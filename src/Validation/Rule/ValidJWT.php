<?php

namespace LGrevelink\LaravelSimpleJWT\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use LGrevelink\SimpleJWT\Exceptions\InvalidFormatException;
use LGrevelink\SimpleJWT\Token;
use LGrevelink\SimpleJWT\TokenBlueprint;

class ValidJWT implements Rule
{
    /**
     * Class name of the blueprint we want to validate against.
     *
     * @var string|null
     */
    protected $blueprint;

    /**
     * Constructor.
     *
     * @param ?string $blueprint Token blueprint to validate against (optional)
     */
    public function __construct(?string $blueprint = null)
    {
        $this->blueprint = $blueprint;
    }

    /**
     * @inheritdoc
     */
    public function passes($attribute, $value)
    {
        if ($value === null) {
            return false;
        }

        try {
            $token = Token::parse($value);

            if ($this->blueprint === null) {
                return true;
            }

            if (!class_exists($this->blueprint)) {
                return false;
            }

            if (!($this->blueprint instanceof TokenBlueprint)) {
                return false;
            }

            return $this->blueprint::validate($token);
        } catch (InvalidFormatException $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function message()
    {
        return 'The :attribute must be a valid JSON Web Token.';
    }
}
