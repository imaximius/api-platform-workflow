# API Platform Workflow Bundle

It is an integration between API Platform and Symfony Workflows.
PHP 8 and Symfony 5 implementation of - https://gist.github.com/soyuka/7c75933a6ae3d64940bb1d1f0d9fa9da

## Installation 
`composer req imaximius/api-platform-workflow-bundle`

## Usage
After an installation process, when a class supports workflow it'll have additional route (assuming entity dummy):

`api_dummies_state_get_item                                    GET      ANY      ANY    /api/dummies/{id}/state.{_format}`

GET receives available states for the given resource.

Important! The bundle tries to autodetect field from workflow (workflow.{name}.marking_store.property parameter) or by default set `state` field for current state. All other staff will be done automatically (including validation of stored state).

## Enjoy