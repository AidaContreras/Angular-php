<?php
include('api/database/connection.php');

$data = [];

$query1 = "SELECT category.name as categoria, product.code, product.name, product.category, product.price 
           FROM product
           JOIN category ON category.id = product.category 
           ORDER BY category.name";

$statement1 = $connect->prepare($query1);

if ($statement1->execute()) {
    while($row = $statement1->fetch(PDO::FETCH_ASSOC)) {
        $data['productos'][] = $row;
    }
}

$query2 = "SELECT id, name FROM category ORDER BY name";

$statement2 = $connect->prepare($query2);

if ($statement2->execute()) {
    while($row = $statement2->fetch(PDO::FETCH_ASSOC)) {
        $data['categoria'][] = $row;
    }
}

echo json_encode($data);
?>
