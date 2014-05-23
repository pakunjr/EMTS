<?php
    /**
     * 403 ERROR page is shown when a page is being
     * accessed by an unauthorized user or visitor.
     */
?>

<div style="display: inline-block; max-width: 350px;">
    <b style="font-size: 18pt; color: #f00;">403 ERORR: Forbidden access</b><br /><br />
    Sorry, but it seems like you don't have enough authorization to access this page.<br /><br />
    You can contact a system administrator and give the following information / data below to know more why you were encountering this error.<br />Thank you.<br /><br />
    <?php
        $GLOBALS['pageView']->displayURI();
    ?>
</div>