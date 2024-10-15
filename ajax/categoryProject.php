<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

// Modo: 1 = associate Project 0 = Delete association
$project_id = $_POST['project_id'];
$category_id = $_POST['category_id'];
$project_name = project($project_id);
$modo = $_POST['modo'];

function project($project_id)
{
    $url = "https://forge.sirailgroup.com/api/v4/projects/$project_id";
    $headers = array(
        "PRIVATE-TOKEN: f2oXXH_jT-Pzy4gUzEHs"
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $result = json_decode($result);
    curl_close($curl);

    return $result->name_with_namespace;
};
//INSERT
if ($modo == 1) {

    $result = $DB->request('glpi_plugin_gitea_projects', ['OR' => ['project_id' => $project_id, 'category_id' => $category_id]]);
    var_dump($result->next());
    if ($result->count() > 0) {
        Session::addMessageAfterRedirect(__("The selected Project or Category is already associated", 'giteaintegration'));
    } else {
        $DB->insert(
            'glpi_plugin_gitea_projects',
            [
                'project_id'    => $project_id,
                'project_name'  => $project_name,
                'category_id'  => $category_id,
                'created_at' => $_SESSION["glpi_currenttime"]
            ]
        );

        Session::addMessageAfterRedirect(__("The $project_name is associated", 'giteaintegration'));
    }
}

//DELETE

if ($modo == 0) {
    $result = $DB->request('glpi_plugin_gitea_projects', ['project_id' => $project_id, 'category_id' => $category_id]);
    if ($result->count() > 0) {
        $DB->delete(
            'glpi_plugin_gitea_projects',
            [
                'project_id' => $project_id,
                'category_id' => $category_id
            ]
        );

        PluginGiteaIntegrationEventLog::Log($id, 'profiles', $_SESSION["glpi_currenttime"], 'gitea', 4, sprintf(__('%2s removed permission for id ' . $id, 'giteaintegration'), $_SESSION["glpiname"]));

        Session::addMessageAfterRedirect(__('Association removed successfully!', 'giteaintegration'));
    } else {
        $erro = "[" . $_SESSION["glpi_currenttime"] . "] glpiphplog.ERROR: PluginGiteaIntegrationProfiles::permissions() in profile.php line 10" . PHP_EOL;
        $erro = $erro . "  ***PHP Notice: The selected Project can't be deleted: Id: " . $id;
        PluginGiteaIntegrationEventLog::ErrorLog($erro);

        Session::addMessageAfterRedirect(__('The selected profile can not be deleted. Verify logs for more information!'));
    }
}

// modo 2 insert a default Project:
// Modo 3 remove default Project:
// set Default Project

if ($modo == 2) {
    $result = $DB->request("SELECT * FROM glpi_plugin_gitea_projects WHERE general = 1 OR project_id = $project_id");
    $project = $result->next();
    if ($result->count() > 0) {
        Session::addMessageAfterRedirect(__("a default Project already exists or The $project_name is associated with a category", 'giteaintegration'));
    } else {
        $project_name = project($project_id);
        $DB->insert(
            'glpi_plugin_gitea_projects',
            [
                'project_id'    => $project_id,
                'project_name'  => $project_name,
                'general'       => 1,
                'created_at' => $_SESSION["glpi_currenttime"]
            ]
        );

        Session::addMessageAfterRedirect(__("The $defaultProjectName is set as Default Project", 'giteaintegration'));
    }
}

if ($modo == 3) {
    $result = $DB->request('glpi_plugin_gitea_projects', ['project_id' => $project_id]);
    echo $result->count();
    if ($result->count() > 0) {
        $DB->delete(
            'glpi_plugin_gitea_projects',
            [
                'project_id' => $project_id,
                'general' => 1
            ]
        );

        PluginGiteaIntegrationEventLog::Log($id, 'profiles', $_SESSION["glpi_currenttime"], 'gitea', 4, sprintf(__('%2s removed permission for id ' . $id, 'giteaintegration'), $_SESSION["glpiname"]));

        Session::addMessageAfterRedirect(__('The Project was removed as default!', 'giteaintegration'));
    }
}
