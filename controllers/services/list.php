<?php

verifyAccess();

$search = isset($_GET['search']) ? $_GET['search'] : null;

$services = getAllServices($search);

$search = '';

render('services/list', [
    'services' => $services
]);
