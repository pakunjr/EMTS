<?php
    /**
     * 403 ERROR page is shown when a page is being
     * accessed by an unauthorized user or visitor.
     */
?>

<div style="display: inline-block; max-width: 500px;">
    <b style="font-size: 18pt; color: #f00;">403 ERORR: Forbidden access</b><br /><br />
    Sorry, but it seems like you don't have enough authorization to access this page.<br /><small>Note: </small>If you have an account, please login first.<br /><br />
    You can contact a system administrator and give the following information / data below to know more why you were encountering this error. Thank you.<br /><br />
    <?php
        $GLOBALS['pageView']->displayURI();
    ?>
</div>