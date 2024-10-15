<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

Html::header(
    PluginGiteaIntegrationCategoriesProjects::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGiteaIntegrationCategoriesProjectsMenu",
    "categoriesProjects"
);

PluginGiteaIntegrationCategoriesProjects::showForm();

Html::footer();
