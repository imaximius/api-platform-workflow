# API Platform Workflow Bundle

It is an integration between API Platform and Symfony Workflows

## Installation 
`composer req imaximius/api-platform-workflow-bundle`

## Usage
After an installation process, when a class supports workflow it'll have additional route (assuming entity dummy):

`api_dummies_state_get_item                                    GET      ANY      ANY    /api/dummies/{id}/state.{_format}`

GET receives available states for the given resource.

Important! Entity should contain `state` field for current state. All other staff will be done automatically (including validation of stored state).

## Enjoy