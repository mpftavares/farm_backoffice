<?php

if (isPost()) {
    [
        'name' => $name,
        'username' => $username,
        'password' => $password
    ] = $_POST;

    $user = createUser($name, $username, $password);

    redirect('/login?message=Success');
}

render(
    'users/form',
    [],
    false
);
