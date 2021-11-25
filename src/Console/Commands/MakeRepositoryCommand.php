<?php

namespace Maarsson\Repository\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maarsson\Repository\Traits\UsesFolderConfig;

class MakeRepositoryCommand extends Command
{
    use UsesFolderConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold model repository';

    /**
     * @var string
     */
    protected $modelName;

    protected $modelBaseName;
    protected $modelNamespaceSuffix;
    protected $modelPathSuffix;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setModelName();
        $this->makeRepositoryContract();
        $this->makeRepository();
        $this->makeEvents();
        $this->makeListeners();
        $this->warnIfModelNotExists();
        $this->warnIfModelNotConfigured();
    }

    protected function setModelName()
    {
        if (! $this->hasArgument('model')) {
            $this->error('Argument for model name (eg. `Foo\Bar`) must be specified!');
            $this->exit();
        }

        $this->modelName = $this->toNamespaceFormat($this->argument('model'));
        $this->splitModelNameToParts();
    }

    protected function warnIfModelNotExists()
    {
        if (! $this->modelExists($this->modelName)) {
            $this->error('NOTICE: Model class `' . $this->getModelsNamespace() . $this->modelName . '` does not exists yet! Don´t forget to create it.');
        }
    }

    protected function warnIfModelNotConfigured()
    {
        if (! collect(config('repository.models'))->contains($this->modelName)) {
            $this->error('NOTICE: Do not forget to add model class `' . $this->modelName . '` to the `models` array in the config/repository.php file.');
        }
    }

    /**
     * Gets the stub skeleton.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getStub(string $type): string
    {
        return File::get(
            __DIR__ . '/../../../resources/stubs/' . $type . '.stub'
        );
    }

    protected function splitModelNameToParts()
    {
        preg_match(
            '/(.*)\/(.*)/',
            $this->toPathFormat($this->modelName),
            $modelClassParts
        );
        $this->modelBaseName = empty($modelClassParts) ? $this->modelName : $modelClassParts[2];
        $this->modelNamespaceSuffix = empty($modelClassParts) ? null : '\\' . $this->toNamespaceFormat($modelClassParts[1]);
        $this->modelPathSuffix = empty($modelClassParts) ? null : '/' . $modelClassParts[1];
    }

    protected function fileAlreadyExists($file)
    {
        if (! File::exists($file)) {
            return false;
        }

        $this->error('Class `' . $file . '` already exists, skipped.');

        return true;
    }

    protected function getConvertedStubContent($stub)
    {
        return Str::replace(
            [
                '{{modelName}}',
                '{{modelsNamespace}}',
                '{{contractsNamespace}}',
                '{{repositoriesNamespace}}',
                '{{eventsNamespace}}',
                '{{listenersNamespace}}',
            ],
            [
                $this->modelBaseName,
                $this->getModelsNamespace(false) . $this->modelNamespaceSuffix,
                $this->getContractsNamespace(false) . $this->modelNamespaceSuffix,
                $this->getRepositoriesNamespace(false) . $this->modelNamespaceSuffix,
                $this->getEventsNamespace(false) . $this->modelNamespaceSuffix,
                $this->getListenersNamespace(false) . $this->modelNamespaceSuffix,
            ],
            $this->getStub($stub)
        );
    }

    protected function createFileFromStub($stub, $file)
    {
        File::put(
            $file,
            $this->getConvertedStubContent($stub)
        );

        $this->info('Class `' . $file . '` was created.');
    }

    /**
     * Creates a class.
     *
     * @param string $stub The stub name
     * @param string $target The target folder
     */
    protected function makeClass(string $stub, string $target)
    {
        $file = $target . $this->modelPathSuffix . '/' . $this->modelBaseName . $stub . '.php';

        if ($this->fileAlreadyExists($file)) {
            return false;
        }

        $this->createFolder($target . $this->modelPathSuffix);
        $this->createFileFromStub($stub, $file);
    }

    /**
     * Creates folder if not exists.
     */
    protected function createFolder(string $folder)
    {
        if (! File::isDirectory($folder)) {
            File::makeDirectory($folder, 0777, true, true);
        }
    }

    /**
     * Creates a repository interface.
     */
    protected function makeRepositoryContract()
    {
        $this->makeClass('RepositoryContract', $this->getContractsFolder());
    }

    /**
     * Creates a repository.
     */
    protected function makeRepository()
    {
        $this->makeClass('Repository', $this->getRepositoriesFolder());
    }

    /**
     * Creates events classes.
     */
    protected function makeEvents()
    {
        $this->makeClass('IsCreatingEvent', $this->getEventsFolder());
        $this->makeClass('WasCreatedEvent', $this->getEventsFolder());
    }

    /**
     * Creates listeners classes.
     */
    protected function makeListeners()
    {
        $this->makeClass('IsCreatingListener', $this->getListenersFolder());
        $this->makeClass('WasCreatedListener', $this->getListenersFolder());
    }
}
