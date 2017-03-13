# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
- Allow to set default credential ID for specific server providers;
- `hasPayload` method added to `Provider` class;
- Workers Management;
- `Forge::credentials` method to get stored credentials.

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

[Unreleased]: https://github.com/tzurbaev/laravel-forge-api/compare/0.9.1...HEAD
[0.9.1]: https://github.com/tzurbaev/laravel-forge-api/compare/0.9.0...0.9.1
[0.9.0]: https://github.com/tzurbaev/laravel-forge-api/releases/tag/0.9.0
