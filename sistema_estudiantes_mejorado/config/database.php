<?php
// La conexión queda aislada de las vistas para poder reutilizarla.
$host = "localhost";
$db = "sistema_estudiantes";
$user = "estudiante";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    die("Error de conexión a la base de datos: " . $error->getMessage());
}
