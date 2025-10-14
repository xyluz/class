<?php require_once 'helper.support.php';

const ALLOWED_RULES = ['min', 'max', 'must', 'not'];
const ALLOWED_CONSTRAINT = ['alpha','numeric','alpha_numeric','array','uppercase','lowercase','symbol'];

function isValidUsername($username):bool {
    return true;
}

/**
 * @throws Exception
 */
function validateCustom(array $rules = [],  array $inputObject = []): array
{

    if(empty($rules) || empty($inputObject)){
        throw new Exception('rules and input object are required');
    }

    $errors = [];

   foreach ($rules as $column => $rule) {

        if(!isset($inputObject[$column])){
            continue;
        }

        $iSError = applySingleInputValidation($rule,$inputObject[$column]);

        if (count($iSError) > 0){
            $errors[$column]  = $iSError;
        }
   }

    return $errors;
}


/**
 * @throws Exception
 */
function applySingleInputValidation(string $rules, $input):array{

    $splitRules = explode('|',$rules);
    $singleError = [];

    foreach ($splitRules as $rule) {

        $single = explode(':',$rule);
        $law = $single[0];
        $value = $single[1];

        if(! in_array($law, ALLOWED_RULES)) {
            throw new Exception('Only allowed rules are: '.implode(', ',ALLOWED_RULES));
        }

        $check = match ($law) {
            'min' =>  applyMinCheck($input, $value) ? true : "$input must be minimum $value",
            'max' =>  applyMaxCheck($input, $value) ? true : "$input must be maximum $value" ,
            'must' => applyMustConstraint($input, $value) ? true : "$input must be of type $value" ,
            'not' =>  applyNotConstraint($input, $value) ? true : "$input must be of type $value" ,
            default => throw new Exception('Invalid rule: ' . $rule),
        };

        if(is_string($check) || ! $check ){
            $singleError["$law:$value"] = $check;
        }

    }

    return $singleError;
}

/**
 * @param mixed $input
 * @param string $value
 * @return bool
 */
function applyMaxCheck(mixed $input, string $value): bool
{
    return is_int($input) ? $input <= $value : strlen($input) <= $value;
}

/**
 * @param mixed $input
 * @param string $value
 * @return bool
 */
function applyMinCheck(mixed $input, string $value): bool
{
    return is_int($input) ? $input >= $value : strlen($input) >= $value;
}

function applyMustConstraint(int|string $input, string $constraint): bool
{
    if(! in_array($constraint, ALLOWED_CONSTRAINT)) {
        throw new Exception('Only allowed constrained are: '.implode(', ',ALLOWED_CONSTRAINT));
    }

    return match ($constraint) {
        'alpha' => ctype_alpha($input),
        'numeric' => is_numeric($input),
        'alpha_numeric' => ctype_alnum($input),
        'array' => is_array($input),
        'lowercase' => ctype_lower($input),
        'uppercase' => ctype_upper($input),
        'symbol' => ctype_space($input),
        default => false,
    };
}

function applyNotConstraint(int|string $input, string $constraint): bool
{
    if(! in_array($constraint, ALLOWED_CONSTRAINT)) {
        throw new Exception('Only allowed constrained are: '.implode(', ',ALLOWED_CONSTRAINT));
    }

    return match ($constraint) {
        'alpha' => !ctype_alpha($input),
        'numeric' => !is_numeric($input),
        'alpha_numeric' => !ctype_alnum($input),
        'array' => !is_array($input),
        'lowercase' => !ctype_lower($input),
        'uppercase' => !ctype_upper($input),
        'symbol' => !ctype_space($input),
        default => true,
    };
}



