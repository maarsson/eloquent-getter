<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UsesFolderConfig
{
    protected $modelsFolder;
    protected $modelsNamespace;
    protected $contractsFolder;
    protected $contractsNamespace;
    protected $repositoriesFolder;
    protected $repositoriesNamespace;
    protected $eventsFolder;
    protected $eventsNamespace;
    protected $listenersFolder;
    protected $listenersNamespace;

    protected function getFolder($type)
    {
        if (empty($this->{$type . 'Folder'})) {
            $this->{$type . 'Folder'} = config('repository.folders.' . $type, ucfirst($type));
            $this->{$type . 'Folder'} = $this->toPathFormat($this->{$type . 'Folder'});
        }

        return app_path($this->{$type . 'Folder'});
    }

    protected function getNamespace($type, $withTrailingSlash = true)
    {
        if (empty($this->{$type . 'Namespace'})) {
            $this->{$type . 'Namespace'} = config('repository.folders.' . $type, ucfirst($type));
            $this->{$type . 'Namespace'} = $this->toNamespaceFormat($this->{$type . 'Namespace'});
            $this->{$type . 'Namespace'} = 'App\\' . $this->{$type . 'Namespace'} . ($withTrailingSlash ? '\\' : '');
        }

        return $this->{$type . 'Namespace'};
    }

    protected function classExists($type, $class)
    {
        return file_exists(
            $this->{'get' . $type . 'Folder'}() . '/' . $this->toPathFormat($class) . '.php'
        );
    }

    protected function getModelsFolder()
    {
        return $this->getFolder('models');
    }

    protected function getContractsFolder()
    {
        return $this->getFolder('contracts');
    }

    protected function getRepositoriesFolder()
    {
        return $this->getFolder('repositories');
    }

    protected function getEventsFolder()
    {
        return $this->getFolder('events');
    }

    protected function getListenersFolder()
    {
        return $this->getFolder('listeners');
    }

    protected function getModelsNamespace($withTrailingSlash = true)
    {
        return $this->getNamespace('models', $withTrailingSlash);
    }

    protected function getContractsNamespace($withTrailingSlash = true)
    {
        return $this->getNamespace('contracts', $withTrailingSlash);
    }

    protected function getRepositoriesNamespace($withTrailingSlash = true)
    {
        return $this->getNamespace('repositories', $withTrailingSlash);
    }

    protected function getEventsNamespace($withTrailingSlash = true)
    {
        return $this->getNamespace('events', $withTrailingSlash);
    }

    protected function getListenersNamespace($withTrailingSlash = true)
    {
        return $this->getNamespace('listeners', $withTrailingSlash);
    }

    protected function modelExists($class)
    {
        return $this->classExists('models', $class);
    }

    protected function contractExists($class)
    {
        return $this->classExists('contracts', $class);
    }

    protected function repositoryExists($class)
    {
        return $this->classExists('repositories', $class);
    }

    protected function eventExists($class)
    {
        return $this->classExists('events', $class);
    }

    protected function listenerExists($class)
    {
        return $this->classExists('listeners', $class);
    }

    protected function toNamespaceFormat($string)
    {
        return Str::replace('/', '\\', $string);
    }

    protected function toPathFormat($string)
    {
        return Str::replace('\\', '/', $string);
    }
}
