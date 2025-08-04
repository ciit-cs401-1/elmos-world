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

        $this->call(command: 'key:generate');
        $this->info(string: '[STEUP]: generated a key');
        $this->call(command: 'migrate', arguments: ['--force' => true]);
        $this->info(string: '[STEUP]: migrated database');
        try {
            $this->call(command: 'db:seed', arguments: ['--force' => true]);
            $this->info('[SETUP]: seeded the database');
        } catch (\Exception $e) {
            $error_code = $e->getCode();
            $error_message = $e->getMessage();

            $this->warn("[SETUP]: db exception ( $error_code ): $error_message");

            if ($error_code == "23000") {
                $this->warn("[SETUP]: db exception is the unique constraint failed");
            }

            $this->warn("[SETUP]: skipping db seeding");
        }


        $this->info('[SETUP]: Setup complete. Run the following in separate terminals:');
        $this->info("[STEUP]: Running Laravel");
        $this->info('  php artisan serve');
        $this->info("[STEUP]: Running TailwindCSS via NPM");
        $this->info('  npm run dev');
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
