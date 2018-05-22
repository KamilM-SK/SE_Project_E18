<?php
include_once( 'sessioncheck.php' );

if (!isset($_GET['user'])) {
    header('location: vote.php?user=' . $_SESSION['user_id']);
}

if (isset($_GET['user'])) {
    if ($_GET['user'] != $_SESSION['user_id']) {
        header('location: admin/404.php');
    }
}


include_once( '../api/Database.php' );
include_once( '../classes/Appointment.php' );
$appointment = new Appointment($conn);


include_once( 'header.php' );

?>

<main>

    <div class="container-fluid">
        <h1 class="display-4">Vote Meeting Appointment</h1>
        <p>Select all appointments you seem fit for next meeting.</p>
        <br>
        <?php
        $lastMeeting = $meeting->getLastMeetingID($conn);
        $lastMeetingDeadline = $meeting->getMeetingDeadline($conn, $lastMeeting);
        if (time() > strtotime($lastMeetingDeadline)) {
            echo '<br><h3>There is no meeting to vote on at the moment.</h3>';
        } elseif ($meeting->checkIfUserAlreadyVotedOnMeeting($conn, $lastMeeting, $_GET['user'])) {
            echo '<br><h3>You already voted.</h3>';
        } else {

            $description = $meeting->getMeetingDescription($conn, $lastMeeting);
            $result = $appointment->fetchAllAppointmentsForMeeting($conn, $lastMeeting);
            ?>

            <div><h5>Description of the meeting:</h5>
                <p><?php echo $description; ?></p></div><br>

            <form method="post" action="admin/libs/meetings.php?user=<?php echo($_SESSION['user_id']) ?>" enctype="multipart/form-data">

                <?php
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="form-check">';
                    echo '<label class="form-check-label">';
                    echo '<input type="checkbox" name="appointment_id[]" class="form-check-input" value="' . $row['ID'] . '">';
                    echo '<b>' . $row['date'] . '</b> at <b>';
                    echo date('H:i', strtotime($row['time']));
                    echo '</b><em> voted ' . $row['count'] . ' times </em>';
                    echo ' </label>';
                    echo '</div><br>';
                }
                ?>

                <button type="submit" name="vote" class="btn btn-primary">Save</button>

            </form>

        <?php } ?>

    </div>


</main>