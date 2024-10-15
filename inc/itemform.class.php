<?php

/*
 -------------------------------------------------------------------------
GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2003-2019 by the INDEPNET Development Team.

http://indepnet.net/   http://glpi-project.org
-------------------------------------------------------------------------

LICENSE

This file is part of GLPI.

GLPI is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

/**
 * Summary of PluginGiteaIntegrationItemForm
 * */
class PluginGiteaIntegrationItemForm
{

   /**
    * Display contents at the begining of item forms.
    *
    * @param array $params Array with "item" and "options" keys
    *
    * @return void
    */
   static public function postItemForm($params)
   {
      global $CFG_GLPI, $DB;
      $item = $params['item'];

      $canCreate = self::verifyPermission();

      if ($item::getType() == Ticket::getType() && ($item->getField('id') != 0) && ($canCreate)) {
         $options = $params['options'];

         $content = self::getTextWithoutQuotationMarks($item->getField('content'));
         $dueDate = $item->getField('time_to_resolve');
         $type = $item->getField('type') == 1 ? 'incident' : 'issue';
         $label = $type === 'incident' ? 'incident' : '';
         $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/glpi/front/ticket.form.php?id=" . $item->getField('id');
         $category_id = $item->getField('itilcategories_id');
         $projects = PluginGiteaIntegrationCategoriesProjects::projects();
         $firstElt = ($item::getType() == Ticket::getType() ? 'th' : 'td');

         $selectedProject = $category_id != 0 ? $DB->request('glpi_plugin_gitlab_projects', ['category_id' => $category_id])->next() : '';
         $associatedCategories = $DB->request('glpi_plugin_gitlab_projects', ['NOT' => ['category_id' => NULL]]);
         // GITLAB PROJECT SELECT INPUT
         $associatedCategoriesIds = [];
         foreach ($associatedCategories as $associatedCategory) {
            array_push($associatedCategoriesIds, $associatedCategory['category_id']);
         }


         echo "<tr>";
         echo "<td>";
         echo "</td>";
         echo "</tr>";

         echo "<tr><$firstElt>";
         echo '<label>' . __('Gitea Project', 'gitlabintegration') . '</label>';
         echo "</$firstElt><td>";

         echo "<select style='padding: 5px' name='project_id' id='project_id'>";
         if (!in_array($category_id, $associatedCategoriesIds)) {
            $defaultProject = $DB->request('glpi_plugin_gitlab_projects', ['general' => 1])->next();
            echo "<option value='$defaultProject[project_id]'>$defaultProject[project_name]</option>";
         };
         foreach ($projects as $project) {
            if ($project->id == $defaultProject['project_id']) continue;
            echo "<option value='$project->id'";
            if ($selectedProject['project_id'] == $project->id) echo ' selected';
            echo ">";
            echo  $project->name_with_namespace;
            echo '</option>';
         }
         echo "</select>";

         echo "</td>";

         echo "<td style='text-align: left; width: 5px'>";

         $message = __('Issue already created in the selected Project', 'gitlabintegration');

         echo "<div class='primary-button' onClick='createIssue(" . $item->getField('id') . ", \"" . $item->getField('name') . "\", \"" . $content . "\", \"" . $dueDate . "\", \"" . $type . "\", \"" . $label . "\", \"" . $link . "\" , \"" . $message . "\")'>" . __("Create Issue", "gitlabintegration") . "</div>";

         echo "</td>";
         echo '</tr>';

         echo '<tr style="padding:10px"><td style="padding:10px"></td></tr>';
      }
   }

   static private function getTextWithoutQuotationMarks($text)
   {
      $bodytag = str_replace("'", "\"", $text);
      $bodytag = str_replace("\"", "\\\"", $bodytag);
      return $bodytag;
   }

   /**
    * Display contents at the selected project of item forms.
    *
    * @param $ticketId
    *
    * @return $selectedProject 
    */
   static private function getSelectedProject($ticketId)
   {
      global $DB;

      $result = $DB->request('glpi_plugin_gitlab_integration', ['ticket_id' => [$ticketId]]);
      $selectedProject = 0;

      foreach ($result as $row) {
         $selectedProject = $row['gitlab_project_id'];
      }

      return $selectedProject;
   }

   /**
    * Display contents at the profiles have Permission of item forms.
    *
    * @param void
    *
    * @return boolean $canCreate
    */
   static private function verifyPermission()
   {
      global $DB;
      $result = $DB->request('glpi_plugin_gitlab_profiles_users', ['FIELDS' => 'profile_id']);
      // => SELECT `profile_id` FROM `glpi_plugin_gitlab_profiles_users`

      $canCreate = false;
      foreach ($result as $row) {
         if ($row['profile_id'] == $_SESSION['glpiactiveprofile']['id']) {
            $canCreate = true;
            break;
         }
      }
      return $canCreate;
   }

   /**
    * Display contents at the component of item forms.
    *
    * @param array $options
    *
    * @return Dropdown component
    */
   static function dropdownProject(array $options = [])
   {
      $p = [
         'name'     => 'project',
         'value'    => 0,
         'showtype' => 'normal',
         'display'  => true,
      ];

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $values = [];

      $result = PluginGiteaIntegrationGiteaIntegration::getProjects();

      foreach ($result as $key => $value) {
         $values[$value->id] = $value->name_with_namespace;
      }

      return Dropdown::showFromArray($p['name'], $values, $p);
   }
}
