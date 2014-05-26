<?php
    /**
     * 404 ERROR page is shown when a missing file
     * or a page not found have been encountered.
     */
?>

<div style="display: inline-block; max-width: 500px;">
    <b style="font-size: 18pt; color: #f00;">404 ERORR: Page not found</b><br /><br />
    Sorry, but it seems like that the page that you were trying to access doesn't exist in our system.<br /><br />
    You can contact a system administrator and give the following information / data below to know more why you were encountering this error. Thank you.<br /><br />
    <?php
        $GLOBALS['pageView']->displayURI();
    ?>
</div>