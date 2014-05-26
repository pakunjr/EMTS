<?php
    /**
     * Unknown Error.
     */
?>

<div style="display: inline-block; max-width: 500px;">
    <b style="font-size: 18pt; color: #f00;">Unknown Page ERORR: </b><br /><br />
    Sorry, but it seems like you have encountered an unknown error.<br /><br />
    You can contact a system administrator and give the following information / data below to know more why you were encountering this error. Thank you.<br /><br />
    <?php
        $GLOBALS['pageView']->displayURI();
    ?>
</div>