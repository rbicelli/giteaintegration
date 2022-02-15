<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

// $criteria = $_GET['criteria'];
$start = $_GET['start'];

Session::checkLoginUser();

Html::header(
    PluginGitlabIntegrationProfiles::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGitlabIntegrationPermissionsMenu",
    "profiles"
);
PluginGitlabIntegrationProfiles::title();
// Search::show('PluginGitlabIntegrationProfiles');
PluginGitlabIntegrationProfiles::configPage($start);
PluginGitlabIntegrationProfiles::massiveActions($start);
PluginGitlabIntegrationProfiles::configPage($start);

Html::footer();

PluginGitlabIntegrationProfiles::dialogActions();
