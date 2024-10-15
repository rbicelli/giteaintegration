<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

// Modo: 1 = Insert 0 = Delete
$modo = (int)$_POST['modo'];

$profileId = (int)$_POST['profileId'];
$userId = (int)$_POST['userId'];
$id = (int)$_POST['id'];

//INSERT
if ($profileId) {
    if ($modo == 1) {
        $result = $DB->request('glpi_plugin_gitea_profiles_users', ['profile_id' => [$profileId]]);
        if ($result->count() > 0) {
            $erro = "[" . $_SESSION["glpi_currenttime"] . "] glpiphplog.ERROR: PluginGiteaIntegrationProfiles::permissions() in profile.php line 10" . PHP_EOL;
            $erro = $erro . "  ***PHP Notice: The selected profile already has permission: Profile Id: " . $profileId;
            PluginGiteaIntegrationEventLog::ErrorLog($erro);

            Session::addMessageAfterRedirect(__('The selected profile already has permission. Verify logs for more information!', 'giteaintegration'));
        } else {
            $DB->insert(
                'glpi_plugin_gitea_profiles_users',
                [
                    'profile_id' => $profileId,
                    'user_id'    => $userId,
                    'created_at' => $_SESSION["glpi_currenttime"]
                ]
            );

            PluginGiteaIntegrationEventLog::Log($profileId, 'profiles', $_SESSION["glpi_currenttime"], 'gitea', 4, sprintf(__('%2s granted permission for profile ' . $profileId, 'giteaintegration'), $_SESSION["glpiname"]));

            Session::addMessageAfterRedirect(__('Permission granted successfully!', 'giteaintegration'));
        }
    }
}

//DELETE
if ($id) {
    if ($modo == 0) {
        $result = $DB->request('glpi_plugin_gitea_profiles_users', ['id' => [$id]]);
        if ($result->count() > 0) {
            $DB->delete(
                'glpi_plugin_gitea_profiles_users',
                [
                    'id' => $id
                ]
            );

            PluginGiteaIntegrationEventLog::Log($id, 'profiles', $_SESSION["glpi_currenttime"], 'gitea', 4, sprintf(__('%2s removed permission for id ' . $id, 'giteaintegration'), $_SESSION["glpiname"]));

            Session::addMessageAfterRedirect(__('Permission removed with successfully!', 'giteaintegration'));
        } else {
            $erro = "[" . $_SESSION["glpi_currenttime"] . "] glpiphplog.ERROR: PluginGiteaIntegrationProfiles::permissions() in profile.php line 10" . PHP_EOL;
            $erro = $erro . "  ***PHP Notice: The selected profile can't be deleted: Id: " . $id;
            PluginGiteaIntegrationEventLog::ErrorLog($erro);

            Session::addMessageAfterRedirect(__('The selected profile can not be deleted. Verify logs for more information!'));
        }
    }
}
