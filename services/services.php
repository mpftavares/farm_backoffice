<?php

function createService(string $name, string $description, array $imageFile): stdClass
{
    $image = uploadServiceImage($imageFile);

    $connection = getConnection();

    $sql = 'INSERT INTO services (name, description, image) VALUES (:name, :description, :image)';
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'image' => $image
    ]);

    $id = $connection->lastInsertId();

    logServices('created', $id);

    return getServiceById($id);
}

function uploadServiceImage(array $file): ?string
{
    $id = uniqid();

    $filename = "images/services/$id.png";

    if (move_uploaded_file($file['tmp_name'], $filename)) {
        return $filename;
    }
    return null;
}

function getAllServices(string $filter = null): array
{
    $sql = "SELECT * FROM services";
    $data = [];

    if (!is_null($filter)) {
        $sql .= " WHERE (name LIKE :filter OR description LIKE :filter)";
        $data['filter'] = '%' . $filter . '%';
    }

    $connection = getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->execute($data);

    return $stmt->fetchAll();
}

function getServiceById(string $id): ?stdClass
{
    $sql = "SELECT * FROM services WHERE id = :id";

    $connection = getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch();
}

function updateService(string $id, string $name, string $description): stdClass
{
    $sql = "UPDATE services SET name = :name, description = :description";
    
    $data = [
        'id' => $id,
        'name' => $name,
        'description' => $description,
    ];

    $sql .= " WHERE id = :id";

    $connection = getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->execute($data);

    logServices('updated', $id);

    return getServiceById($id);
}

function removeService(string $id): bool
{
    $service = getServiceById($id);

    $sql = "DELETE FROM services WHERE id = :id";

    $connection = getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        'id' => $id
    ]);

    logServices('deleted', $id);

    return $stmt->rowCount() > 0;
};

function logServices($message, $id): void
{

    $user = $_SESSION['user'];

    $log = sprintf("[%s] %s %s service %s from %s\n", date('Y-m-d H:i:s'), $user->name, $message, $id, $_SERVER['REMOTE_ADDR']);
    file_put_contents('../logs/services.log', $log, FILE_APPEND);
}
