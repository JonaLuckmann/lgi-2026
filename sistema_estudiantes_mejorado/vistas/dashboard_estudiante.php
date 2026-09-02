<?php
$alum_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare('SELECT * FROM materias WHERE estudiante_id = ? ORDER BY cuatrimestre, nombre_materia');
$stmt->execute([$alum_id]);
$asigs = $stmt->fetchAll();
$notas = array_filter(array_column($asigs, 'nota_regular'), fn($nota) => $nota !== null);
$promedio = $notas ? number_format(array_sum($notas) / count($notas), 2) : 'N/A';
$aprobadas = count(array_filter($notas, fn($nota) => $nota >= 6));
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">Mi espacio</p>
        <h1>Tu recorrido académico</h1>
        <p class="subtitle">Una vista clara de tus materias, parciales y recuperatorios.</p>
    </div>
</section>
<section class="summary-grid">
    <article class="summary-card"><small>Materias aprobadas</small><strong class="score-good"><?php echo $aprobadas; ?></strong></article>
    <article class="summary-card"><small>Materias cursadas</small><strong><?php echo count($asigs); ?></strong></article>
    <article class="summary-card"><small>En seguimiento</small><strong class="score-warning"><?php echo count($asigs) - $aprobadas; ?></strong></article>
</section>
<section class="panel">
    <div class="panel-heading">
        <h2>Mis calificaciones</h2><span class="eyebrow"><?php echo count($asigs); ?> materias</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Parciales</th>
                    <th>Recuperatorios</th>
                    <th>Global</th>
                    <th>Nota regular</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asigs as $asig): ?><tr>
                        <td><strong><?php echo htmlspecialchars($asig['nombre_materia']); ?></strong><br><small><?php echo htmlspecialchars($asig['cuatrimestre']); ?></small></td>
                        <td><?php echo htmlspecialchars(($asig['p1'] ?? '-') . ' · ' . ($asig['p2'] ?? '-') . ' · ' . ($asig['p3'] ?? '-')); ?></td>
                        <td><?php echo htmlspecialchars(($asig['rec1'] ?? '-') . ' · ' . ($asig['rec2'] ?? '-') . ' · ' . ($asig['rec3'] ?? '-')); ?></td>
                        <td><?php echo htmlspecialchars($asig['global'] ?? '-'); ?></td>
                        <td class="score score-good"><?php echo htmlspecialchars($asig['nota_regular'] ?? '-'); ?></td>
                    </tr><?php endforeach; ?><?php if (!$asigs): ?><tr>
                        <td class="empty-state" colspan="5">Todavía no tenés materias ni notas registradas.</td>
                    </tr><?php endif; ?></tbody>
        </table>
    </div>
</section>