<?php
session_start();
require_once "config/database.php";

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login_user = trim($_POST['login_user'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    if (!empty($login_user) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE (dni = ? OR email = ?) AND password = ?");
        $stmt->execute([$login_user, $login_user, $password]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['rol']        = strtolower(trim($user['rol']));

            header("Location: index.php");
            exit;
        } else {
            $error = "DNI/Email o contraseña incorrectos.";
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}

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