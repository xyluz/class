<?php require_once("support/validation.support.php");


//$_POST = ARRAY OF USER INPUTS

/**
 * @throws Exception
 */
function register(array $requestObject = []): void
{
    $username = $requestObject['username'];
    $password = $requestObject['password'];
    $confirm_password = $requestObject['confirm_password'];
    $firstname = $requestObject['firstname'];
    $lastname = $requestObject['lastname'];
    $phone_number = $requestObject['phone_number'];
    $dob = $requestObject['dob'];

    if( isActiveSession($username)) {
        redirect('/view/dashboard.view.php');
    }

    if(confirmPasswordFails($password, $confirm_password)){
        createSession('errors',['register_failed'=>['confirm password does not match']]);
        redirect('views/register.view.php');
    }

    $errors = validateInputs($requestObject);

    if ( !empty($errors) ) {

        createSession('errors',[$errors]);
        redirect('views/register.view.php');

    }

    if($user = createUser()){
        createSession($username,true);
        createSession('user',$user);
        redirect('/view/dashboard.view.php');
    }else{

    deleteSession($username);
    createSession('errors',['register_failed'=>'registration failed']);
    redirect('/view/register.view.php');
}


}

/**
 * TODO: consider refactoring to accept array / Object
 *
 * @param array $requestObject
 * @return bool|array
 * @throws Exception
 */
function validateInputs(array $requestObject = []): bool|array
{

    $rules = [
        'phone_number'=>'min:11|max:15|must:numeric',
        'firstname'=>'min:3|max:200|must:alpha|not:numeric',
        'lastname'=>'min:3|max:200|must:alpha|not:numeric',
        'username'=>'min:3|max:50|must:alpha',
        'password'=>'min:8|max:50|must:alpha_numeric|must:uppercase|must:lowercase|must:symbol',
        'profile_photo' => 'file:5kb|mimes:jpeg,jpg,png|max:10240',
    ];

    $hasErrors = validateCustom(rules: $rules, inputObject:  $requestObject);

    return count($hasErrors) === 0 ? true : $hasErrors;

}

function confirmPasswordFails(string $password, string $confirm_password): bool {
    return $password !== $confirm_password;
}