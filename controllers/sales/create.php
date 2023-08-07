<?php

verifyAccess();

if (isPost()) {
    ['name' => $name, 'description' => $description, 'startDate' => $startDate, 'endDate' => $endDate] = $_POST;

    ['image' => $image] = $_FILES;

    $sale = createSale($name, $description, $startDate, $endDate, $image);

    redirect('/sales/detail?id=' . $sale->id);
}

render('sales/form');