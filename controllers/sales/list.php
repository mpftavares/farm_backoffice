<?php

verifyAccess();

$search = isset($_GET['search']) ? $_GET['search'] : null;

$sales = getAllSales($search);

render('sales/list', [
    'sales' => $sales
]);
