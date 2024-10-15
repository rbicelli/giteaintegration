<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

// $criteria = $_GET['criteria'];
$start = $_GET['start'];

Session::checkLoginUser();

Html::header(
    PluginGiteaIntegrationDefaultProject::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGiteaIntegrationDefaultProjectMenu",
    "defaultProject"
);
PluginGiteaIntegrationDefaultProject::title();
// Search::show('PluginGiteaIntegrationDefaultProject');
PluginGiteaIntegrationDefaultProject::configPage($start);
PluginGiteaIntegrationDefaultProject::massiveActions($start);
PluginGiteaIntegrationDefaultProject::configPage($start);

Html::footer();

PluginGiteaIntegrationDefaultProject::dialogActions();
