<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Model;

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
            $this->{$type . 'Folder'} = str_replace('\\', '/', $this->{$type . 'Folder'});
        }

        return $this->{$type . 'Folder'};
    }

    protected function getNamespace($type)
    {
        if (empty($this->{$type . 'Namespace'})) {
            $this->{$type . 'Namespace'} = config('repository.folders.' . $type, ucfirst($type));
            $this->{$type . 'Namespace'} = str_replace('/', '\\', $this->{$type . 'Namespace'});
            $this->{$type . 'Namespace'} = 'App\\' . $this->{$type . 'Namespace'} .'\\';
        }

        return $this->{$type . 'Namespace'};
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

    protected function getModelsNamespace()
    {
        return $this->getNamespace('models');
    }

    protected function getContractsNamespace()
    {
        return $this->getNamespace('contracts');
    }

    protected function getRepositoriesNamespace()
    {
        return $this->getNamespace('repositories');
    }

    protected function getEventsNamespace()
    {
        return $this->getNamespace('events');
    }

    protected function getListenersNamespace()
    {
        return $this->getNamespace('listeners');
    }
}
