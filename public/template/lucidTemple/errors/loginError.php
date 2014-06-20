<?php
    /**
     * 404 ERROR page is shown when a missing file
     * or a page not found have been encountered.
     */
?>

<div style="display: inline-block; max-width: 500px;">
    <b style="font-size: 18pt; color: #f00;">Login Error: Invalid combination of username and password.</b><br /><br />
    Sorry, but it seems like your username and password combination is incorrect, please try again.<br /><br />
    You can contact a system administrator and give the following information / data below to know more why you were encountering this error. Thank you.<br /><br />
    <?php
        echo '<b>Username</b>: ',$_POST['username'];
    ?>
    <br />
    
    <hr />
    <a href="#forgotPassword">Forgot Password?</a>
</div>