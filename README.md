# sulu-schemaorg-event-bundle

[![Mastodon Follow](https://img.shields.io/mastodon/follow/109408681246972700?domain=https://rheinneckar.social)](https://rheinneckar.social/@bitexpert)
[![Mastodon Follow](https://img.shields.io/mastodon/follow/109408728315328967?domain=https://rheinneckar.social)](https://rheinneckar.social/@unKonf)

This is a Sulu bundle to manage Schema.org Event metadata for your Sulu webspaces.

## Installation

```bash
composer require unkonf/sulu-schemaorg-event-bundle
```

1. Register the bundle in the file `config/bundles.php`
```php
UnKonf\Sulu\SchemaOrgEventBundle\UnKonfSuluSchemaOrgEventBundle::class => ['all' => true],
```

2. Configure the routing as follows:

Create file `config/routes/schemaorgevent_admin.yaml`:
```yaml
schemaorgevent_api:
  resource: "@UnKonfSuluSchemaOrgEventBundle/Resources/config/routing_api.yaml"
  type: rest
  prefix:   /admin/api
```

3. Run Doctrine Schema Update
```bash
./bin/adminconsole doctrine:schema:update -f
```

## Usage

Once installed, this bundle adds a tab called "Schema.org Event" to the webspaces configuration which allows you to create
new schema.org Event entries for the different webspaces. For each webspace only one event configuration can be saved.

The tab is only visible once the user got the permissions assigned. Create a new role via "Settings > User role" first, 
and assign it to the users that should be able to manage the settings.

## Contribute

Please feel free to fork and extend existing or add new features and send a pull request with your changes! To establish
a consistent code quality, please provide unit tests for all your changes and adapt the documentation.

## Want To Contribute?

If you feel that you have something to share, then we’d love to have you.
Check out [the contributing guide](CONTRIBUTING.md) to find out how, as well as what we expect from you.

## License

Sulu Schema.org Event Bundle is released under the MIT License.
