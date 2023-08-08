<?php

function createSale(string $name, string $description, string $starts, string $ends, array $imageFile): stdClass
{
    $image = uploadImage($imageFile);

    $connection = connect();

    $sql = 'INSERT INTO sales (name, description, starts, ends, image) VALUES (:name, :description, :starts, :ends, :image)';

    $data = [
        'name' => $name,
        'description' => $description,
        'starts' => $starts,
        'ends' => $ends,
        'image' => $image
    ];

    raw($sql, $data);

    $id = $connection->lastInsertId();

    logSales('created', $id);

    return getSaleById($id);
}

function uploadImage(array $file): ?string
{
    $id = uniqid();

    $filename = "images/sales/$id.png";

    if (move_uploaded_file($file['tmp_name'], $filename)) {
        return $filename;
    }
    return null;
}

function getAllSales(string $filter = null): array
{
    $sql = "SELECT * FROM sales";
    $data = [];

    if (!is_null($filter)) {
        $sql .= " WHERE (name LIKE :filter OR description LIKE :filter)";
        $data['filter'] = '%' . $filter . '%';
    }

    $stmt = raw($sql, $data);

    return $stmt->fetchAll();
}

function getSaleById(string $id): ?stdClass
{
    $sql = "SELECT * FROM sales WHERE id = :id";
    $data = ['id' => $id];
    $stmt = raw($sql, $data);

    return $stmt->fetch();
}

function updateSale(string $id, string $name, string $description, string $starts, string $ends, array $imageFile): stdClass
{
    $sql = "UPDATE sales SET name = :name, description = :description, starts = :starts, ends = :ends";

    $data = [
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'starts' => $starts,
        'ends' => $ends,
    ];

    if ($imageFile['name'] != '') {
        $image = uploadImage($imageFile);
        $sql .= ", photo = :photo";
        $data['image'] = $image;
    }

    $sql .= " WHERE id = :id";

    raw($sql, $data);

    logSales('updated', $id);

    return getSaleById($id);
}

function removeSale(string $id): bool
{
    $sale = getSaleById($id);

    if ($sale->image) {
        unlink($sale->image);
    }

    $sql = "DELETE FROM sales WHERE id = :id";
    $data = [
        'id' => $id
    ];

    $stmt = raw($sql, $data);

    logSales('deleted', $id);

    return $stmt->rowCount() > 0;
};

function logSales($message, $id): void
{

    $user = $_SESSION['user'];

    $log = sprintf("[%s] %s %s sale %s from %s\n", date('Y-m-d H:i:s'), $user->name, $message, $id, $_SERVER['REMOTE_ADDR']);
    file_put_contents('../logs/sales.log', $log, FILE_APPEND);
}
