<?php

verifyAccess();

if (!isset($_GET['id'])) {
    redirect('/sales/list');
}

$id = $_GET['id'];

$sale = getSaleById($id);

if (isPost()) {
    ['name' => $name, 'description' => $description, 'starts' => $starts, 'ends' => $ends] = $_POST;

    ['image' => $image] = $_FILES;

    $sale = updateSale($id, $name, $description, $starts, $ends, $image);

    redirect('/sales/detail?id=' . $sale->id);
}

render('sales/form', [
    'sale' => $sale
]);