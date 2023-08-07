<?php

verifyAccess();


if (!isset($_GET['id'])) {
    redirect('/sales/list');
}

$id = $_GET['id'];

$sale = getSaleById($id);

render('sales/detail', [
    'sale' => $sale
]);