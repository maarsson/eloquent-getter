<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Support\Str;

trait UsesFolderConfigTrait
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
     * Container for the interfaces´ folder path.
     *
     * @var string
     */
    protected string $interfacesFolder;

    /**
     * Container for the interfaces´ namespace.
     *
     * @var string
     */
    protected string $interfacesNamespace;

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
     * Container for the getters´ folder path.
     *
     * @var string
     */
    protected string $gettersFolder;

    /**
     * Container for the filters´ namespace.
     *
     * @var string
     */
    protected string $gettersNamespace;

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
     *
     * @return string
     */
    protected function getNamespace(string $type): string
    {
        return $this->getNamespaceWithoutTrailingSlash($type) . '\\';
    }

    /**
     * Gets the namespace for the given type
     * without trailing slash.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getNamespaceWithoutTrailingSlash(string $type): string
    {
        if (empty($this->{$type . 'Namespace'})) {
            $this->{$type . 'Namespace'} = config('repository.folders.' . $type, ucfirst($type));
            $this->{$type . 'Namespace'} = $this->toNamespaceFormat($this->{$type . 'Namespace'});
            $this->{$type . 'Namespace'} = 'App\\' . $this->{$type . 'Namespace'};
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
     * Gets the interfaces folder path.
     *
     * @return string
     */
    protected function getInterfacesFolder(): string
    {
        return $this->getFolder('interfaces');
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
     * Gets the getters folder path.
     *
     * @return string
     */
    protected function getGettersFolder(): string
    {
        return $this->getFolder('getters');
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
     * @return string
     */
    protected function getModelsNamespace(): string
    {
        return $this->getNamespace('models');
    }

    /**
     * Gets the models namespace
     * without trailing slash.
     *
     * @return string
     */
    protected function getModelsNamespaceWithoutTrailingSlash(): string
    {
        return $this->getNamespaceWithoutTrailingSlash('models');
    }

    /**
     * Gets the interfaces namespace.
     *
     * @return string
     */
    protected function getInterfacesNamespace(): string
    {
        return $this->getNamespace('interfaces');
    }

    /**
     * Gets the interfaces namespace
     * without trailing slash.
     *
     * @return string
     */
    protected function getInterfacesNamespaceWithoutTrailingSlash(): string
    {
        return $this->getNamespaceWithoutTrailingSlash('interfaces');
    }

    /**
     * Gets the repositories namespace.
     *
     * @return string
     */
    protected function getRepositoriesNamespace(): string
    {
        return $this->getNamespace('repositories');
    }

    /**
     * Gets the repositories namespace
     * without trailing slash.
     *
     * @return string
     */
    protected function getRepositoriesNamespaceWithoutTrailingSlash(): string
    {
        return $this->getNamespaceWithoutTrailingSlash('repositories');
    }

    /**
     * Gets the getters namespace.
     *
     * @return string
     */
    protected function getGettersNamespace(): string
    {
        return $this->getNamespace('getters');
    }

    /**
     * Gets the getters namespace
     * without trailing slash.
     *
     * @return string
     */
    protected function getGettersNamespaceWithoutTrailingSlash(): string
    {
        return $this->getNamespaceWithoutTrailingSlash('getters');
    }

    /**
     * Gets the events namespace.
     *
     * @return string
     */
    protected function getEventsNamespace(): string
    {
        return $this->getNamespace('events');
    }

    /**
     * Gets the events namespace
     * without trailing slash.
     *
     * @return string
     */
    protected function getEventsNamespaceWithoutTrailingSlash(): string
    {
        return $this->getNamespaceWithoutTrailingSlash('events');
    }

    /**
     * Gets the listeners namespace.
     *
     * @return string
     */
    protected function getListenersNamespace(): string
    {
        return $this->getNamespace('listeners');
    }

    /**
     * Gets the listeners namespace
     * without trailing slash.
     *
     * @return string
     */
    protected function getListenersNamespaceWithoutTrailingSlash(): string
    {
        return $this->getNamespaceWithoutTrailingSlash('listeners');
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
     * Determines if the given interface class exists.
     *
     * @param string $class
     *
     * @return bool true if class exists, False otherwise
     */
    protected function interfaceExists(string $class): bool
    {
        return $this->classExists('interfaces', $class);
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
