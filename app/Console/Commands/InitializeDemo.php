<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InitializeDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Инициализирует демо';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->createEnvFile();
        $this->createDatabase();

        if (!$this->checkDependencies()) {
            $this->error('Composer installation finished with error!');

            return;
        }

        $this->finalize();
        $this->serve();
    }

    /**
     * @return void
     */
    protected function createEnvFile(): void
    {
        $this->alert('Initialization');
        $envFileExists = file_exists($this->envPath());
        if (!$envFileExists) {
            $this->info('Creating .env file...');
            copy($this->envPath().'.example', $this->envPath());
        }
    }
    /**
     * @return void
     */
    protected function createDatabase(): void
    {
        $dbFileExists = file_exists(config('database.connections.sqlite.database'));
        if (!$dbFileExists) {
            $this->info('Creating database...');
            if (touch($dbPath = base_path().'\database.sqlite')) {
                $dbPath = str_replace('\\', '/', $dbPath);
                $this->putKeyToEnv('DB_CONNECTION', 'sqlite');
                $this->putKeyToEnv('DB_DATABASE', '"'.$dbPath.'"'.PHP_EOL);
                config()->set('database.default', 'sqlite');
                config()->set('database.connections.sqlite.database', $dbPath);
            }
        }
    }

    /**
     * @return bool
     */
    protected function checkDependencies(): bool
    {
        $this->alert('Installing dependencies');
        $result = 0;
        system('composer install', $result);
        if ($result !== 0) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    protected function finalize(): void
    {
        $this->alert('Finalization');
        $this->call('key:generate');
        $this->call('migrate', ['--force' => true]);
        $this->call('jwt:secret');
        $this->call('l5-swagger:generate');
    }

    /**
     * @return void
     */
    protected function serve(): void
    {
        $this->alert('Starting server');
        $this->call('serve');
    }

    /**
     * @return string
     */
    protected function envPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath().DIRECTORY_SEPARATOR.'.env_old';
        }

        return $this->laravel->basePath('.env_old');
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function putKeyToEnv(string $key, string $value): void
    {
        if (Str::contains(file_get_contents($path = $this->envPath()), $key) === false) {
            file_put_contents($path, PHP_EOL."$key=$value", FILE_APPEND);
        }
    }
}
