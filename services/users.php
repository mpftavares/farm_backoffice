<?php

function getUserByUsername(string $username): ?stdClass
{
    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $data = [
        'username' => $username
    ];
    $stmt = raw($sql, $data);

    return $stmt->fetch();
}

function attemptLogin(string $username, string $password): bool
{
    $user = getUserByUsername($username);

    if (!is_null($user) && password_verify($password, $user->password)) {
        $_SESSION['user'] = $user;

        logAccess('logged in');

        return true;
    }
    return false;
}

function verifyAccess(): void
{
    if (!isset($_SESSION['user'])) {
        redirect("/login?status=403");
    }
}

function doLogout(): void
{
    logAccess('logged out');

    unset($_SESSION['user']);
}

function logAccess($message): void
{
    $user = $_SESSION['user'];

    $log = sprintf("[%s] %s %s from %s\n", date('Y-m-d H:i:s'), $user->name, $message, $_SERVER['REMOTE_ADDR']);
    file_put_contents('../logs/access.log', $log, FILE_APPEND);
}

function createUser(string $name, string $username, string $password): void
{
    $sql = 'INSERT INTO users (name, username, password) VALUES (:name, :username, :password)';
    $data = [
        'name' => $name,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ];

    raw($sql, $data);
};
