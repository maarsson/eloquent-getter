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
     * Gets the folder path for the given type.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getFolder(string $type): string
    {
        if (empty($this->{$type . 'Folder'})) {
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
     * Gets the getters folder path.
     *
     * @return string
     */
    protected function getGettersFolder(): string
    {
        return $this->getFolder('getters');
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
