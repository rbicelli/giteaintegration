<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

// $criteria = $_GET['criteria'];
$start = $_GET['start'];

Session::checkLoginUser();

Html::header(
    PluginGiteaIntegrationProfiles::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGiteaIntegrationPermissionsMenu",
    "profiles"
);
PluginGiteaIntegrationProfiles::title();
// Search::show('PluginGiteaIntegrationProfiles');
PluginGiteaIntegrationProfiles::configPage($start);
PluginGiteaIntegrationProfiles::massiveActions($start);
PluginGiteaIntegrationProfiles::configPage($start);

Html::footer();

PluginGiteaIntegrationProfiles::dialogActions();
