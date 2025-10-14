<?php

require_once("support/auth.support.php");
require_once("support/validation.support.php");
require_once("support/session.support.php");
require_once("support/helper.support.php");
require_once("models/login.model.php");


/**
 * Accept username, password, validate, hash and send to modal for authentication.
 * @param $username
 * @param $password
 * @return void
 */
function login($username, $password):void{

    if(! isValidUsername($username)){
        createSession('errors',['login'=>'invalid username']);
        redirect('/view/login.view.php');
    }

    if( isActiveSession($username)) {
        redirect('/view/dashboard.view.php');
    }

    $hashPassword = hashPassword($password);

     if( authenticated($username,$hashPassword) ){
        createSession($username,true);
        redirect('/view/dashboard.view.php');
     }else{
         createSession('errors',['login_failed'=>'username or password invalid']);
         redirect('/view/login.view.php');
     }

}

function logout(): void
{
    clearAllSession();
    redirect('/view/login.view.php');
}