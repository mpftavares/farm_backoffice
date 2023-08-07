<?php

verifyAccess();

if (isPost()) {
    ['name' => $name, 'description' => $description, 'starts' => $starts, 'ends' => $ends] = $_POST;

    ['image' => $image] = $_FILES;

    $sale = createSale($name, $description, $starts, $ends, $image);

    redirect('/sales/detail?id=' . $sale->id);
}

render('sales/form');