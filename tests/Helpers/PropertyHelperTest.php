<?php

namespace Tests\Helpers;

use LGrevelink\LaravelSimpleJWT\Helpers\PropertyHelper;
use ReflectionProperty;
use Tests\TestCase;

class PropertyHelperTest extends TestCase
{
    protected $propertyWithoutDoc = 'without docs';

    /**
     * This is a property with a description but lacks a var doc.
     */
    protected $propertyWithoutVarDoc = 'without var doc';

    /**
     * This is a property with a description and a var doc.
     *
     * @var string
     */
    protected $propertyWithDoc = 'without docs';

    /**
     * This is a property with a description which is separated over two lines
     * to make sure we also fetch this.
     *
     * @var string
     */
    protected $propertyWithMultilineDoc = 'without docs';

    public function testGetContextWithoutDoc()
    {
        $property = new ReflectionProperty($this, 'propertyWithoutDoc');

        $result = PropertyHelper::getContext($property);

        $this->assertSame('$propertyWithoutDoc', $result[0]);
        $this->assertSame('', $result[1]);
        $this->assertSame('mixed', $result[2]);
    }

    public function testGetContextWithoutVarDoc()
    {
        $property = new ReflectionProperty($this, 'propertyWithoutVarDoc');

        $result = PropertyHelper::getContext($property);

        $this->assertSame('$propertyWithoutVarDoc', $result[0]);
        $this->assertSame('This is a property with a description but lacks a var doc.', $result[1]);
        $this->assertSame('mixed', $result[2]);
    }

    public function testGetContextWithDoc()
    {
        $property = new ReflectionProperty($this, 'propertyWithDoc');

        $result = PropertyHelper::getContext($property);

        $this->assertSame('$propertyWithDoc', $result[0]);
        $this->assertSame('This is a property with a description and a var doc.', $result[1]);
        $this->assertSame('string', $result[2]);
    }

    public function testGetContextWithMutlilineDoc()
    {
        $property = new ReflectionProperty($this, 'propertyWithMultilineDoc');

        $result = PropertyHelper::getContext($property);

        $this->assertSame('$propertyWithMultilineDoc', $result[0]);
        $this->assertSame('This is a property with a description which is separated over two lines to make sure we also fetch this.', $result[1]);
        $this->assertSame('string', $result[2]);
    }
}
