<?php session_start();

function createSession($key, $value):void{
    $_SESSION[$key] = $value;
}

function getSession($key):?string{
    return $_SESSION[$key] ?? null;
}

function deleteSession($key):void{
    unset($_SESSION[$key]);
}

function clearAllSession(): void
{
    session_destroy();
}