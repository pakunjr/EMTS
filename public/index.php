<?php

$bootstrap_file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'bootstrap.php';
if ( file_exists($bootstrap_file) ) require_once($bootstrap_file);
else {
    echo '<!DOCTYPE html><html><head></head><body><div>FATAL ERROR: bootstrap file for the system is missing!</div></body></html>';
    exit();
}


/**
 * Render the page
 */
$uri = isset($_GET['url']) ? $_GET['url'] : 'home';

$pageModel = new pageModel($uri);
$pageView = new pageView($pageModel);
$pageController = new pageController($pageModel);

$pageController->routePage();
$pageView->renderPage();

