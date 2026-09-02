<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar | Aula Clara</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="login-page">
    <section class="login-box">
        <p class="eyebrow">Sistema académico</p>
        <h1>Bienvenido a Aula Clara</h1>
        <p class="subtitle">Consulta y gestiona el recorrido de cada estudiante.</p>

        <?php if ($error !== ''): ?>
            <p class="error" role="alert"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <div class="field">
                <label for="usuario">DNI o correo electrónico</label>
                <input id="usuario" type="text" name="usuario" required autocomplete="username" placeholder="Ej. 11111111">
            </div>
            <div class="field">
                <label for="clave">Contraseña</label>
                <input id="clave" type="password" name="clave" required autocomplete="current-password" placeholder="Tu contraseña">
            </div>
            <button class="btn" type="submit">Ingresar al sistema</button>
        </form>
    </section>
</body>

</html>