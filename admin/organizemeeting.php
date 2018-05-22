<?php
include_once( 'sessioncheck.php' );


if ($_SESSION['user_type'] != 1) {
    header('location: 403.php');
}

include( 'header.php');
?>

<main>
    <div class="container-fluid">
		
		<?php 
	
			if (isset($_GET['status'])) {
				
				if ($_GET['status'] == 1000) {
					?>

		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success!</strong> New meeting has been added!
		</div>

		<?php
		} }?>
        <h1 class="display-4">Organize Meeting</h1>
        <br>
        <form name="org_form" method="post" enctype="multipart/form-data" action="admin/libs/meetings.php?<?php echo($_SERVER['PHP_SELF']) ?>">

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <textarea rows="4" cols="8" class="form-control" name="meeting_description" placeholder="Add description of the meeting here..."></textarea>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="sel1">Select the date of deadline for voting:</label>
                        <input type="date" name="deadline_date" required class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="sel1">Select the time of deadline for voting:</label>
                        <input type="time" name="deadline_time" required class="form-control">
                    </div>
                </div>
            </div>
         
            <div>Set all possible dates and times for future meeting.</div><br>
            <div id="appointments">
                
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="date" name="date_appointment[]" required class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="time" name="time_appointment[]" required class="form-control">
                        </div>
                    </div>
                </div>


                <div id="row" class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input id="date_appointment" type="date" name="date_appointment[]" required class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input id="time_appointment" type="time" name="time_appointment[]" required class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="Clone('row')" name="add_more" class="btn btn-success">Add more</button> 
            <button type="submit" name="create_poll" class="btn btn-primary">Create poll</button>
            
        </form>



    </div>

</main>

<script> 
function Clone(item) {
    var node = document.getElementById(item);
    var clone = node.cloneNode(true);
    document.getElementById("appointments").appendChild(clone);
}</script>