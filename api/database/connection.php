<?php

    try {

        $connect = new PDO("mysql:host=localhost;dbname=prueba_tecnica", "root", "");
        $connect -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {

        die("Fallo la conexión a MySQL: " . $e->getMessage());

    }
?>


