<?php

namespace Maarsson\EloquentGetter\Traits;

use Illuminate\Support\Facades\File;

trait UsesStubFunctionsTrait
{
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
     * @var null|string
     */
    protected ?string $modelNamespaceSuffix;

    /**
     * The relative path to the model within the app/Models folder.
     *
     * @var null|string
     */
    protected ?string $modelPathSuffix;

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
     * Gets the stub skeleton.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getStub(string $type): string
    {
        return File::get(
            __DIR__ . '/../../resources/stubs/' . $type . '.stub'
        );
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
}
