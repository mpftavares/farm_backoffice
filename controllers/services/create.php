<?php

verifyAccess();

if (isPost()) {
    ['name' => $name, 'description' => $description] = $_POST;

    ['image' => $image] = $_FILES;

    $service = createService($name, $description, $image);

    redirect('/services/detail?id=' . $service->id);
}

render('services/form');
