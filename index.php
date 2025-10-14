<?php require_once 'support/validation.support.php';


$rules = [
    'phone_number'=>'min:11|max:15|must:numeric',
    'firstname'=>'min:3|max:4|must:alpha|not:numeric',
    'lastname'=>'min:3|max:4|must:alpha|not:numeric',
    'username'=>'min:3|max:50|must:alpha',
];

$requestObject = [
    'username' => 'Seyi',
    'firstname' => 'John6',
    'lastname' => 'Doe43',
    'phone_number' => '+2547',
];

try {
    $validator = validateCustom(rules: $rules, inputObject: $requestObject);
    print_r($validator);
} catch (Exception $e) {
    print($e->getMessage());
}

