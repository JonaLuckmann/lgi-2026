<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    $materia_id = $_POST['materia_id'] ?? null;
    $nueva_nota = $_POST['nota'] ?? null;

    if ($materia_id !== null && $nueva_nota !== null) {
        // Actualiza asegurando que la materia pertenezca al docente en sesión
        $stmt = $pdo->prepare("UPDATE materias SET nota = ? WHERE id = ? AND docente_id = ?");
        $stmt->execute([$nueva_nota, $materia_id, $_SESSION['usuario_id']]);
    }
}

header("Location: index.php");
exit;
