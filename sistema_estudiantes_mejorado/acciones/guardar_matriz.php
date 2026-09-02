<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$prof_id = $_SESSION['usuario_id'];
$accion = $_POST['accion'] ?? '';

if ($accion === 'crear') {
    $alum_id = $_POST['estudiante_id'] ?? null;
    $asig = trim($_POST['nombre_materia'] ?? '');
    $cuat = $_POST['cuatrimestre'] ?? '1er Cuatrimestre';

    if ($alum_id && $asig !== '') {
        $stmt = $pdo->prepare(
            'INSERT INTO materias (estudiante_id, docente_id, nombre_materia, cuatrimestre) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$alum_id, $prof_id, $asig, $cuat]);
    }
}

if ($accion === 'actualizar') {
    $asig_id = $_POST['materia_id'] ?? null;
    $campos = ['p1', 'p2', 'p3', 'rec1', 'rec2', 'rec3'];
    $notas = [];

    foreach ($campos as $campo) {
        $notas[$campo] = ($_POST[$campo] ?? '') !== '' ? $_POST[$campo] : null;
    }

    // El recuperatorio reemplaza al parcial de la misma posición.
    $nota_1 = $notas['rec1'] ?? $notas['p1'];
    $nota_2 = $notas['rec2'] ?? $notas['p2'];
    $nota_3 = $notas['rec3'] ?? $notas['p3'];
    $notas_validas = array_filter([$nota_1, $nota_2, $nota_3], fn($nota) => $nota !== null);
    $nota_regular = count($notas_validas) > 0
        ? number_format(array_sum($notas_validas) / count($notas_validas), 2)
        : null;

    if ($asig_id) {
        $stmt = $pdo->prepare(
            'UPDATE materias SET p1=?, p2=?, p3=?, rec1=?, rec2=?, rec3=?, nota_regular=? WHERE id=? AND docente_id=?'
        );
        $stmt->execute([
            $notas['p1'],
            $notas['p2'],
            $notas['p3'],
            $notas['rec1'],
            $notas['rec2'],
            $notas['rec3'],
            $nota_regular,
            $asig_id,
            $prof_id
        ]);
    }
}

if ($accion === 'borrar' && isset($_POST['materia_id'])) {
    $stmt = $pdo->prepare('DELETE FROM materias WHERE id=? AND docente_id=?');
    $stmt->execute([$_POST['materia_id'], $prof_id]);
}

header('Location: ../index.php');
exit;
