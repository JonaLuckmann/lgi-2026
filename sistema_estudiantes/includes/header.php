<!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <title>Sistema Académico</title>
            <link rel="stylesheet" href="css/estilos.css">
        </head>

        <body>
            <header style="display:flex; justify-content:space-between; align-items:center; padding: 15px; background: #2c3e50; color: white;">
                <h2>🎓 Sistema Académico (Rol: <?php echo ucfirst($_SESSION['rol'] ?? 'Usuario'); ?>)</h2>
                <div>
                    <span>Hola, <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></strong></span>
                    <a href="logout.php" style="margin-left: 15px; color: #e74c3c; text-decoration: none; font-weight: bold;">Cerrar Sesión 🚪</a>
                </div>
            </header>
            <div class="container" style="padding: 20px;">