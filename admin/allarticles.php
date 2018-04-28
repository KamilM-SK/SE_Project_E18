<?php
session_start();

include( '../api/Database.php' );
include( '../classes/User.php' );

$user = new User($conn);

include( 'header.php' )
?>


<main>

    <div class="container-fluid">
        <h1 class="display-4">All Articles</h1>
        
        <div class="columns-top">
            <select name="magazine" class="form-control">
                <option value="">Number 17</option>
                <option value="">Number 16</option>
            </select>
            
        </div>
        
        <br>
        
        <div class="columns">
            <div class="column-left">test<br>
                test<br>test<br>test<br>test<br>test<br>test<br>
            
            
            
            </div>
            <div class="column-right">tetst</div>
            
        </div>
        
        
    </div>
    
</main>