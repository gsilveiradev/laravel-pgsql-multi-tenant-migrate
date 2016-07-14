<?php

namespace Guissilveira\Laravel\Commands;

use Illuminate\Console\Command;
use Pacuna\Schemas\Facades\PGSchema;
use DB;

class TenantMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command used to make laravel migration for all schemas of the pgSQL database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting migration:');
        $this->line('');

        // Retrieve tenants from db
        $tenants = DB::select('SELECT n.nspname AS "name", pg_catalog.pg_get_userbyid(n.nspowner) AS "owner", pg_catalog.array_to_string(n.nspacl, E\'\n\') AS "access_privileges", pg_catalog.obj_description(n.oid, \'pg_namespace\') AS "description" FROM pg_catalog.pg_namespace n WHERE n.nspname !~ \'^pg_\' AND n.nspname <> \'information_schema\' ORDER BY 1');

        $this->line(' -> '.count($tenants).' schemas to migrate.');

        $bar = $this->output->createProgressBar(count($tenants));

        // Migrate all tenants schemas
        foreach ($tenants as $tenant) {

            PGSchema::migrate($tenant->name, ['--path' => 'database/migrations']);

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->line('');

        $this->info('Migration finished!');
    }
}
