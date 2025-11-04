<?php

class Validator
{

    private const array ALLOWED_RULES = ['min', 'max', 'must', 'not']; //TODO: use Enum
    private const array ALLOWED_CONSTRAINTS = ['alpha', 'numeric', 'alpha_numeric', 'array', 'uppercase', 'lowercase', 'symbol', 'upper_lower_sym' ]; //TODO: use Enum

    //TODO: Make class for input Object
    //TODO: make class for rules (in addition to Enum)
    //TODO: create class for handling errors

    private array $errors = [];
    public function __construct(public array $rules, public array $inputObject)
    {
        //TODO: implement data cleanup
    }

    /**
     * @throws Exception
     */
    public function validateCustom(): array {

        foreach ($this->rules as $column => $rule) {
            if (!isset($this->inputObject[$column])) {
                continue;
            }

            $isError = $this->applySingleInputValidation($rule, $this->inputObject[$column]);

            $this->errors[$column] = count($isError) > 0 ? $isError : null;

        }

        return array_filter($this->errors);
    }


    /**
     * @return string
     */
    private function getImplodeConstraints(): string
    {
        return implode(', ', self::ALLOWED_CONSTRAINTS);
    }

    private function getImplodeRules(): string
    {
        return implode(', ', self::ALLOWED_RULES);
    }

    private function makeArrayFromString(string $separator, string $string):array{
        return explode($separator, $string);
    }
    /**
     * @throws Exception
     */
    private function applySingleInputValidation(mixed $rule, mixed $input): array
    {
        $splitRules = $this->makeArrayFromString('|',$rule); //TODO: convert to method (makeArrayFromString)
        $singleInputError = [];

        foreach ($splitRules as $rule) {
            $single = explode(':', $rule);
            $law = $single[0];
            $value = $single[1];

            if (! in_array($law, ALLOWED_RULES)) {
                throw new Exception($rule . 'is invalid' . ' Only allowed rules are: ' . $this->getImplodeRules());
            }

            $check = match($law) {
                'min' => $this->applyMinCheck($input, $value) ? true : "{$input} must be at least {$value} characters",
                'max' => $this->applyMaxCheck($input, $value) ? true : "{$input} must not exceed  {$value} characters",
                'must' => $this->applyMustConstraint($input, $value) ? true : "{$input} must be of type {$value}",
                'not' => $this->applyNotConstraint($input, $value) ? true : "{$input} must not be of type {$value}",
                default => throw new Exception("{$rule} is invalid rule"),
            };
            if (! $check ) {
                $singleInputError["$law:$value"] = $check;
            }
        }
        return $singleInputError;

    }

    private function applyMaxCheck(mixed $input, string $value): bool
    {
        return is_int($input) ?  $input <= $value : strlen($input) <= $value;
    }

    private function applyMinCheck(mixed $input, string $value): bool
    {
        return is_int($input) ?  $input >= $value : strlen($input) >= $value;
    }

    private function applyMustConstraint(mixed $input, string $constraint): bool
    {
        if (! in_array($constraint, ALLOWED_CONSTRAINTS)) {
            throw new Exception("{$constraint} is invalid constraint type. Only allowed constraints are: ". $this->getImplodeConstraints());
        }
        return match($constraint) {
            'alpha' => ctype_alpha($input),
            'numeric' => ctype_digit($input),
            'alpha_numeric' => ctype_alnum($input),
            'array' => is_array($input),
            'upper_lower_sym' => checkPasswordStrength($input),
            'lowercase' => ctype_lower($input),
            'symbol' => ctype_space($input),
            default => false,
        };
    }

    private function applyNotConstraint(mixed $input, string $constraint): bool
    {
        if (! in_array($constraint, ALLOWED_CONSTRAINTS)) {
            throw new Exception("{$constraint} is invalid constraint type. Only allowed constraints are: ". $this->getImplodeConstraints());
        }

        return match($constraint) {
            'alpha' => !ctype_alpha($input),
            'numeric' => !ctype_digit($input),
            'alpha_numeric' => !ctype_alnum($input),
            'array' => !is_array($input),
            'uppercase' => !ctype_upper($input),
            'lowercase' => !ctype_lower($input),
            'symbol' => !ctype_space($input),
            default => true,
        };
    }

}