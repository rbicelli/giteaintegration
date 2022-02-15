<div align="center">
    <img src="https://raw.githubusercontent.com/faizaleticia/gitlabintegration/master/img/glpi_logo.png" width="300px"/>
</div>


# Gitlab Integration

Gitlab Integration is a plugin to use into GLPI - Gestionnaire Libre de Parc Informatique when the tickets needs to integrate with Gitlab.

## Installation

Clone this repository inside the folder plugins of GLPI

Configure parameters to use Gitlab Integration:

- open the file `gitlabintegration.ini`
- Change the variables `GITLAB_URL` and `GITLAB_TOKEN` values to.
  - `GITLAB_URL`: receive the url to access gitlab repository
  - `GITLAB_TOKEN`: receive the token to access gitlab repository

Afterwards install the plugin and enabled it.

## Giving permissions to users profiles

To give permission, it is necessary to access the option `Permissions Gitlab` located at ``Administration`.

Then it's possible to adds any available profile.

## Set a Default gitlab project

To set a default gitlab project, it is nessecary to access the option `Gitlab Default Project` located at `Administration`.

Then select a project from the available projects combobox.

## Associate a Gitlab project to a GLPI category

In `Administation` select the option `Gitlab Projects Association` , then you can select a Gitlab project and a GLPI category to associate them.

## Creating an Issue

To create an Issue, a ticket must be created and then open it.

on the end of the ticket form a field called `Gitlab Project` is added. In this field you must select a project from Gitlab available projects and then click on `Create Issue`.

After this, the Issue is created on the Gitlab website successfully!
