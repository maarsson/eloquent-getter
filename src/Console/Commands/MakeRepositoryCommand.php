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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->modelName = ucfirst($this->argument('model'));
        $this->makeRepositoryContract();
        $this->makeRepository();
        $this->makeEvents();
        $this->makeListeners();
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
        return file_get_contents(
            __DIR__ . '/../../../resources/stubs/' . $type . '.stub'
        );
    }

    /**
     * Creates a class.
     *
     * @param string $stub The stub name
     * @param string $target The target folder
     */
    protected function makeClass(string $stub, string $target)
    {
        preg_match(
            '/(.*)\/(.*)/',
            $this->toPathFormat($this->modelName),
            $modelClassParts
        );

        $modelBaseName = empty($modelClassParts) ? $this->modelName : $modelClassParts[2];
        $namespaceSuffix = empty($modelClassParts) ? null : '\\' . $this->toNamespaceFormat($modelClassParts[1]);
        $pathSuffix = empty($modelClassParts) ? null : '/' . $modelClassParts[1];

        $file = $target . $pathSuffix . '/' . $modelBaseName . $stub . '.php';

        if (File::exists($file)) {
            $this->error('Class `' . $file . '` already exists, skipped.');

            return false;
        }

        $this->createFolder($target . $pathSuffix);

        $template = Str::replace(
            [
                '{{modelName}}',
                '{{modelsNamespace}}',
                '{{contractsNamespace}}',
                '{{repositoriesNamespace}}',
                '{{eventsNamespace}}',
                '{{listenersNamespace}}',
            ],
            [
                $modelBaseName,
                $this->getModelsNamespace(false) . $namespaceSuffix,
                $this->getContractsNamespace(false) . $namespaceSuffix,
                $this->getRepositoriesNamespace(false) . $namespaceSuffix,
                $this->getEventsNamespace(false) . $namespaceSuffix,
                $this->getListenersNamespace(false) . $namespaceSuffix,
            ],
            $this->getStub($stub)
        );

        File::put(
            $file,
            $template
        );

        $this->info('Class `' . $file . '` was created.');
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
