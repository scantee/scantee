<div class='page_wrapper'>
    <h3>Dashboard</h3>
    <div style='text-align:left;'>    
    <?php
    echo '<div>This is the dashboard index - <a href="/dashboard/logout">logout</a></div>';
    echo '<div style="margin-top:20px;">These are the session variables:</div>';
    echo '<div><b>Username: </b>'.$_SESSION['u_name'].'</div>';
    echo '<div><b>First Name: </b>'.$_SESSION['f_name'].'</div>';
    echo '<div><b>Last Name: </b>'.$_SESSION['l_name'].'</div>';
    ?>
    </div>
</div>

