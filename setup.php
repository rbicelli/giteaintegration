<?php
define('PLUGIN_GITINTEGRATION_VERSION', '0.0.1');

// Minimal GLPI version, inclusive
define("PLUGIN_GITINTEGRATION_MIN_GLPI_VERSION", "10.0.0");
// Maximum GLPI version, exclusive
define("PLUGIN_GITINTEGRATION_MAX_GLPI_VERSION", "10.0.99");

function plugin_init_giteaintegration()
{

	global $PLUGIN_HOOKS, $CFG_GLPI;

	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/itemform.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/eventlog.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/parameters.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/giteaintegration.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/permissionsMenu.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/categoriesProjectsMenu.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/defaultProjectMenu.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/profiles.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/categoriesProjects.class.php");
	include_once(GLPI_ROOT . "/plugins/giteaintegration/inc/defaultProject.class.php");

	$PLUGIN_HOOKS['add_css']['giteaintegration'][] = "css/styles.css";
	$PLUGIN_HOOKS['add_javascript']['giteaintegration'][] = 'js/buttonsFunctions.js';

	// CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
	$PLUGIN_HOOKS['csrf_compliant']['giteaintegration'] = true;

	if (class_exists('PluginGiteaIntegrationItemForm')) {
		$PLUGIN_HOOKS['post_item_form']['giteaintegration'] = ['PluginGiteaIntegrationItemForm', 'postItemForm'];
	}

	// add entry to configuration menu
	$PLUGIN_HOOKS['menu_toadd']['giteaintegration']['admin'] = ['PluginGiteasIntegrationPermissionsMenu', 'PluginGiteaIntegrationCategoriesProjectsMenu', 'PluginGiteaIntegrationDefaultProjectMenu'];
}


function plugin_version_giteaintegration()
{	
	return [
		'name'			  => 'Gitea Integration',
		'version' 		  => PLUGIN_GITINTEGRATION_VERSION,
		'author'		  => 'Riccardo Bicelli',
		'license'		  => 'GPLv3+',
		'homepage'		  => 'https://github.com/rbicelli/giteaintegration',
		'requirements'    => [
            'glpi' => [
                'min' => PLUGIN_GITINTEGRATION_MIN_GLPI_VERSION,
                'max' => PLUGIN_GITINTEGRATION_MAX_GLPI_VERSION,
            ]
		]
	];
}


function plugin_giteaintegration_check_prerequisites()
{
		return true;	
}


function plugin_giteaintegration_check_config($verbose = false)
{
	if ($verbose) {
		echo 'Installed / not configured';
	}
	return true;
}