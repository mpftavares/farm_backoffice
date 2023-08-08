<?php

verifyAccess();


if (!isset($_GET['id'])) {
    redirect('/services/list');
}

$id = $_GET['id'];

$sale = getServiceById($id);

removeService($id);

redirect('/services/list');
