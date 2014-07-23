<!DOCTYPE html><html><head>

    <title><?php echo SYSTEM_SHORT,' | ',SYSTEM_NAME; ?></title>

    <link rel="icon" type="image/png" href="<?php echo URL_BASE,'public/img/logo_EMTS_25x25.png'; ?>" />

    <link rel="stylesheet" type="text/css" href="<?php echo URL_BASE,'public/css/normalize.css'; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo URL_BASE,'plugins/jquery/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo URL_TEMPLATE,'css/style.css'; ?>" />

    <script type="text/javascript" src="<?php echo URL_BASE,'plugins/jquery/jquery-1.11.0.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo URL_BASE,'plugins/jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js'; ?>"></script>
    <script type="text/javascript">
        var URLBase = '<?php echo URL_BASE; ?>'
            ,URLTemplate = '<?php echo URL_TEMPLATE; ?>';
    </script>
    <script type="text/javascript" src="<?php echo URL_BASE,'plugins/jquery/jquery.alphanumeric.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo URL_TEMPLATE,'js/script.js'; ?>"></script>

</head><body>

<?php $GLOBALS['pageView']->renderNavigation(); ?>

<div class="clear"></div>

<div id="content">
