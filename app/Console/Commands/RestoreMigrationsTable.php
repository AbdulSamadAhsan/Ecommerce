<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class RestoreMigrationsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore the migrations table from all migration files.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! Schema::hasTable('migrations')) {
            Schema::create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });

            $this->info('Migrations table created.');
        } else {
            DB::table('migrations')->truncate();
            $this->info('Existing migrations table cleared.');
        }

        $files = collect(File::files(database_path('migrations')))
            ->sortBy(fn ($file) => $file->getFilename())
            ->values();

        $batch = 1;

        foreach ($files as $file) {
            DB::table('migrations')->insert([
                'migration' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
                'batch' => $batch,
            ]);
        }

        $this->newLine();
        $this->info('✔ Migrations table restored successfully.');
        $this->line('Total migration files : ' . $files->count());
        $this->line('Inserted migrations   : ' . $files->count());

        return self::SUCCESS;
    }
}