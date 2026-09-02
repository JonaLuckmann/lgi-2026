<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    $alum_id = $_POST['estudiante_id'] ?? null;
    $asig = trim($_POST['nombre_materia'] ?? '');
    $cuat = $_POST['cuatrimestre'] ?? '1er Cuatrimestre';
    $eval = $_POST['evaluacion'] ?? 'Parcial';
    $nota = $_POST['nota'] ?? null;

    if ($alum_id && $asig !== '' && $nota !== null) {
        $stmt = $pdo->prepare(
            'INSERT INTO materias (nombre_materia, estudiante_id, nota, cuatrimestre, evaluacion, profesor_id, docente_id) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $prof_id = $_SESSION['usuario_id'];
        $stmt->execute([$asig, $alum_id, $nota, $cuat, $eval, $prof_id, $prof_id]);
    }
}

header('Location: ../index.php');
exit;
