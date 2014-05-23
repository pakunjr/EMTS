<?php

/**
 * System configurations.
 */
$cfg['SYSTEM_NAME'] = 'Equipments Monitoring and Tracking System';
$cfg['SYSTEM_SHORT'] = 'EMTS';
$cfg['SYSTEM_AUTHOR'] = 'Palmer C. Gawaban Jr.';
$cfg['SYSTEM_YEAR'] = '2014';
$cfg['SYSTEM_COMPANY'] = 'LORMA COLLEGES';
$cfg['SYSTEM_TEMPLATE'] = 'colorbox';

/**
 * URLs
 */
$cfg['URL_BASE'] = 'http://'.$_SERVER['HTTP_HOST'].'/EMTS/';
$cfg['URL_TEMPLATE'] = $cfg['URL_BASE']
    .'public/template/'
    .$cfg['SYSTEM_TEMPLATE'].'/';

/**
 * Database settings.
 */
$cfg['DATABASE_HOST'] = '127.0.0.1';
$cfg['DATABASE_USERNAME'] = 'root';
$cfg['DATABASE_PASSWORD'] = 'sysdev09';
$cfg['DATABASE_NAME'] = 'db_emts';
$cfg['DATABASE_PORT'] = '3306';
$cfg['DATABASE_SOCKET'] = '';

/**
 * Define the settings as CONSTANT VARIABLES.
 */
foreach ( $cfg as $c => $o ) { defined($c) or define($c, $o); }

/**
 * Get phpfastcache and configure it.
 *** Get back on this later, it is too gaah! ***
 */
require_once(PLUGINS_DIR.DS.'php_fast_cache'.DS.'detect_visitor_location'.DS.'php_fast_cache.php');
$phpFastCache = new phpFastCache();
$phpFastCache->set('equipment_monitoring_and_tracking_system', 'EMTS', 600);
$value = $phpFastCache->get('equipment_monitoring_and_tracking_system');
