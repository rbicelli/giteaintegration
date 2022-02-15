<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

Html::header(
    PluginGitlabIntegrationDefaultProject::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGitlabIntegrationDefaultProjectMenu",
    "defaultProject"
);

PluginGitlabIntegrationDefaultProject::showForm();

Html::footer();
