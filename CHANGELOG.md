# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [2.1.0] - 2019-10-11
### Added
- OPCache support (by [@acurrieclark](https://github.com/acurrieclark)).

## [2.0.0] - 2019-06-26
### Added
- Users service to retrieve authenticated user info;
- Allow to provision servers with PostgreSQL;
- Allow to specify recipe to be run after server provisioning;
- Add PHP 7.3 to the list of allowed PHP versions.

### Fixed
- `maria` database type changed to `mariadb` (by [@acurrieclark](https://github.com/acurrieclark));

### Changed
- The `withMemoryOf()` method of `Provider` class has been removed in favor of `withSizeId` method
(by [@acurrieclark](https://github.com/acurrieclark)). Please read [upgrade info](./docs/upgrade.md);
- Min PHP version raised to 7.2 (from 7.0).

## [1.5.2] - 2017-12-23
### Added
- Allow to create servers with PHP 7.2

## [1.5.1] - 2017-09-03

### Added
- Added support for Laravel's Package Discovery feature;
- Added `Laravel\Forge\Laravel\Facades\ForgeFacade` facade.

## [1.5.0] - 2017-08-29

Updates by [@acurrieclark](https://github.com/acurrieclark)
### Added
- `Site::directory()` method to retrieve current site's root directory;
- `Site::updateApplication(ApplicationContract $app)` method to install new application on site;
- `CreateSiteCommand::withDirectory(string $directory)` method to specify site's root directory while creating new site (defaults to `/public`);
- `GitApplication::usingBranch(string $branch)` method to specify what GIT branch should be used while installing new GIT application.

## [1.4.1] - 2017-06-16
### Fixed
- Valid responses with empty lists should not cause `InvalidArgumentException` anymore.

## [1.4.0] - 2017-06-14

Updates by [@acurrieclark](https://github.com/acurrieclark)
### Added
- Added an optional request rate limiting feature;
- Added new Site property access methods (`deploymentStatus`, `wildcards`, `quickDeploy`, `hipchatRoom`, `slackChannel`, `app`, `appStatus`, `repository`, `repositoryProvider`, `repositoryBranch`, `repositoryStatus`);

### Fixed
- Missing changes for [1.3.1] release.

## [1.3.1] - 2017-05-27
### Changed
- `Provider::asLoadBalancer` is marked as deprecated in favour of `Provider::asNodeBalancer`.

## [1.3.0] - 2017-05-25

Updates by [@acurrieclark](https://github.com/acurrieclark)
### Added
- Added the option to specify a firewall rule IP address by using `$rule->usingIp('127.0.0.1')` method;
- Added endpoint for configuring a load balancer's site network by using `$site->balance([$server->id])` method.
## Fixed
- Fixed LetsEncrypt certificates endpoint URL.

## [1.2.1] - 2017-05-19
### Fixed
- Creating server as node balancer should be fixed now (thanks [@acurrieclark](https://github.com/acurrieclark)).

## [1.2.0] - 2017-05-17
### Added
- Optional boolean `$reload` parameter added to `Forge::get` method signature. If true, Forge will reload fresh server data from API instead of in-memory cache;
- `Server::sudoPassword` and `Server::databasePassword` methods to retrieve sudo and database passwords respectively (both methods returns actual values only after server creation!);

### Fixed
- `Recipe::run` method correctly accepts `$serverIds` array parameter with required server IDs;

### Changed
- All API requests now uses Guzzle's `json` field instead of `form_params`.

Big thanks to [@acurrieclark](https://github.com/acurrieclark) for helping with this release.

## [1.1.1] - 2017-03-31
Small fixes & updates.

## [1.1.0] - 2017-03-15
### Added
- Configuration (nginx and ENV) files management;
- Recipes management;
- SSL Certificates management.

## [1.0.1] - 2017-03-14
### Added
- `Forge::credentialFor` method to retrieve first credential ID for given provider.

## [1.0.0] - 2017-03-14
### Added
- Allow to set default credential ID for specific server providers;
- `hasPayload` method added to `Provider` class;
- Workers Management;
- `Forge::credentials` method to get stored credentials;
- Laravel Integration: service provider and sample console commands.

### Changed
- `ForgeServers` class was renamed to `Forge`;
- `ServersFactory` class was renamed to `Factory`;
- All SDK entities are now extended from base `Laravel\Forge\ApiResource` class;
- Resource commands system was reworked and now any Resource can execute commands (previously only Server had this possibility);
- `enable`, `disable`, `reset`, `log` and `deploy` methods of `DeploymentManager` class have new signatures;
- `DeploymentManager::getScript` method was renamed to `DeploymentManager::script`;
- All `WorkerManager` commands should be used with `on($site)`/`from($site)` methods instead of `for($site)` as last method in chains;
- FQCN of `Laravel\Forge\Sites\DeploymentManager` class was changed to `Laravel\Forge\Deployment\DeploymentManager`;
- FQCN of `Laravel\Forge\Sites\Applications\GitApplication` class was changed to `Laravel\Forge\Applications\GitApplication`;
- FQCN of `Laravel\Forge\Sites\Applications\WordPressApplication` was changed to `Laravel\Forge\Applications\WordPressApplication`;
- FQCN of `Laravel\Forge\Sites\Worker` was changed to `Laravel\Forge\Workers\Worker`;
- FQCN of `Laravel\Forge\Sites\WorkersManager` was changed to `Laravel\Forge\Workers\WorkersManager`.

### Fixed
- Fixed `ArrayAccessTrait`'s `offsetExists` method.

## [0.9.1] - 2017-03-13
### Added
- Package documentation;
- `start` and `runningAs` methods were added to `CreateDaemonCommand` class;
- `identifiedAs` and `usingPort` methods were added to `CreateFirewallRuleCommand` class.

### Changed
- `DaemonsManager::create` method now accepts new daemon name instead of full daemon payload. `CreateDaemonCommand::runningAs` method allows you to set daemon user;
- `FirewallManager::create` method now accepts rule name instead of full firewall rule payload. `CreateFirewallRuleCommand::usingPort` method allows you to set rule port number;
- `CreateJobCommand::runAs` method was renamed to `runningAs`, method signature remains the same.

## [0.9.0] - 2017-03-12
Initial release.

### Added
- Servers Management;
- Servers Factory;
- Services Management;
- Deamons;
- Firewall Rules;
- Scheduled Jobs;
- MySQL Databases Management;
- MySQL Users Management;
- Sites Management;
- Site Applications;
- Deployment Management.

[Unreleased]: https://github.com/tzurbaev/laravel-forge-api/compare/2.1.0...HEAD
[2.1.0]: https://github.com/tzurbaev/laravel-forge-api/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/tzurbaev/laravel-forge-api/compare/1.5.2...2.0.0
[1.5.2]: https://github.com/tzurbaev/laravel-forge-api/compare/1.5.1...1.5.2
[1.5.1]: https://github.com/tzurbaev/laravel-forge-api/compare/1.5.0...1.5.1
[1.5.0]: https://github.com/tzurbaev/laravel-forge-api/compare/1.4.1...1.5.0
[1.4.1]: https://github.com/tzurbaev/laravel-forge-api/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/tzurbaev/laravel-forge-api/compare/1.3.1...1.4.0
[1.3.1]: https://github.com/tzurbaev/laravel-forge-api/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/tzurbaev/laravel-forge-api/compare/1.2.1...1.3.0
[1.2.1]: https://github.com/tzurbaev/laravel-forge-api/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/tzurbaev/laravel-forge-api/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/tzurbaev/laravel-forge-api/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/tzurbaev/laravel-forge-api/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/tzurbaev/laravel-forge-api/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/tzurbaev/laravel-forge-api/compare/0.9.1...1.0.0
[0.9.1]: https://github.com/tzurbaev/laravel-forge-api/compare/0.9.0...0.9.1
[0.9.0]: https://github.com/tzurbaev/laravel-forge-api/releases/tag/0.9.0
