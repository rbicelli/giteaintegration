<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

// $criteria = $_GET['criteria'];
$start = $_GET['start'];

Session::checkLoginUser();

Html::header(
    PluginGitlabIntegrationCategoriesProjects::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGitlabIntegrationCategoriesProjectsMenu",
    "categoriesProjects"
);
PluginGitlabIntegrationCategoriesProjects::title();
// Search::show('PluginGitlabIntegrationCategoriesProjects');
PluginGitlabIntegrationCategoriesProjects::configPage($start);
PluginGitlabIntegrationCategoriesProjects::massiveActions($start);
PluginGitlabIntegrationCategoriesProjects::configPage($start);

Html::footer();

PluginGitlabIntegrationCategoriesProjects::dialogActions();
