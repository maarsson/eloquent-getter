<?php

namespace Maarsson\Repository\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Maarsson\Repository\Traits\UsesFolderConfigTrait;
use Maarsson\Repository\Traits\UsesStubFunctionsTrait;

class MakeGetterCommand extends Command
{
    use UsesFolderConfigTrait, UsesStubFunctionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:getter
        {model : The model class name eg.: \'YourModel\' or \'Foo\\Bar\'}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold model getter';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setModelName()
            ->makeGetter()
            ->warnIfModelNotExists()
            ->warnIfModelNotConfigured();
    }

    /**
     * Creates a repository interface.
     *
     * @return self
     */
    protected function makeGetter(): self
    {
        $this->makeClass('Getter', $this->getGettersFolder());

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
                '{{gettersNamespace}}',
            ],
            [
                $this->modelBaseName,
                $this->getGettersNamespace(false) . $this->modelNamespaceSuffix,
            ],
            $this->getStub($stub)
        );
    }
}
