<?php

define('DATA_SALES_PATH', '../data/sales/');

function createSale(string $name, string $description, string $startDate, string $endDate, array $imageFile): stdClass
{

    $id = uniqid();
    $image = uploadImage($imageFile, $id);

    $sale = new stdClass();

    $sale->id = $id;
    $sale->name = $name;
    $sale->description = $description;
    $sale->startDate = $startDate;
    $sale->endDate = $endDate;
    $sale->image = $image;

    $filePath = DATA_SALES_PATH . $id . '.json';
    $json = json_encode($sale);
    file_put_contents($filePath, $json);

    return $sale;
}

function uploadImage(array $file, string $id): ?string
{
    $filename = "images/sales/$id.png";

    if (move_uploaded_file($file['tmp_name'], $filename)) {
        return $filename;
    }
    return null;
}

function getAllSales(string $filter = null): array
{
    $files = glob(DATA_SALES_PATH . '*.json');

    // $sales = [];

    // foreach ($files as $file) {
    //     $json = file_get_contents(($file));
    //     $sale = json_decode($json);
    //     $sales[] = $sale;
    // }

    $sales = array_map(function ($file) {
        $json = file_get_contents($file);
        return json_decode($json);
    }, $files);

    if (!is_null($filter)) {
        $filter = strtolower($filter);
        $sales = array_filter($sales, fn ($sales) => str_contains(strtolower($sales->name), $filter));
    }

    return $sales;
}

function getSaleById(string $id): ?stdClass
{
    $sales = getAllSales();

    foreach ($sales as $sale) {
        if ($sale->id === $id) {
            return $sale;
        }
    }

    return null;
}

function updateSale(string $id, string $name, string $description, string $startDate, string $endDate, array $imageFile): stdClass
{
    $sale = getSaleById($id);

    $sale->name = $name;
    $sale->description = $description;
    $sale->startDate = $startDate;
    $sale->endDate = $endDate;

    if ($imageFile) {
        $image = uploadImage($imageFile, $id);
    }

    $sale->image = isset($image) ? $image : $sale->image;

    $filePath = DATA_SALES_PATH . $id . '.json';
    $json = json_encode($sale);
    file_put_contents($filePath, $json);

    return $sale;
}

function removeSale(string $id): bool {
    $saleFile = DATA_SALES_PATH . $id . '.json';
    $sale = getSaleById($id);

    if ($sale->image) {
        unlink($sale->image);
    }

    if (!file_exists($saleFile)) {
            return false;
    }

    return unlink($saleFile);
};