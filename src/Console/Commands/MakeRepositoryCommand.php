<?php

namespace Maarsson\Repository\Console\Commands;

use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
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
        $this->makeRepositoryInterface();
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
        $template = str_replace(
            ['{{modelName}}'],
            [$this->modelName],
            $this->getStub($stub)
        );

        file_put_contents(
            app_path('/' . $target . '/' . $this->modelName . $stub . '.php'),
            $template
        );
    }

    /**
     * Creates a repository interface.
     */
    protected function makeRepositoryInterface()
    {
        $this->makeClass('RepositoryInterface', 'Interfaces');
    }

    /**
     * Creates a repository.
     */
    protected function makeRepository()
    {
        $this->makeClass('Repository', 'Repositories');
    }

    /**
     * Creates events classes.
     */
    protected function makeEvents()
    {
        $this->makeClass('IsCreatingEvent', 'Events');
        $this->makeClass('WasCreatedEvent', 'Events');
    }

    /**
     * Creates listeners classes.
     */
    protected function makeListeners()
    {
        $this->makeClass('IsCreatingListener', 'Listeners');
        $this->makeClass('WasCreatedListener', 'Listeners');
    }
}
