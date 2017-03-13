<?php

namespace Laravel\Forge\Laravel\Commands;

use Laravel\Forge\Forge;
use Illuminate\Console\Command;

class ForgeCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forge:credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display Forge credentials';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Forge $forge)
    {
        $credentials = $forge->credentials();

        $headers = ['ID', 'Name', 'Provider'];
        $rows = collect($credentials)->map(function ($credential) {
            return [$credential['id'], $credential['name'], $credential['type']];
        });

        $this->table($headers, $rows->toArray());
    }
}
