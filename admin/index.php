<?php 
include_once( 'sessioncheck.php' );
include_once( '../api/Database.php' );
include_once( '../classes/User.php' );

$user = new User($conn);

include('header.php'); 

$result = $magazine->getLastMagazineID($conn);
$latestMagazineID = $result['ID'];

?>


<main>
    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-4">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="colored" >
                            <h4  class="display-4">Next Meeting Session</h4>
                            <h1>
                                <?php $meeting->getLastMeeting($conn); ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dark">
                            ARTICLE DEADLINE
                            <h4>
                                <?php echo $magazine->getDeadlineForWriting($latestMagazineID, $conn);?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dark">
                            REVISION DEADLINE
                            <h4>
                                <?php echo $magazine->getDeadlineForReviewing($latestMagazineID, $conn);?>
                            </h4>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dark">
                            DESIGNER DEADLINE
                            <h4>
                                <?php echo $magazine->getDeadlineForDesign($latestMagazineID, $conn);?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dark">
                            MAGAZINE RELEASE DATE
                            <h4>
                                <?php echo $magazine->getMagazineReleaseDate($latestMagazineID, $conn);?>
                            </h4>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-8 members-list">
                <div >
                    <h4 class="display-4">Members List</h4>

                    <table class="table table-striped">
                        <thead>

                            <tr>
								<th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $membersList = $user->fetchAllMembers($conn);

                            while ($list = $membersList->fetch_assoc()) {
                                echo '<tr>';
								echo '<td><img class="profile_photo_circle"';
								 
								 
								 if ($list['avatar']) echo('src="admin/members/'.$list['avatar'].'"');
								 else echo('src="admin/members/default.jpg"');
								 
								 echo '></td>';
                                echo '<td>' . $list['first_name'] . ' ' . $list['last_name'] . '</td>';
                                echo '<td>' . $list['email'] . '</td>';
                                if ($list['ID'] == $_SESSION['user_id']) {
                                    echo '<td> <a href="myaccount.php?id='.$list['ID'].'" role="button" class="btn btn-primary btn-sm">Edit</a> ';
                                } else {
                                    echo '<td> <a href="admin/messages.php?reciever='.$list['ID'].'" role="button" class="btn btn-warning btn-sm">Send message</a> ';
									echo '<a href="admin/myaccount.php?id='.$list['ID'].'" role="button" class="btn btn-primary btn-sm">View</a>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

</main>