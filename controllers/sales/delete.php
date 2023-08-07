<?php

verifyAccess();


if (!isset($_GET['id'])) {
    redirect('/sales/list');
}

$id = $_GET['id'];

$sale = getSaleById($id);

removeSale($id);

redirect('/sales/list');