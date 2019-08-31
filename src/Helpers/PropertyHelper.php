<?php

namespace LGrevelink\LaravelSimpleJWT\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionProperty;

class PropertyHelper
{
    /**
     * Retrieves the context about a property.
     *
     * @param ReflectionProperty $property
     *
     * @return array
     */
    public static function getContext(ReflectionProperty $property)
    {
        $docComment = $property->getDocComment() ?: '';

        $trimmed = trim(preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ \t]{0,1}(.*)?#u', '$1', $docComment));
        $cleaned = str_replace(PHP_EOL, ' ', $trimmed);

        $name = '$' . $property->getName();
        $description = trim(Str::substr($cleaned, 0, mb_strpos($cleaned, '@') ?: mb_strlen($cleaned)));

        preg_match('/\@var ([a-z]+)/', $trimmed, $matches);

        return [$name, $description, Arr::get($matches, 1, 'mixed')];
    }
}
