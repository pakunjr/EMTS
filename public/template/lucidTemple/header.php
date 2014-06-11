<!DOCTYPE html><html><head>

    <title><?php echo SYSTEM_SHORT,' | ',SYSTEM_NAME; ?></title>

    <link rel="icon" type="image/png" href="<?php echo URL_BASE,'public/img/logo_EMTS_25x25.png'; ?>" />

    <link rel="stylesheet" type="text/css" href="<?php echo URL_BASE,'public/css/normalize.css'; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo URL_BASE,'plugins/jquery/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo URL_TEMPLATE,'css/style.css'; ?>" />

    <script type="text/javascript" src="<?php echo URL_BASE,'plugins/jquery/jquery-1.11.0.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo URL_BASE,'plugins/jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js'; ?>"></script>

</head><body>

<div id="navigation">
    <span>
        <a href="<?php echo URL_BASE; ?>">
            <img class="logo-emts-60x60" src="<?php echo URL_BASE,'public/img/logo_EMTS_60x60.png'; ?>" style="display: inline-block; margin: 2px 3px; padding: 0px; vertical-align: middle;" />
            <span style="display: inline-block; padding: 0px 10px 0px 5px; vertical-align: middle">
                <strong style="letter-spacing: 30pt; font-size: 2.5em;"><?php echo SYSTEM_SHORT; ?></strong><br />
                <small><?php echo SYSTEM_NAME; ?></small>
            </span>
        </a>
    </span>

    <span class="menu-items">
        <a href="<?php echo URL_BASE,'items/'; ?>">Items</a>
        <ol type="none" class="submenu">
            <li><a href="<?php echo URL_BASE,'items/new_item/'; ?>">New Item/s</a></li>
        </ol>
    </span>

    <span class="menu-items">
        <a href="<?php echo URL_BASE,'owners/'; ?>">Owners</a>
        <ol type="none" class="submenu">
            <li><a href="<?php echo URL_BASE,'owners/complete_list/'; ?>">Complete List</a></li>
        </ol>
    </span>

    <span class="menu-items">
        <a href="<?php echo URL_BASE,'reports/'; ?>">Reports</a>
        <ol type="none" class="submenu">
            <li><a href="<?php echo URL_BASE,'reports/generate/'; ?>">Generate Report</a></li>
            <li><a href="<?php echo URL_BASE,'reports/history/'; ?>">History Report</a></li>
        </ol>
    </span>

    <span class="menu-items">
        <a href="<?php echo URL_BASE,'archives/'; ?>">Archives</a>
    </span>

    <span id="user">
        <?php
            $loginModel = new loginModel();
            $loginView = new loginView($loginModel);
            $loginView->showLogin();
        ?>
    </span>
</div>

<div class="clear"></div>

<div id="content">
