<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeSqlite extends Command
{
    protected $signature = 'db:optimize';
    protected $description = 'Optimize the SQLite database';

    public function handle()
    {
        $this->info("Optimizing SQLite database...");
        
        try {
            $pdo = DB::connection()->getPdo();
            
            // Start timer
            $start = microtime(true);
            
            // Run VACUUM to rebuild the database file
            $this->info("Running VACUUM...");
            $pdo->exec('VACUUM');
            
            // Run ANALYZE to update statistics
            $this->info("Running ANALYZE...");
            $pdo->exec('ANALYZE');
            
            // Set some pragmas for better performance
            $this->info("Setting performance pragmas...");
            $pdo->exec('PRAGMA journal_mode = WAL');
            $pdo->exec('PRAGMA synchronous = NORMAL');
            $pdo->exec('PRAGMA cache_size = 10000');
            $pdo->exec('PRAGMA temp_store = MEMORY');
            $pdo->exec('PRAGMA mmap_size = 30000000000');
            
            // Report current settings
            $this->info("\nCurrent SQLite settings:");
            $settings = [
                'journal_mode',
                'synchronous',
                'cache_size',
                'temp_store',
                'mmap_size'
            ];
            
            foreach ($settings as $setting) {
                $value = $pdo->query("PRAGMA $setting")->fetchColumn();
                $this->info("  - $setting: $value");
            }
            
            $time = microtime(true) - $start;
            $this->info("\nOptimization completed in " . round($time, 2) . " seconds.");
            
        } catch (\Exception $e) {
            $this->error("Optimization failed: " . $e->getMessage());
        }
    }
}
