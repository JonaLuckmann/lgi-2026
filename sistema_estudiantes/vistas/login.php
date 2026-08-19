<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <div class="login-box">
        <h2>🔐 Iniciar Sesión</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red; font-size: 14px; margin-bottom: 10px;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" required placeholder="profesor@email.com">

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>

</html>