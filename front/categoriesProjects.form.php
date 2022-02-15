<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

Html::header(
    PluginGitlabIntegrationCategoriesProjects::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGitlabIntegrationCategoriesProjectsMenu",
    "categoriesProjects"
);

PluginGitlabIntegrationCategoriesProjects::showForm();

Html::footer();
