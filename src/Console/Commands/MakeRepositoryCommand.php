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
    protected $signature = 'make:repository
        {model : The model class name eg.: \'YourModel\' or \'Foo\\Bar\'}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold model repository';

    /**
     * The model class given by the argument.
     *
     * @var string
     */
    protected string $modelName;

    /**
     * The model class basename.
     *
     * @var string
     */
    protected string $modelBaseName;

    /**
     * The relative namespace for the model within the \App\Models namespace.
     *
     * @var string|null
     */
    protected ?string $modelNamespaceSuffix;

    /**
     * The relative path to the model within the app/Models folder.
     *
     * @var string|null
     */
    protected ?string $modelPathSuffix;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setModelName()
            ->makeRepositoryContract()
            ->makeRepository()
            ->makeEvents()
            ->makeListeners()
            ->warnIfModelNotExists()
            ->warnIfModelNotConfigured();
    }

    /**
     * Sets the model name related properties.
     *
     * @return self
     */
    protected function setModelName(): self
    {
        if (! $this->hasArgument('model')) {
            $this->error('Argument for model name (eg. \'YourModel\' or \'Foo\\Bar\') must be specified!');
            $this->exit();
        }

        $this->modelName = $this->toNamespaceFormat($this->argument('model'));
        $this->splitModelNameToParts();

        return $this;
    }

    /**
     * Splits model class argument to namespace and basename parts.
     * Also converts namespace to path.
     */
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

    /**
     * Creates a repository interface.
     *
     * @return self
     */
    protected function makeRepositoryContract(): self
    {
        $this->makeClass('RepositoryContract', $this->getContractsFolder());

        return $this;
    }

    /**
     * Creates a repository.
     *
     * @return self
     */
    protected function makeRepository(): self
    {
        $this->makeClass('Repository', $this->getRepositoriesFolder());

        return $this;
    }

    /**
     * Creates events classes.
     *
     * @return self
     */
    protected function makeEvents(): self
    {
        $this->makeClass('IsCreatingEvent', $this->getEventsFolder());
        $this->makeClass('IsUpdatingEvent', $this->getEventsFolder());
        $this->makeClass('WasCreatedEvent', $this->getEventsFolder());
        $this->makeClass('WasUpdatedEvent', $this->getEventsFolder());

        return $this;
    }

    /**
     * Creates listeners classes.
     *
     * @return self
     */
    protected function makeListeners(): self
    {
        $this->makeClass('IsCreatingListener', $this->getListenersFolder());
        $this->makeClass('IsUpdatingListener', $this->getListenersFolder());
        $this->makeClass('WasCreatedListener', $this->getListenersFolder());
        $this->makeClass('WasUpdatedListener', $this->getListenersFolder());

        return $this;
    }

    /**
     * Sends output warning message if the model file not exists.
     *
     * @return self
     */
    protected function warnIfModelNotExists(): self
    {
        if (! $this->modelExists($this->modelName)) {
            $this->error('NOTICE: Model class `' . $this->getModelsNamespace() . $this->modelName . '` does not exists yet! Don´t forget to create it.');
        }

        return $this;
    }

    /**
     * Sends output warning message if the model class is not added to the config file.
     *
     * @return self
     */
    protected function warnIfModelNotConfigured(): self
    {
        if (! collect(config('repository.models'))->contains($this->modelName)) {
            $this->error('NOTICE: Do not forget to add model class `' . $this->modelName . '` to the `models` array in the config/repository.php file.');
        }

        return $this;
    }

    /**
     * Creates a class.
     *
     * @param string $stub The stub name
     * @param string $target The target folder
     */
    protected function makeClass(string $stub, string $target): void
    {
        $file = $target . $this->modelPathSuffix . '/' . $this->modelBaseName . $stub . '.php';

        if ($this->fileAlreadyExists($file)) {
            return;
        }

        $this->createFolder($target . $this->modelPathSuffix);
        $this->createFileFromStub($stub, $file);
    }

    /**
     * Determines if the file already exists.
     * Also sends output warning message, if exists.
     *
     * @param string $file
     *
     * @return bool true if file already exists, False otherwise
     */
    protected function fileAlreadyExists(string $file): bool
    {
        if (! File::exists($file)) {
            return false;
        }

        $this->error('Class `' . $file . '` already exists, skipped.');

        return true;
    }

    /**
     * Creates folder if not exists.
     *
     * @param string $path
     */
    protected function createFolder(string $path): void
    {
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }

    /**
     * Creates a class file from stub template.
     *
     * @param string $stub
     * @param string $file
     */
    protected function createFileFromStub(string $stub, string $file): void
    {
        File::put(
            $file,
            $this->getConvertedStubContent($stub)
        );

        $this->info('Class `' . $file . '` was created.');
    }

    /**
     * Gets the converted stub template content.
     *
     * @param string $stub
     *
     * @return string
     */
    protected function getConvertedStubContent(string $stub): string
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
}
