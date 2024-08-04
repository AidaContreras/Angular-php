<?php

include('database/connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$errors = [];
$message = '';

$category = '';
$code = '';
$name = '';
$price = '';

if ($form_data->type == "Delete") {
    $query = "
	DELETE FROM product WHERE code='".$form_data->code."'
	";
    $statement = $connect->prepare($query);
	if($statement->execute())
	{
		// $output['message'] = 'Producto eliminado';
		$output = array(
			'error'   => '',
			'message' => 'Producto eliminado'
		);
		echo json_encode($output);
		return;
	}
} else {
    if (empty($form_data->category)) {
        $errors[] = 'Categoría requerida';
    } else {
        $category = $form_data->category;
    }

    if (empty($form_data->code)) {
        $errors[] = 'Código requerido';
    } else {
        $code = $form_data->code;
    }

    if (empty($form_data->name)) {
        $errors[] = 'Nombre requerido';
    } else {
        $name = $form_data->name;
    }

    if (empty($form_data->price)) {
        $errors[] = 'Precio requerido';
    } else {
        $price = $form_data->price;
    }

    if (empty($errors)) {
        if ($form_data->type == 'Insert') {

            $query = "SELECT code FROM product WHERE code = :code";
            $statement = $connect->prepare($query);
            $statement->bindParam(':code', $code, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $errors[] = 'El código ingresado ya se encuentra registrado.';
                $output = array(
                    'error'   => $errors,
                    'message' => ''
                );
                echo json_encode($output);
                return;
            }

            $data = array(
                ':category'  => $category,
                ':code'      => $code,
                ':name'      => $name,
                ':price'     => $price,
                ':createdat' => date('Y-m-d H:i:s'),
                ':updatedat' => date('Y-m-d H:i:s')
            );

            $query = "
            INSERT INTO product 
                (category, code, name, price, createdat, updatedat) VALUES 
                (:category, :code, :name, :price, :createdat, :updatedat)
            ";
            $statement = $connect->prepare($query);
            if ($statement->execute($data)) {
                $message = 'Producto agregado';
            }
        }

        if ($form_data->type == 'Update') {
            $data = array(
                ':category'  => $category,
                ':code'      => $code,
                ':name'      => $name,
                ':price'     => $price,
                ':updatedat' => date('Y-m-d H:i:s')
            );

            $query = "
            UPDATE product 
            SET category = :category, name = :name, price = :price, updatedat = :updatedat
            WHERE code = :code
            ";

            $statement = $connect->prepare($query);
            if ($statement->execute($data)) {
                $message = 'Producto actualizado';
            }
        }
    }

    $output = array(
        'error'   => $errors,
        'message' => $message
    );

    echo json_encode($output);
}
?>
