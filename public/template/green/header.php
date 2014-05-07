<!DOCTYPE html><html><head>

<title><?php echo SYSTEM_SHORT, ' | ', SYSTEM_NAME; ?></title>

<link rel="shortcut icon" type="image/png" href="<?php echo BASE; ?>public/img/logo_EMTS_25x25.png" />
<link rel="stylesheet" type="text/css" href="<?php echo BASE; ?>plugins/jquery/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php echo BASE; ?>public/css/style.css" />

<script type="text/javascript" src="<?php echo BASE; ?>plugins/jquery/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo BASE; ?>plugins/jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript">
    var $window = $(window),
        $document = $(document),
        baseURL = '<?php echo BASE; ?>';
</script>

</head><body>
<ul id="navigation">
    <li><a href="<?php echo BASE; ?>"><img class="icon-home" src="<?php echo BASE; ?>public/img/blank.png" />Home</a></li>

    <?php $auth = new authorization; ?>
    <?php if ( $auth->IsLoggedIn() ) : ?>
    <?php if ( $auth->IsAdministrator() || $auth->IsSupervisor() ) : ?>
    <li><a href="#"><img class="icon-human" src="<?php echo BASE; ?>public/img/blank.png" />Accounts</a>
        <div class="sub-menu">
            <a href="<?php echo BASE; ?>"><img class="icon-addHuman" src="<?php echo BASE; ?>public/img/blank.png" />Register New Account</a>
            <a href="<?php echo BASE; ?>"><img class="icon-edit" src="<?php echo BASE; ?>public/img/blank.png" />Edit Existing Account</a>
            <a href="<?php echo BASE; ?>"><img class="icon-monitor" src="<?php echo BASE; ?>public/img/blank.png" />Activity Logs</a>
        </div>
    </li>
    <li><a href="#"><img class="icon-gear" src="<?php echo BASE; ?>public/img/blank.png" />Administration</a>
        <div class="sub-menu">
            <a href="<?php echo BASE; ?>"><img class="icon-human" src="<?php echo BASE; ?>public/img/blank.png" />Employee</a>
            <a href="<?php echo BASE; ?>"><img class="icon-chair" src="<?php echo BASE; ?>public/img/blank.png" />Office</a>
            <a href="<?php echo BASE; ?>"><img class="icon-briefcase" src="<?php echo BASE; ?>public/img/blank.png" />Department</a>
            <a href="<?php echo BASE; ?>"><img class="icon-report" src="<?php echo BASE; ?>public/img/blank.png" />Equipments</a>
        </div>
    </li>
    <?php endif; //End IsAdministrator || IsSupervisor ?>

    <li><a href="#"><img class="icon-monitor" src="<?php echo BASE; ?>public/img/blank.png" />Monitor</a>
        <div class="sub-menu">
            <a href="<?php echo BASE; ?>"><img class="icon-report" src="<?php echo BASE; ?>public/img/blank.png" />Equipments</a>
            <a href="<?php echo BASE; ?>"><img class="icon-human" src="<?php echo BASE; ?>public/img/blank.png" />Persons</a>
        </div>
    </li>
    <li><a href="#"><img class="icon-report" src="<?php echo BASE; ?>public/img/blank.png" />Reports</a>
        <div class="sub-menu">
            <a href="<?php echo BASE; ?>"><img class="icon-view" src="<?php echo BASE; ?>public/img/blank.png" />View Statistical Report</a>
        </div>
    </li>
    <?php endif; //End IsLoggedIn ?>
</ul><!-- End #navigation -->
<div id="header"><span>

    <?php require_once(VIEWS_DIR. DS
                        . 'authorization'. DS
                        . 'authorization.php'); ?>

    <div id="system-time-date">
        <span id="system-date"></span>
        <span id="system-time"></span>
    </div><!-- End #system-time-date -->
    <img class="logo-emts-60x60" src="<?php echo BASE; ?>public/img/blank.png" />
    <span class="system-short"><?php echo SYSTEM_SHORT; ?></span><br />
    <span class="system-name"><?php echo SYSTEM_NAME; ?></span>
</span></div><!-- End #header -->
<div id="body"><span>