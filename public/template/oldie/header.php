<!DOCTYPE html><html lang="en-US"><head>

<meta name="description" content="A system for monitoring and tracking inventory items" />
<meta name="keywords" content="emts,equipments,inventory,monitoring,tracking,system,monitor,track,equipment" />
<meta name="charset" content="UTF-8" />

<title><?php echo SYSTEM_SHORT, ' | ', SYSTEM_NAME; ?></title>

<link rel="icon" type="image/png" src="<?php echo URL_BASE, 'public/img/logo_EMTS_25x25.png' ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE, 'plugins/jquery/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE, 'public/css/style.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL_TEMPLATE, 'style.css'; ?>" />

<script type="text/javascript" src="<?php echo URL_BASE, 'plugins/jquery/jquery-1.11.0.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo URL_BASE, 'plugins/jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js'; ?>"></script>
<script type="text/javascript">
    var $document = $(document)
        , $window = $(window)
        , baseURL = '<?php echo URL_BASE; ?>'
        , templateURL = '<?php echo URL_TEMPLATE; ?>';
</script>
<script type="text/javascript" src="<?php echo URL_BASE, 'public/js/script.js'; ?>"></script>

</head><body>

<div id="header"><span>

    <span>
        <?php
            $file = TEMPLATE_DIR. DS. 'navigation_menu.php';
            if ( file_exists($file) ) require_once($file);
        ?>

        <img class="logo-emts-60x60" src="<?php echo URL_BASE, 'public/img/blank.png'; ?>" />
        <span class="system-short"><?php echo SYSTEM_SHORT; ?></span><br />
        <span class="system-name"><?php echo SYSTEM_NAME; ?></span>
    </span>

    <span>
        <?php
            $loginModel = new login_model();
            $loginView = new login_view($loginModel);
            $loginController = new login_controller($loginModel);

            $loginView->renderOutput();
        ?>

        <div id="system-time-date">
            <span id="system-date"></span>
            <span id="system-time"></span>
        </div>
    </span>

</span></div><!-- End #header -->

<div id="body"><span>