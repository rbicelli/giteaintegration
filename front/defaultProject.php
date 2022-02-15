<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

// $criteria = $_GET['criteria'];
$start = $_GET['start'];

Session::checkLoginUser();

Html::header(
    PluginGitlabIntegrationDefaultProject::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGitlabIntegrationDefaultProjectMenu",
    "defaultProject"
);
PluginGitlabIntegrationDefaultProject::title();
// Search::show('PluginGitlabIntegrationDefaultProject');
PluginGitlabIntegrationDefaultProject::configPage($start);
PluginGitlabIntegrationDefaultProject::massiveActions($start);
PluginGitlabIntegrationDefaultProject::configPage($start);

Html::footer();

PluginGitlabIntegrationDefaultProject::dialogActions();
