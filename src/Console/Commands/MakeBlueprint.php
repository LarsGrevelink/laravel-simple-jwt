<?php

namespace LGrevelink\LaravelSimpleJWT\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use LGrevelink\LaravelSimpleJWT\Helpers\PropertyHelper;
use LGrevelink\SimpleJWT\TokenBlueprint;
use ReflectionClass;

class MakeBlueprint extends GeneratorCommand
{
    private const DEFAULT_NAMESPACE = '\\JwtTokens';
    private const PROPERTY_TEMPLATE = <<<TEMPLATE
    /**
     * @inheritdoc
     */
    protected static %s = %s;
TEMPLATE;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:jwt-blueprint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new JWT blueprint class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'TokenBlueprint';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return realpath(__DIR__ . '/Stubs/make-blueprint.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . self::DEFAULT_NAMESPACE;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $classContents = [];

        $properties = (new ReflectionClass(TokenBlueprint::class))->getProperties();

        foreach ($properties as $property) {
            [$propertyName, $description, $type] = PropertyHelper::getContext($property);

            // Display the description of the claim
            if ($description) {
                $this->info($description);
            }

            $value = $this->ask(
                sprintf('Value for the %s claim?', Str::snake($property->getName(), ' '))
            );

            if ($value !== null) {
                if ($type === 'int') {
                    $value = (int) $value;
                } elseif ($type === 'bool') {
                    $value = (bool) $value;
                } elseif ($type === 'float') {
                    $value = (float) $value;
                }

                // Store the property for adding to the stub
                $classContents[] = sprintf(self::PROPERTY_TEMPLATE, $propertyName, var_export($value, true));
            }
        }

        return str_replace(
            'protected static $dummyProperty;',
            trim(implode(PHP_EOL . PHP_EOL, $classContents)),
            parent::buildClass($name)
        );
    }
}
