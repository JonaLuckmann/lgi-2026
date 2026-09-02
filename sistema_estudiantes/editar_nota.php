<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    $materia_id = $_POST['materia_id'] ?? null;
    $nueva_nota = $_POST['nota'] ?? null;

    if ($materia_id !== null && $nueva_nota !== null) {
        $sql = "UPDATE materias SET nota = :nota WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nota' => $nueva_nota,
            'id'   => $materia_id
        ]);
    }
}

header("Location: index.php");
exit;
