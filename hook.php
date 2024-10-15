<?php

function plugin_giteaintegration_install()
{

	global $DB;

	$config = new Config();
	$config->setConfigurationValues('plugin:Gitea Integration', ['configuration' => false]);

	ProfileRight::addProfileRights(['giteaintegration:read']);

	//instanciate migration with version
	$migration = new Migration(100);

	// //Create table glpi_plugin_gitea_integration only if it does not exists yet!
	plugin_giteaintegration_create_integration($DB);

	//Create table glpi_plugin_gitea_profiles only if it does not exists yet!
	plugin_giteaintegration_create_profiles($DB);

	//Create table glpi_plugin_gitea_categories_projects only if it does not exists yet!
	plugin_giteaintegration_create_projects($DB);

	//Create table glpi_plugin_gitea_parameters only if it does not exists yet!
	plugin_giteaintegration_create_parameters($DB);

	//Insert parameters at table glpi_plugin_gitea_parameters only if it exist!
	plugin_giteaintegration_insert_parameters($DB);

	return true;
}

function plugin_giteaintegration_uninstall()
{

	global $DB;

	$config = new Config();
	$config->deleteConfigurationValues('plugin:Gitea Integration', ['configuration' => false]);

	ProfileRight::deleteProfileRights(['giteaintegration:read']);

	$notif = new Notification();
	$options = [
		'itemtype' => 'Ticket',
		'event'    => 'plugin_giteaintegration',
		'FIELDS'   => 'id'
	];
	foreach ($DB->request('glpi_notifications', $options) as $data) {
		$notif->delete($data);
	}

	//Drop table glpi_plugin_gitea_integration only if it exists!
	plugin_giteaintegration_delete_integration($DB);

	//Drop table glpi_plugin_gitea_profiles_users only if it exists!
	plugin_giteaintegration_delete_profiles($DB);

	//Drop table glpi_plugin_gitea_parameters only if it exists!
	plugin_giteaintegration_delete_parameters($DB);

	//Drop table glpi_plugin_gitea_projects only if it exists!
	plugin_giteaintegration_delete_projects($DB);

	return true;
}

function plugin_giteaintegration_create_integration($DB)
{
	if (!$DB->tableExists('glpi_plugin_gitea_integration')) {
		$query = "CREATE TABLE `glpi_plugin_gitea_integration` (
				   `id` INT(11) NOT NULL AUTO_INCREMENT,
				   `ticket_id` INT(11) NOT NULL,
				   `gitea_project_id` INT(11) NOT NULL,
				   `gitea_member_id` INT (11) NOT NULL,
				   PRIMARY KEY  (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$DB->queryOrDie($query, $DB->error());

		$query = "ALTER TABLE `glpi_plugin_gitea_integration`
	                ADD CONSTRAINT `fk_gitea_ticket`
					FOREIGN KEY (`ticket_id`) REFERENCES `glpi_tickets` (`id`)";
		$DB->queryOrDie($query, $DB->error());
	}
}

function plugin_giteaintegration_create_profiles($DB)
{
	if (!$DB->tableExists('glpi_plugin_gitea_profiles_users')) {
		$query = "CREATE TABLE `glpi_plugin_gitea_profiles_users` (
				   `id` INT(11) NOT NULL AUTO_INCREMENT,
				   `profile_id` INT(11) NOT NULL,
				   `user_id` INT(11) NOT NULL,
				   `created_at` DATETIME,
				   PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$DB->queryOrDie($query, $DB->error());

		$query = "ALTER TABLE `glpi_plugin_gitea_profiles_users`
	                ADD CONSTRAINT `fk_gitea_profile`
					FOREIGN KEY (`profile_id`) REFERENCES `glpi_profiles` (`id`)";
		$DB->queryOrDie($query, $DB->error());

		$query = "ALTER TABLE `glpi_plugin_gitea_profiles_users` 
	                ADD CONSTRAINT `fk_gitea_user` 
					FOREIGN KEY (`user_id`) REFERENCES `glpi_users` (`id`)";
		$DB->queryOrDie($query, $DB->error());
	}
}

function plugin_giteaintegration_create_projects($DB)
{
	if (!$DB->tableExists('glpi_plugin_gitea_projects')) {
		$query = "CREATE TABLE `glpi_plugin_gitea_projects` (
				   `id` INT(11) NOT NULL AUTO_INCREMENT,
				   `project_id` INT(11) NOT NULL,
				   `project_name` VARCHAR(255) NOT NULL,
				   `category_id` INT(11) NULL,
				   `general` BOOLEAN NOT NULL DEFAULT 0,
				   `created_at` DATETIME,
				   PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$DB->queryOrDie($query, $DB->error());

		$query = "ALTER TABLE `glpi_plugin_gitea_projects`
	                ADD CONSTRAINT `fk_gitea_categories`
					FOREIGN KEY (`category_id`) REFERENCES `glpi_itilcategories` (`id`)";
		$DB->queryOrDie($query, $DB->error());
	}
}

function plugin_giteaintegration_create_parameters($DB)
{
	if (!$DB->tableExists('glpi_plugin_gitea_parameters')) {
		$query = "CREATE TABLE `glpi_plugin_gitea_parameters` (
				   `id` INT(11) NOT NULL AUTO_INCREMENT,
				   `name` VARCHAR(50) NOT NULL,
				   `value` VARCHAR(125),
				   PRIMARY KEY  (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$DB->queryOrDie($query, $DB->error());

		$query = "ALTER TABLE `glpi_plugin_gitea_parameters`
	                ADD CONSTRAINT `uk_name`
					UNIQUE (`name`) ";
		$DB->queryOrDie($query, $DB->error());
	}
}

function plugin_giteaintegration_delete_integration($DB)
{
	if ($DB->tableExists('glpi_plugin_gitea_integration')) {
		$drop_count = "DROP TABLE glpi_plugin_gitea_integration";
		$DB->query($drop_count);
	}
}

function plugin_giteaintegration_delete_profiles($DB)
{
	if ($DB->tableExists('glpi_plugin_gitea_profiles_users')) {
		$drop_count = "DROP TABLE glpi_plugin_gitea_profiles_users";
		$DB->query($drop_count);
	}
}

function plugin_giteaintegration_delete_projects($DB)
{
	if ($DB->tableExists('glpi_plugin_gitea_projects')) {
		$drop_count = "DROP TABLE glpi_plugin_gitea_projects";
		$DB->query($drop_count);
	}
}

function plugin_giteaintegration_delete_parameters($DB)
{
	if ($DB->tableExists('glpi_plugin_gitea_parameters')) {
		$drop_count = "DROP TABLE glpi_plugin_gitea_parameters";
		$DB->query($drop_count);
	}
}

function plugin_giteaintegration_insert_parameters($DB)
{
	if ($DB->tableExists('glpi_plugin_gitea_parameters')) {

		$ini_array = parse_ini_file("giteaintegration.ini");

		$parameters = [
			[
				'name'  => 'gitea_url',
				'value' => $ini_array['GITLAB_URL'] == "" ? NULL : $ini_array['GITLAB_URL']
			],
			[
				'name'  => 'gitea_token',
				'value' => $ini_array['GITLAB_TOKEN'] == "" ? NULL : $ini_array['GITLAB_TOKEN']
			]
		];

		foreach ($parameters as $parameter) {
			$DB->insert(
				'glpi_plugin_gitea_parameters',
				[
					'name'  => $parameter['name'],
					'value' => $parameter['value']
				]
			);
		}
	}
}
