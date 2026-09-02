<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    $estudiante_id  = $_POST['estudiante_id'] ?? null;
    $nombre_materia = $_POST['nombre_materia'] ?? '';
    $cuatrimestre   = $_POST['cuatrimestre'] ?? '1er Cuatrimestre';
    $evaluacion     = $_POST['evaluacion'] ?? 'Parcial';
    $nota           = $_POST['nota'] ?? null;
    $docente_id     = $_SESSION['usuario_id'];

    if ($estudiante_id && !empty($nombre_materia) && $nota !== null) {
        $sql = "INSERT INTO materias (nombre_materia, estudiante_id, nota, cuatrimestre, evaluacion, profesor_id, docente_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_materia, $estudiante_id, $nota, $cuatrimestre, $evaluacion, $docente_id, $docente_id]);
    }
}

header("Location: index.php");
exit;
