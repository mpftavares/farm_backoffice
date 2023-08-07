<?php

define('DATA_USERS_PATH', '../data/users/'); 
// defines a named constant

function getAllUsers(): array
{
    $files = glob(DATA_USERS_PATH . '*.json'); // glob finds pathnames matching a pattern
    $users = [];

    foreach ($files as $file) {
        $json = file_get_contents(($file));
        // $users[] = (array)json_decode($json);
        $users[] = json_decode($json);
    }

    return $users;
}

function getUserByUsername(string $username): ?stdClass
{
    $users = getAllUsers();

    foreach ($users as $user) {
        if ($user->username === $username) {
            return $user;
        }
    }

    return null;
}

function attemptLogin(string $username, string $password): bool
{
    $user = getUserByUsername($username);

    if (!is_null($user) && password_verify($password, $user->password)) {
        $_SESSION['user'] = $user;

        logAccess($username, 'logged in');

        return true;
    }
    return false;
}

function verifyAccess(): void {
    if (!isset($_SESSION['user'])) {
        redirect("/login?status=403");
    }
}

function doLogout(): void {
    $user = $_SESSION['user'];

    logAccess($user->name, 'logged out');

    unset($_SESSION['user']);
}

function logAccess(string $username, $message): void {
    $log = sprintf("[%s] user %s %s from %s\n", date('Y-m-d H:i:s'), $username, $message, $_SERVER['REMOTE_ADDR']);
    file_put_contents('../logs/access.log', $log, FILE_APPEND);
}

function createUser(string $name, string $username, string $password): void {
    $id = uniqid();
   
    $user = new stdClass();

    $user->id = $id;
    $user->name = $name;
    $user->username = $username;
    $user->password = password_hash($password, PASSWORD_BCRYPT);

    $filePath = DATA_USERS_PATH . $id . '.json';
    $json = json_encode($user);
    file_put_contents($filePath, $json);
};