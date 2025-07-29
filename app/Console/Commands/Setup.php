<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->runProcess(['npm', 'install'], "[STEUP]: node and vite composer");

        if (!file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
            $this->info('[STEUP]: .env generated');
        } else {
            $this->warn('[STEUP]: .env failed to generate');
        }

        $this->call('key:generate');
        $this->info('[STEUP]: generated a key');
        $this->call('migrate', ['--force' => true]);
        $this->info('[STEUP]: migrated database');
        $this->call('db:seed', ['--force' => true]);
        $this->info('[STEUP]: seeded the database');

        $this->runProcess(['php', 'artisan', 'serve'], "[STEUP]: Running Laravel");
        $this->runProcess(['npm', 'run', 'dev'], "[STEUP]: Running TailwindCSS via NPM");
    }

    private function runProcess(array $command, string $message)
    {
        $this->info("{$message}");
        $process = new \Symfony\Component\Process\Process($command, base_path());
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error("{$message} failed!");
            exit(1);
        }
    }
}
