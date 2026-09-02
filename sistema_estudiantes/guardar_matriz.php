<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    $docente_id = $_SESSION['usuario_id'];
    $accion     = $_POST['accion'] ?? '';


    if ($accion === 'crear') {
        $estudiante_id  = $_POST['estudiante_id'];
        $nombre_materia = $_POST['nombre_materia'];
        $cuatrimestre   = $_POST['cuatrimestre'];

        $sql = "INSERT INTO materias (estudiante_id, docente_id, nombre_materia, cuatrimestre) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$estudiante_id, $docente_id, $nombre_materia, $cuatrimestre]);
    }


    if ($accion === 'actualizar') {
        $materia_id = $_POST['materia_id'];


        $p1   = $_POST['p1'] !== '' ? $_POST['p1'] : null;
        $p2   = $_POST['p2'] !== '' ? $_POST['p2'] : null;
        $p3   = $_POST['p3'] !== '' ? $_POST['p3'] : null;
        $rec1 = $_POST['rec1'] !== '' ? $_POST['rec1'] : null;
        $rec2 = $_POST['rec2'] !== '' ? $_POST['rec2'] : null;
        $rec3 = $_POST['rec3'] !== '' ? $_POST['rec3'] : null;


        $n1 = $rec1 !== null ? $rec1 : $p1;
        $n2 = $rec2 !== null ? $rec2 : $p2;
        $n3 = $rec3 !== null ? $rec3 : $p3;

        $notas_validas = array_filter([$n1, $n2, $n3], function ($v) {
            return $v !== null;
        });

        $nota_regular = null;
        if (count($notas_validas) > 0) {
            $nota_regular = array_sum($notas_validas) / count($notas_validas);
            $nota_regular = number_format($nota_regular, 2);
        }

        $sql = "UPDATE materias SET p1=?, p2=?, p3=?, rec1=?, rec2=?, rec3=?, nota_regular=? 
                WHERE id=? AND docente_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$p1, $p2, $p3, $rec1, $rec2, $rec3, $nota_regular, $materia_id, $docente_id]);
    }

    if ($accion === 'borrar') {
        $materia_id = $_POST['materia_id'];
        $stmt = $pdo->prepare("DELETE FROM materias WHERE id=? AND docente_id=?");
        $stmt->execute([$materia_id, $docente_id]);
    }
}

header("Location: index.php");
exit;
