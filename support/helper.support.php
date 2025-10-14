<?php require_once 'session.support.php';

function redirect($page): void
{
    header("Location: $page");
}

function isActiveSession($username):bool{
    return !empty( getSession($username));
}

function dd($arg): void
{
    echo "<pre>";

    print_r($arg);
    die();

    echo "</pre>";

}