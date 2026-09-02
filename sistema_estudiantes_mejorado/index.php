<?php
session_start();
require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($usuario !== '' && $clave !== '') {
        $stmt = $pdo->prepare(
            'SELECT * FROM usuarios WHERE (dni = ? OR email = ?) AND password = ?'
        );
        $stmt->execute([$usuario, $usuario, $clave]);
        $fila = $stmt->fetch();

        if ($fila) {
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['email'] = $fila['email'];
            $_SESSION['rol'] = strtolower(trim($fila['rol']));
            header('Location: index.php');
            exit;
        }

        $error = 'DNI/correo o contraseña incorrectos.';
    } else {
        $error = 'Completa los dos campos para ingresar.';
    }
}

if (!isset($_SESSION['usuario_id'])) {
    require __DIR__ . '/vistas/login.php';
    exit;
}

require __DIR__ . '/includes/header.php';

$es_prof = in_array($_SESSION['rol'], ['docente', 'profesor'], true);
if ($es_prof) {
    require __DIR__ . '/vistas/dashboard_docente.php';
} else {
    require __DIR__ . '/vistas/dashboard_estudiante.php';
}

require __DIR__ . '/includes/footer.php';
