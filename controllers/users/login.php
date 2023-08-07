<?php

$messages = [];

if (isPost()) {
    [
        'username' => $username,
        'password' => $password
    ] = $_POST;

    if (attemptLogin($username, $password)) {
        redirect("/dashboard");
    }

    $messages[] = [
        'type' => 'danger',
        'message' => 'Bad credentials'
    ];
}

if (isset($_GET['message'])) {
    $messages[] = [
        'type' => 'info',
        'message' => $_GET['message']
    ];
}

render(
    'users/login',
    [
        'messages' => $messages
    ],
    false
);
