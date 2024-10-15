<?php
define('GLPI_ROOT', '../../..');
include(GLPI_ROOT . "/inc/includes.php");

Session::checkLoginUser();

$selectedProject = (int)$_POST['selectedProject'];
$selectedCategory = (int)$_POST['selectedCategory'];
$ticketId = (int)$_POST['ticketId'];
$ticketName = $_POST['ticketName'];
$ticketContent = $_POST['ticketContent'];
$ticketDueDate = $_POST['ticketDueDate'];
$ticketType = $_POST['ticketType'];
$ticketLabel = $_POST['ticketLabel'];

$result = $DB->request('glpi_plugin_gitea_integration', ['ticket_id' => $ticketId, 'gitea_project_id' => $selectedProject]);
$findCategoryProject = $DB->request('glpi_plugin_gitea_projects', ['category_id' => $selectedCategory]);

if ($result->count() > 0) {
    $response = ['res' => false];
    echo json_encode($response);
} else {
    if (class_exists('PluginGiteaIntegrationParameters')) {
        $DB->insert(
            'glpi_plugin_gitea_integration',
            [
                'ticket_id'         => $ticketId,
                'gitea_project_id' => $selectedProject
            ]
        );

        $title = $ticketId . ' - ' . $ticketName;
        $description = str_replace('&lt;p&gt;', '', str_replace('&lt;/p&gt;', '', $ticketContent));
        $description = str_replace('&lt;br&gt;', '<br><br><br>', $description);
        $description = str_replace('&lt;p style=\"padding-left: 40px;\"&gt;', '<p style="padding-left: 40px;">', $description);
        $description = str_replace('&lt;', '<', $description);
        $description = str_replace('&gt;', '>', $description);
        $dueDate = $ticketDueDate;
        $type = $ticketType;
        $label = $ticketLabel;
        $assignedTo = $ticketAssignedTo;

        PluginGiteaIntegrationGiteaIntegration::CreateIssue($selectedProject, $title, $description, $dueDate, $type, $label);

        PluginGiteaIntegrationEventLog::Log($ticketId, 'ticket', $_SESSION["glpi_currenttime"], 'issue', 4, sprintf(__('%2s created Issue', 'giteaintegration'), $_SESSION["glpiname"]));

        Session::addMessageAfterRedirect(__('Issue created successfully!', 'giteaintegration'));

        $response = ['res' => true];
        echo json_encode($response);
    }
}
