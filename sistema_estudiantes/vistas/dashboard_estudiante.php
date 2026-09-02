<?php
$estudiante_id = $_SESSION['usuario_id'];

// Obtener todas las materias del alumno
$stmt = $pdo->prepare("SELECT * FROM materias WHERE estudiante_id = ? ORDER BY cuatrimestre ASC, nombre_materia ASC");
$stmt->execute([$estudiante_id]);
$mis_materias = $stmt->fetchAll();

// Cálculo de Promedio General de Notas Regulares
$sum_prom = 0;
$cant_prom = 0;
foreach ($mis_materias as $m) {
    if ($m['nota_regular'] !== null) {
        $sum_prom += $m['nota_regular'];
        $cant_prom++;
    }
}
$promedio_general = $cant_prom > 0 ? number_format($sum_prom / $cant_prom, 2) : 'N/A';
?>

<h2>Mis Calificaciones Académicas</h2>

<div style="background: #f8f9fa; border-left: 4px solid #2980b9; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
    <strong>Promedio General Regular:</strong> <span style="font-size: 1.2em; color: #2c3e50;"><?php echo $promedio_general; ?></span>
</div>

<table border="1" style="width: 100%; border-collapse: collapse; text-align: center; font-family: sans-serif;">
    <thead>
        <tr style="background: #2c3e50; color: white;">
            <th rowspan="2" style="padding: 10px;">Materia</th>
            <th colspan="3" style="background: #3498db; padding: 5px;">PARCIALES</th>
            <th colspan="3" style="background: #f39c12; padding: 5px;">EXAM. REC.</th>
            <th rowspan="2" style="background: #e74c3c; padding: 10px;">Global</th>
            <th rowspan="2" style="background: #27ae60; padding: 10px;">Nota Regular</th>
        </tr>
        <tr style="background: #34495e; color: white;">
            <th>1°</th>
            <th>2°</th>
            <th>3°</th>
            <th>1°</th>
            <th>2°</th>
            <th>3°</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($mis_materias)): ?>
            <?php foreach ($mis_materias as $mat): ?>
                <tr>
                    <td style="text-align: left; padding: 8px; font-weight: bold;"><?php echo htmlspecialchars($mat['nombre_materia']); ?></td>
                    <td><?php echo $mat['p1'] ?? '-'; ?></td>
                    <td><?php echo $mat['p2'] ?? '-'; ?></td>
                    <td><?php echo $mat['p3'] ?? '-'; ?></td>
                    <td style="background: #fffde7;"><?php echo $mat['rec1'] ?? '-'; ?></td>
                    <td style="background: #fffde7;"><?php echo $mat['rec2'] ?? '-'; ?></td>
                    <td style="background: #fffde7;"><?php echo $mat['rec3'] ?? '-'; ?></td>
                    <td><?php echo $mat['global'] ?? '-'; ?></td>
                    <td style="font-weight: bold; background: #e8f8f5;"><?php echo $mat['nota_regular'] ?? '-'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" style="padding: 15px; color: #7f8c8d;">No tenés materias ni notas registradas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>