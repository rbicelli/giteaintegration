<div align="center">
    <img src="img/glpi_logo.png" width="300px"/>
</div>


# Gitea Integration

Gitea Integration is a plugin to use into GLPI - Gestionnaire Libre de Parc Informatique when the tickets needs to integrate with Gitea.

## Installation

Clone this repository inside the folder plugins of GLPI

Configure parameters to use Gitea Integration:

- open the file `giteaintegration.ini`
- Change the variables `GITEA_URL` and `GITEA_TOKEN` values to.
  - `GITEA_URL`: receive the url to access gitea repository
  - `GITEA_TOKEN`: receive the token to access gitea repository

Afterwards install the plugin and enabled it.

## Giving permissions to users profiles

To give permission, it is necessary to access the option `Permissions Gitea` located at ``Administration`.

Then it's possible to adds any available profile.

## Set a Default Gitea project

To set a default gitea project, it is nessecary to access the option `Gitea Default Project` located at `Administration`.

Then select a project from the available projects combobox.

## Associate a Gitea project to a GLPI category

In `Administation` select the option `Gitea Projects Association` , then you can select a Gitea project and a GLPI category to associate them.

## Creating an Issue

To create an Issue, a ticket must be created and then open it.

on the end of the ticket form a field called `Gitea Project` is added. In this field you must select a project from Gitea available projects and then click on `Create Issue`.

After this, the Issue is created on the Gitea website successfully!
