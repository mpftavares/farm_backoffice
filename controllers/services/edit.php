<?php

verifyAccess();

if (!isset($_GET['id'])) {
    redirect('/services/list');
}

$id = $_GET['id'];

$service = getServiceById($id);

if (isPost()) {
    ['name' => $name, 'description' => $description] = $_POST;

    $service = updateService($id, $name, $description, $starts, $ends, $image);

    redirect('/services/detail?id=' . $service->id);
}

render('services/form', [
    'service' => $service
]);