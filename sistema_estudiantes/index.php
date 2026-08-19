<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "config/database.php";

// Manejo de Cerrar Sesión
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Procesar el Login
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre']     = $user['nombre'];
        $_SESSION['email']      = $user['email'];
        $_SESSION['rol']        = $user['rol'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales inválidas.";
    }
}

// Carga de Vistas según estado de Sesión y Rol
if (isset($_SESSION['usuario_id'])) {
    include "includes/header.php";

    if ($_SESSION['rol'] === 'docente' || $_SESSION['rol'] === 'profesor') {
        include "vistas/dashboard_docente.php";
    } else {
        include "vistas/dashboard_estudiante.php";
    }

    echo "</div></body></html>";
} else {
    include "vistas/login.php";
}
