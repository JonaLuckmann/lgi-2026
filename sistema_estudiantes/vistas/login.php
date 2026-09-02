<div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
    <h2>Iniciar Sesión</h2>

    <?php if (!empty($error)): ?>
        <div style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px;">DNI o Correo Electrónico:</label>
            <input type="text" name="login_user" required style="width: 100%; padding: 8px;" placeholder="Ej: 11111111 o usuario@email.com">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px;">Contraseña:</label>
            <input type="password" name="password" required style="width: 100%; padding: 8px;" placeholder="••••••••">
        </div>
        <button type="submit" style="width: 100%; padding: 10px; background: #2980b9; color: white; border: none; cursor: pointer; border-radius: 4px;">Ingresar</button>
    </form>
</div>