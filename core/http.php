<?php

function redirect(string $url): void
{
    header("Location: $url");
    die;
}

function render(string $name, array $data = null, bool $layout = true): void
{
    $path = "../views/$name.phtml";

    if (!file_exists($path)) {
        header('HTTP/1.1 500 Internal Server Error');
        die('View not found');
    }

    if (!is_null($data)) {
        extract($data); // extract imports variables into the current symbol table from an array      
    }

    if ($layout) {
        include "../views/common/header.phtml";
        include $path;
        include "../views/common/footer.phtml";
    } else {
        include $path;
    }
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}
