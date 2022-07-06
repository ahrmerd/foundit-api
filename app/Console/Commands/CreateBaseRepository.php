<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateBaseRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:base-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the base repository if it dosen\'t exist';


    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/BaseRepository.stub');
    }

    protected function getFullFilePath()
    {
        return app()->basePath() . "/app/Repositories/";
    }

    public function makeDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    protected function checkForBaseResource()
    {
        if (file_exists(app()->basePath() . "/app/Http/Resources/BaseResource.php")) return;
        $this->call('make:resource', ['name' => 'BaseResource']);
    }

    protected function getFullFileName()
    {
        return $this->getFullFilePath() . 'BaseRepository.php';
    }

    public function handle()
    {
        $this->checkForBaseResource();
        if (file_exists($this->getFullFileName())) {
            $this->info('the base repository already exist');
        } else if (file_exists($this->getStub())) {
            $this->makeDir($this->getFullFilePath());
            // dump($this->getStub(), $this->getFullFileName());
            if (copy($this->getStub(), $this->getFullFileName())) {

                $this->info('the base repository already exist');
            } else {
                $this->error('the base repository already exist');
            }

            $this->info('the base repository already exist');
        } else {
            $this->error('something is wrong the stub file does not exit in' . $this->getStub());
        }
        return 0;
    }
}
