<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

Html::header(
    PluginGiteaIntegrationProfiles::getTypeName(),
    $_SERVER['PHP_SELF'],
    "admin",
    "PluginGiteaIntegrationPermissionsMenu",
    "profiles"
);

PluginGiteaIntegrationProfiles::showForm();

Html::footer();
