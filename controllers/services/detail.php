<?php

verifyAccess();


if (!isset($_GET['id'])) {
    redirect('/services/list');
}

$id = $_GET['id'];

$service = getServiceById($id);

render('services/detail', [
    'service' => $service
]);
