<?php

namespace Tests\Validation\Rule;

use LGrevelink\LaravelSimpleJWT\Exceptions\Validation\InvalidBlueprintException;
use LGrevelink\LaravelSimpleJWT\Validation\Rule\ValidJwt;
use Tests\Mocks\Blueprints\EmptyBlueprintMock;
use Tests\Mocks\Blueprints\FullBlueprintMock;
use Tests\TestCase;
use Tests\TestUtil;

class ValidJwtTest extends TestCase
{
    public function testConstructorWithoutBlueprint()
    {
        $this->assertTrue((new ValidJwt()) instanceof ValidJwt);
        $this->assertTrue((new ValidJwt(null)) instanceof ValidJwt);
    }

    public function testConstructorWithNonExistingClass()
    {
        $this->expectException(InvalidBlueprintException::class);
        $this->expectExceptionMessage('Blueprint class could not be found');

        new ValidJwt('SomeNonExistingClass');
    }

    public function testConstructorWithNonBlueprintClass()
    {
        $this->expectException(InvalidBlueprintException::class);
        $this->expectExceptionMessage('Blueprint class is not a TokenBlueprint class');

        new ValidJwt(self::class);
    }

    public function testConstructorWithBlueprintClass()
    {
        $validJwtRule = new ValidJwt(EmptyBlueprintMock::class);

        $this->assertTrue($validJwtRule instanceof ValidJwt);
        $this->assertSame(TestUtil::getProperty($validJwtRule, 'blueprint'), EmptyBlueprintMock::class);
    }

    public function testPassesNullValue()
    {
        $validJwtRule = new ValidJwt();

        $this->assertFalse($validJwtRule->passes(null, null));
    }

    public function testPassesJwtValue()
    {
        $validJwtRule = new ValidJwt();

        $this->assertTrue($validJwtRule->passes(null, EmptyBlueprintMock::generate()->toString()));
    }

    public function testPassesJwtValueWithBlueprint()
    {
        $validJwtRule = new ValidJwt(EmptyBlueprintMock::class);

        $this->assertTrue($validJwtRule->passes(null, EmptyBlueprintMock::generate()->toString()));
    }

    public function testPassesJwtValueWithInvalidBlueprint()
    {
        $validJwtRule = new ValidJwt(FullBlueprintMock::class);

        $this->assertFalse($validJwtRule->passes(null, EmptyBlueprintMock::generate()->toString()));
    }

    public function testPassesJwtValueWithCorrectBlueprint()
    {
        $validJwtRule = new ValidJwt(FullBlueprintMock::class);

        $this->assertTrue($validJwtRule->passes(null, FullBlueprintMock::generate()->toString()));
    }

    public function testPassesInvalidJwtValue()
    {
        $this->assertFalse((new ValidJwt())->passes(null, 'definitely-not-a-jwt'));
    }

    public function testMessage()
    {
        $this->assertSame((new ValidJwt())->message(), 'The :attribute must be a valid JSON Web Token.');
    }
}
