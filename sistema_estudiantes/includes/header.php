<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema Académico</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <header>
        <h1>🎓 Sistema Académico (Rol: <?php echo ucfirst($_SESSION['rol'] ?? ''); ?>)</h1>
        <nav>
            <span>Hola, <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></strong></span>
            <a href="index.php?action=logout">Cerrar Sesión 🚪</a>
        </nav>
    </header>
    <div class="container">