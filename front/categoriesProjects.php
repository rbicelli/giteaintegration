<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

// $criteria = $_GET['criteria'];
$start = $_GET['start'];

Session::checkLoginUser();

Html::header(
    PluginGiteaIntegrationCategoriesProjects::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGiteaIntegrationCategoriesProjectsMenu",
    "categoriesProjects"
);
PluginGiteaIntegrationCategoriesProjects::title();
// Search::show('PluginGiteaIntegrationCategoriesProjects');
PluginGiteaIntegrationCategoriesProjects::configPage($start);
PluginGiteaIntegrationCategoriesProjects::massiveActions($start);
PluginGiteaIntegrationCategoriesProjects::configPage($start);

Html::footer();

PluginGiteaIntegrationCategoriesProjects::dialogActions();
