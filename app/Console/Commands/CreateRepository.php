<?php

namespace App\Console\Commands;

use App\Http\Resources\BaseResource;
use App\Models\User;
use App\UserRepository;
use Database\Factories\UserFactory;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:repository')]
class CreateRepository extends GeneratorCommand
{

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:repository';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/repository.stub');
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command'],
        ];
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getSorts()
    {
        $this->info('Please enter the sorts allowed. input exit if you are done');
        $fields = [];
        while (true) {
            $field = $this->ask('field');
            if ($field == 'exit') {
                break;
            }
            array_push($fields, $field);
        }
        return json_encode($fields);
    }

    protected function getIncludes()
    {
        $this->info('Please enter the includes allowed. input exit if you are done');
        $fields = [];
        while (true) {
            $field = $this->ask('field');
            if ($field == 'exit') {
                break;
            }
            array_push($fields, $field);
        }
        return json_encode($fields);
    }

    protected function getFilters()
    {
        $this->info('Please enter the filters allowed. input exit if you are done');
        $fields = [];
        while (true) {
            $field = $this->ask('field');
            if ($field == 'exit') {
                break;
            }
            array_push($fields, $field);
        }
        return json_encode($fields);
        // return $fields;
    }

    protected function checkForBaseRepository()
    {
        if (file_exists(app()->basePath() . "/app/Repository/BaseRepository.php")) return;
        $this->call('make:base-repository',);
    }


    protected function buildClass($name)
    {
        // $filters = $this->getFilters();
        // $sorts = $this->getSorts();
        // $includes = $this->getIncludes();
        $this->checkForBaseRepository();
        $model = $this->ask('what is the model name ?');
        $model = ucfirst($model);
        $filters = $this->getFilters();
        $sorts = $this->getSorts();
        $includes = $this->getIncludes();
        $namespaceModel = $this->qualifyModel($model);;
        $model = class_basename($namespaceModel);



        $replace = [
            '_filters' => $filters,
            '_sorts' => $sorts,
            '_includes' => $includes,
            '_model' => $model,
            '_namespacemodel' => $namespaceModel,

        ];


        // $resource = app()->make($resource);
        // dump($resource);
        // $filters = $this->getFilters();
        // dump($filters);

        // $namespace = $this->getNamespace(
        //     Str::replaceFirst($this->rootNamespace(), 'Database\\Factories\\', $this->qualifyClass($this->getNameInput()))
        // );

        // $replace = ['' => ''];
        // $replace = [
        //     '{{ factoryNamespace }}' => $namespace,
        //     'NamespacedDummyModel' => $namespaceModel,
        //     '{{ namespacedModel }}' => $namespaceModel,
        //     '{{namespacedModel}}' => $namespaceModel,
        //     'DummyModel' => $model,
        //     '{{ model }}' => $model,
        //     '{{model}}' => $model,
        //     '{{ factory }}' => $factory,
        //     '{{factory}}' => $factory,
        // ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }
}
