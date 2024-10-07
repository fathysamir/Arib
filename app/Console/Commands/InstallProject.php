<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallProject extends Command
{
    protected $signature = 'install-project {name}';
    protected $description = 'Install a project by running migrations and seeders';

    public function handle()
    {
        $this->info('Installing MY Cash project...');

        // Run migrations
        Artisan::call('migrate', ['--force' => true]);

        // Seed the database
        Artisan::call('db:seed', ['--force' => true]);
        Artisan::call('serve', ['--port' => 8000]);
        // Artisan::call('db:seed', ['--class' => 'PermissionTableSeeder', '--force' => true]);
        // Artisan::call('db:seed', ['--class' => 'RoleSeeder', '--force' => true]);

        // Artisan::call('db:seed', ['--class' => 'CreateAdminSeeder', '--force' => true]);


        $this->info('Project installation completed successfully.');
    }
}
