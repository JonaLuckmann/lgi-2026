<?php
$nombre_usuario = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario');
$rol_usuario = ucfirst($_SESSION['rol'] ?? 'Usuario');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Clara | Sistema Académico</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <header class="topbar">
        <a class="brand" href="index.php"><span class="brand-mark">AC</span><span>Aula Clara</span></a>
        <div class="user-menu">
            <span class="user-name"><?php echo $nombre_usuario; ?></span>
            <span class="role-label">Rol: <?php echo htmlspecialchars($rol_usuario); ?></span>
            <a class="logout-link" href="logout.php">Salir</a>
        </div>
    </header>
    <main class="page-shell">