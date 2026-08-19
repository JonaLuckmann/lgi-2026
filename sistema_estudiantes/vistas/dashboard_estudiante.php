<?php
// Consulta solo los datos y notas asociadas al email o id del alumno conectado
$sql = "SELECT e.nombre, e.apellido, m.nombre_materia, m.nota
        FROM estudiantes e
        INNER JOIN materias m ON e.id = m.estudiante_id
        WHERE e.email = :email";

$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $_SESSION['email']]);
$mis_materias = $stmt->fetchAll();
?>

<h2>Mis Calificaciones</h2>

<table>
    <thead>
        <tr>
            <th>Materia</th>
            <th>Nota Obtenida</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($mis_materias)): ?>
            <?php foreach ($mis_materias as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['nombre_materia']); ?></td>
                    <td><strong><?php echo $m['nota'] !== null ? $m['nota'] : 'Pendiente'; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No tenés materias registradas aún.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>