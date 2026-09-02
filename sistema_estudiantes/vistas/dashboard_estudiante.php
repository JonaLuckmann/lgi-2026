<?php
$estudiante_id = $_SESSION['usuario_id'];

$sql = "SELECT nombre_materia, cuatrimestre, evaluacion, nota 
        FROM materias 
        WHERE estudiante_id = ? 
        ORDER BY cuatrimestre ASC, nombre_materia ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$mis_notas = $stmt->fetchAll();

$promedios = [];
foreach ($mis_notas as $n) {
    if ($n['nota'] !== null && $n['nota'] !== '') {
        $promedios[$n['cuatrimestre']][] = $n['nota'];
    }
}
?>

<h2>Mis Calificaciones Académicas</h2>

<div style="display: flex; gap: 15px; margin-bottom: 20px;">
    <?php if (!empty($promedios)): ?>
        <?php foreach ($promedios as $cuatri => $notas): ?>
            <?php
            $cant = count($notas);
            $prom = $cant > 0 ? array_sum($notas) / $cant : 0;
            ?>
            <div style="background: #f8f9fa; border-left: 4px solid #2980b9; padding: 12px 20px; border-radius: 4px; flex: 1;">
                <small style="color: #7f8c8d; font-weight: bold;"><?php echo htmlspecialchars($cuatri); ?></small>
                <h3 style="margin-top: 5px; color: #2c3e50;">Promedio: <?php echo number_format($prom, 2); ?></h3>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: #7f8c8d;">Aún no tenés notas cargadas para calcular promedios.</p>
    <?php endif; ?>
</div>

<table>
    <thead>
        <tr>
            <th>Cuatrimestre</th>
            <th>Materia</th>
            <th>Evaluación</th>
            <th>Nota Obtenida</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($mis_notas)): ?>
            <?php foreach ($mis_notas as $m): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($m['cuatrimestre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($m['nombre_materia']); ?></td>
                    <td><?php echo htmlspecialchars($m['evaluacion']); ?></td>
                    <td><strong><?php echo $m['nota'] !== null ? htmlspecialchars($m['nota']) : '-'; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center; color: #7f8c8d; padding: 20px;">No tenés calificaciones cargadas aún.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>