<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Support\Str;

trait UsesFolderConfig
{
    /**
     * Container for the models´ folder path.
     *
     * @var string
     */
    protected string $modelsFolder;

    /**
     * Container for the models´ namespace.
     *
     * @var string
     */
    protected string $modelsNamespace;

    /**
     * Container for the contracts´ folder path.
     *
     * @var string
     */
    protected string $contractsFolder;

    /**
     * Container for the contracts´ namespace.
     *
     * @var string
     */
    protected string $contractsNamespace;

    /**
     * Container for the repositories´ folder path.
     *
     * @var string
     */
    protected string $repositoriesFolder;

    /**
     * Container for the repositories´ namespace.
     *
     * @var string
     */
    protected string $repositoriesNamespace;

    /**
     * Container for the filters´ folder path.
     *
     * @var string
     */
    protected string $filtersFolder;

    /**
     * Container for the filters´ namespace.
     *
     * @var string
     */
    protected string $filtersNamespace;

    /**
     * Container for the events´ folder path.
     *
     * @var string
     */
    protected string $eventsFolder;

    /**
     * Container for the events´ namespace.
     *
     * @var string
     */
    protected string $eventsNamespace;

    /**
     * Container for the listeners´ folder path.
     *
     * @var string
     */
    protected string $listenersFolder;

    /**
     * Container for the listeners´ namespace.
     *
     * @var string
     */
    protected $listenersNamespace;

    /**
     * Gets the folder path for the given type.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getFolder(string $type): string
    {
        if (empty($this->{$type . 'Folder'})) {
            $this->{$type . 'Folder'} = config('repository.folders.' . $type, ucfirst($type));
            $this->{$type . 'Folder'} = $this->toPathFormat($this->{$type . 'Folder'});
        }

        return app_path($this->{$type . 'Folder'});
    }

    /**
     * Gets the namespace for the given type.
     *
     * @param string $type
     * @param bool $withTrailingSlash
     *
     * @return string
     */
    protected function getNamespace(string $type, bool $withTrailingSlash = true): string
    {
        if (empty($this->{$type . 'Namespace'})) {
            $this->{$type . 'Namespace'} = config('repository.folders.' . $type, ucfirst($type));
            $this->{$type . 'Namespace'} = $this->toNamespaceFormat($this->{$type . 'Namespace'});
            $this->{$type . 'Namespace'} = 'App\\' . $this->{$type . 'Namespace'} . ($withTrailingSlash ? '\\' : '');
        }

        return $this->{$type . 'Namespace'};
    }

    /**
     * Determines if the given class exists for the given type.
     *
     * @param string $type
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function classExists(string $type, string $class): bool
    {
        return file_exists(
            $this->{'get' . $type . 'Folder'}() . '/' . $this->toPathFormat($class) . '.php'
        );
    }

    /**
     * Gets the models folder path.
     *
     * @return string
     */
    protected function getModelsFolder(): string
    {
        return $this->getFolder('models');
    }

    /**
     * Gets the contracts folder path.
     *
     * @return string
     */
    protected function getContractsFolder(): string
    {
        return $this->getFolder('contracts');
    }

    /**
     * Gets the repositories folder path.
     *
     * @return string
     */
    protected function getRepositoriesFolder(): string
    {
        return $this->getFolder('repositories');
    }

    /**
     * Gets the filters folder path.
     *
     * @return string
     */
    protected function getFiltersFolder(): string
    {
        return $this->getFolder('filters');
    }

    /**
     * Gets the events folder path.
     *
     * @return string
     */
    protected function getEventsFolder(): string
    {
        return $this->getFolder('events');
    }

    /**
     * Gets the listeners folder path.
     *
     * @return string
     */
    protected function getListenersFolder(): string
    {
        return $this->getFolder('listeners');
    }

    /**
     * Gets the models namespace.
     *
     * @param mixed $withTrailingSlash
     *
     * @return string
     */
    protected function getModelsNamespace($withTrailingSlash = true): string
    {
        return $this->getNamespace('models', $withTrailingSlash);
    }

    /**
     * Gets the contracts namespace.
     *
     * @param mixed $withTrailingSlash
     *
     * @return string
     */
    protected function getContractsNamespace($withTrailingSlash = true): string
    {
        return $this->getNamespace('contracts', $withTrailingSlash);
    }

    /**
     * Gets the repositories namespace.
     *
     * @param mixed $withTrailingSlash
     *
     * @return string
     */
    protected function getRepositoriesNamespace($withTrailingSlash = true): string
    {
        return $this->getNamespace('repositories', $withTrailingSlash);
    }

    /**
     * Gets the filters namespace.
     *
     * @param mixed $withTrailingSlash
     *
     * @return string
     */
    protected function getFiltersNamespace($withTrailingSlash = true): string
    {
        return $this->getNamespace('filters', $withTrailingSlash);
    }

    /**
     * Gets the events namespace.
     *
     * @param mixed $withTrailingSlash
     *
     * @return string
     */
    protected function getEventsNamespace($withTrailingSlash = true): string
    {
        return $this->getNamespace('events', $withTrailingSlash);
    }

    /**
     * Gets the listeners namespace.
     *
     * @param mixed $withTrailingSlash
     *
     * @return string
     */
    protected function getListenersNamespace($withTrailingSlash = true): string
    {
        return $this->getNamespace('listeners', $withTrailingSlash);
    }

    /**
     * Determines if the given model class exists.
     *
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function modelExists(string $class): bool
    {
        return $this->classExists('models', $class);
    }

    /**
     * Determines if the given contract class exists.
     *
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function contractExists(string $class): bool
    {
        return $this->classExists('contracts', $class);
    }

    /**
     * Determines if the given repository class exists.
     *
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function repositoryExists(string $class): bool
    {
        return $this->classExists('repositories', $class);
    }

    /**
     * Determines if the given event class exists.
     *
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function eventExists(string $class): bool
    {
        return $this->classExists('events', $class);
    }

    /**
     * Determines if the given listener class exists.
     *
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function listenerExists(string $class): bool
    {
        return $this->classExists('listeners', $class);
    }

    /**
     * Ensures that string contains only backslashes.
     *
     * @param string $string
     *
     * @return string
     */
    protected function toNamespaceFormat(string $string): string
    {
        return Str::replace('/', '\\', $string);
    }

    /**
     * Ensures that string contains only forward slashes.
     *
     * @param string $string
     *
     * @return string
     */
    protected function toPathFormat(string $string): string
    {
        return Str::replace('\\', '/', $string);
    }
}
