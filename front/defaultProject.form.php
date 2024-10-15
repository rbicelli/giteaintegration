<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

Html::header(
    PluginGiteaIntegrationDefaultProject::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGiteaIntegrationDefaultProjectMenu",
    "defaultProject"
);

PluginGiteaIntegrationDefaultProject::showForm();

Html::footer();
