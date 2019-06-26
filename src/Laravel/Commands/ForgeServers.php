<?php

namespace Laravel\Forge\Laravel\Commands;

use Laravel\Forge\Forge;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;
use Laravel\Forge\Servers\Providers\Provider;

class ForgeServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forge:servers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Forge Servers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Forge $forge)
    {
        $action = $this->choice('What do you want to do?', [
            'list' => 'List Servers',
            'create' => 'Create New Server',
            'delete' => 'Delete Server',
        ]);

        switch ($action) {
            case 'list':
                return $this->handleListAction($forge);
            case 'create':
                return $this->handleCreateAction($forge);
            case 'delete':
                return $this->handleDeleteAction($forge);
        }
    }

    /**
     * Asks user to choose a server.
     *
     * @param \Laravel\Forge\Forge $forge
     * @param string               $message = 'Choose Server'
     *
     * @return \Laravel\Forge\Server
     */
    protected function chooseServer(Forge $forge, string $message = 'Choose Server')
    {
        $choices = [];

        foreach ($forge as $server) {
            $choices[$server->name()] = $server->id();
        }

        $serverName = $this->choice($message, $choices);

        return $forge[$serverName];
    }

    /**
     * "List Servers" handler.
     *
     * @param \Laravel\Forge\Forge $forge
     *
     * @return mixed
     */
    protected function handleListAction(Forge $forge)
    {
        $headers = ['ID', 'Name', 'Size', 'Region', 'Ready?', 'PHP Version'];
        $rows = [];

        foreach ($forge as $server) {
            $rows[] = [
                $server->id(),
                $server->name(),
                $server->size(),
                $server->region(),
                $server->isReady() ? 'Yes' : 'No',
                $server->phpVersion(),
            ];
        }

        $this->info('Here are your servers:');
        $this->table($headers, $rows);
    }

    /**
     * "Create New Server" handler.
     *
     * @param \Laravel\Forge\Forge $forge
     *
     * @return mixed
     */
    protected function handleCreateAction(Forge $forge)
    {
        $provider = $this->choice('Choose provider', [
            'ocean2' => 'DigitalOcean',
            'linode' => 'Linode',
            'aws' => 'AWS',
            'custom' => 'Custom VPS',
        ]);

        $name = $this->ask('Choose name for your new server');

        switch ($provider) {
            case 'ocean2':
                return $this->createServer($forge->create()->droplet($name));
            case 'linode':
                return $this->createServer($forge->create()->linode($name));
            case 'aws':
                return $this->createServer($forge->create()->aws($name));
            case 'custom':
                return $this->createServer($forge->create()->custom($name));
        }
    }

    /**
     * Create server at specific provider.
     *
     * @param \Laravel\Forge\Servers\Providers\Provider $provider
     *
     * @return mixed
     */
    protected function createServer(Provider $provider)
    {
        if ($provider->provider() !== 'custom') {
            $size = $this->ask('Enter the server size ID');
            $provider->withSizeId($size);

            $regions = $provider->regions();

            $region = $this->choice('Choose server region', $regions);

            if ($provider->provider() === 'linode') {
                $flippedRegions = array_flip($regions);
                $region = $flippedRegions[$region];
            }

            $provider->at($region);
        }

        $phpVersion = $this->choice('Choose PHP version', $provider->phpVersions());
        $provider->runningPhp($phpVersion);

        $databaseName = 'forge';

        if ($this->confirm('Do you want to set new database name?')) {
            $databaseName = $this->ask('Choose database name');
        } else {
            $this->comment('OK, using default database name ("forge").');
        }

        $databaseType = $this->choice('Choose database type', [
            'mysql' => 'MySQL 5.7',
            'mysql8' => 'MySQL 8.0',
            'mariadb' => 'MariaDB',
            'postgres' => 'PostgreSQL',
        ]);

        switch ($databaseType) {
            case 'mysql':
                $provider->withMysql($databaseName);
                $this->comment('OK, MySQL 5.7 server will be installed.');
                break;
            case 'mysql8':
                $provider->withMysql($databaseName, 8);
                $this->comment('OK, MySQL 8.0 server will be installed.');
                break;
            case 'mariadb':
                $provider->withMariaDb($databaseName);
                $this->comment('OK, MariaDb server will be installed');
                break;
            case 'postgres':
                $provider->withPostgres($databaseName);
                $this->comment('OK, PostgreSQL server will be installed.');
                break;
        }

        if ($this->confirm('Do you want to provision this server as node balancer?', false)) {
            $provider->asNodeBalancer();
            $this->comment('OK, server will be provisioned as load balancer.');
        }

        if ($provider->provider() === 'custom') {
            $publicIp = $this->ask('Please, provide public IP address for this VPS');
            $privateIp = $this->ask('Please, provide private IP address for this VPS');

            $provider->usingPublicIp($publicIp)->usingPrivateIp($privateIp);
        }

        $hasCredentials = $provider->hasPayload('credential_id');
        $credentialMessage = 'Seems that you\'re using predefined credential. Do you want to change credential for this server?';
        $updateCredential = $hasCredentials === false || $this->confirm($credentialMessage, false);

        if ($updateCredential) {
            $credentialId = $this->ask('Enter credential ID');
            $provider->usingCredential($credentialId);
        } else {
            $this->comment('OK, default credential will be used.');
        }

        try {
            $server = $provider->save();
        } catch (RequestException $e) {
            $response = $e->getResponse();

            $this->error('Request ended with error.');
            $this->error('HTTP Status Code: '.$response->getStatusCode());

            return $this->error((string) $response->getBody());
        }

        $this->info('Great! Your new server "'.$server->name().'" was created!');
        $this->info('Please allow up to 10-15 minutes to finish server provision.');
    }

    /**
     * "Delete Server" handler.
     *
     * @param \Laravel\Forge\Forge $forge
     *
     * @return mixed
     */
    protected function handleDeleteAction(Forge $forge)
    {
        $server = $this->chooseServer($forge);

        $this->error('THIS IS DESTRUCTIVE OPERATION! YOUR SERVER WILL BE DELETED AND THIS ACTION IS IRREVERSIBLE!');
        $this->error('You\'re going to delete '.Str::upper($server->name()).' server.');

        if (!$this->confirm('Are you totally sure you want to delete this server?', false)) {
            return $this->info('Ok, your server left untoched.');
        }

        $this->error('We require one more confirmation that you\'re totally sure about deleting '.$server->name().' server.');
        $confirmation = $this->ask('Enter server name to continue');

        if ($server->name() !== $confirmation) {
            return $this->error('You\'ve entered wrong name, operation aborted.');
        }

        $this->info('Ok, server '.$server->name().' will be deleted now.');

        $server->delete();

        $this->info('Server '.$server->name().' was sucessfully deleted.');
    }
}
