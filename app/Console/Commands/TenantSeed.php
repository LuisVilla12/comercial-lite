<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use Database\Seeders\TenantDatabaseSeeder;

class TenantSeed extends Command
{
    protected $signature = 'tenant:seed {empresa}';

    protected $description = 'Ejecuta los seeders de una empresa tenant';

    public function handle()
    {
        $empresa = Empresa::findOrFail($this->argument('empresa'));

        Config::set('database.connections.tenant.database', $empresa->db_database);

        DB::purge('tenant');
        DB::reconnect('tenant');

        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => TenantDatabaseSeeder::class,
            '--force' => true,
        ]);

        $this->info("Seeder ejecutado para {$empresa->db_database}");
    }
}
