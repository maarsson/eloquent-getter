<?php

namespace Maarsson\Repository\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Maarsson\Repository\Traits\UsesFolderConfig;
use Maarsson\Repository\Traits\UsesStubFunctions;

class MakeFilterCommand extends Command
{
    use UsesFolderConfig, UsesStubFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter
        {model : The model class name eg.: \'YourModel\' or \'Foo\\Bar\'}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold model filter';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setModelName()
            ->makeFilter()
            ->warnIfModelNotExists()
            ->warnIfModelNotConfigured();
    }

    /**
     * Creates a repository interface.
     *
     * @return self
     */
    protected function makeFilter(): self
    {
        $this->makeClass('Filter', $this->getFiltersFolder());

        return $this;
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
                '{{filtersNamespace}}',
            ],
            [
                $this->modelBaseName,
                $this->getFiltersNamespace(false) . $this->modelNamespaceSuffix,
            ],
            $this->getStub($stub)
        );
    }
}
