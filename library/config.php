<?php

/**
 * System configurations.
 */
$cfg['SYSTEM_NAME'] = 'Equipments Monitoring and Tracking System';
$cfg['SYSTEM_SHORT'] = 'EMTS';
$cfg['SYSTEM_AUTHOR'] = 'Palmer C. Gawaban Jr.';
$cfg['SYSTEM_YEAR'] = '2014';
$cfg['SYSTEM_COMPANY'] = 'LORMA COLLEGES';
$cfg['SYSTEM_TEMPLATE'] = 'lucidTemple';

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
 * Configure and include phpfastcache version 2
 */
$f = PLUGINS_DIR.DS.'phpfastcache'.DS.'phpfastcache_v2.1_release'.DS.'phpfastcache'.DS.'phpfastcache.php';
if ( file_exists($f) ) require_once($f);
else echo '<div style="color: #f00;"><b>Error: </b>phpfastcache plugin is broken.<br />Please contact a system administrator immediately regarding this error.<br />Thank you.</div>';
phpFastCache::setup('storage', 'auto');
$cache = phpFastCache();
