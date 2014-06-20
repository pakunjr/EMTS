<?php
    /**
     * 404 ERROR page is shown when a missing file
     * or a page not found have been encountered.
     */
?>

<div style="display: inline-block; max-width: 500px;">
    <b style="font-size: 18pt; color: #f00;">This page is Underconstruction / Maintenance</b><br /><br />
    Sorry but the page you are accessing is currently under construction or under maintenance. Please bear with us. Thank you.<br /><br />
    <?php
        $GLOBALS['pageView']->displayURI();
    ?>
    <br />This page Under Construction / Maintenance
</div>