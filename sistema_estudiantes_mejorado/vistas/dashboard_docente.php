<?php
$filtro = trim($_GET['buscar'] ?? '');
$prof_id = $_SESSION['usuario_id'];

$stmt_asig = $pdo->prepare('SELECT nombre_materia FROM docente_materia WHERE docente_id = ?');
$stmt_asig->execute([$prof_id]);
$asigs = $stmt_asig->fetchAll(PDO::FETCH_COLUMN);

$stmt_alum = $pdo->query('SELECT id, nombre, apellido FROM estudiantes WHERE activo = 1 ORDER BY apellido ASC');
$alumnos = $stmt_alum->fetchAll();

$stmt_filas = $pdo->prepare(
    'SELECT m.*, e.nombre, e.apellido FROM materias m INNER JOIN estudiantes e ON m.estudiante_id = e.id WHERE m.docente_id = :docente AND (e.nombre LIKE :busqueda OR e.apellido LIKE :busqueda OR m.nombre_materia LIKE :busqueda) ORDER BY m.nombre_materia, e.apellido'
);
$stmt_filas->execute(['docente' => $prof_id, 'busqueda' => "%$filtro%"]);
$filas = $stmt_filas->fetchAll();
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">Espacio docente</p>
        <h1>Seguimiento de calificaciones</h1>
        <p class="subtitle">Carga, actualiza y revisa el avance de tus estudiantes.</p>
    </div>
</section>
<section class="summary-grid">
    <article class="summary-card"><small>Registros visibles</small><strong><?php echo count($filas); ?></strong></article>
    <article class="summary-card"><small>Materias propias</small><strong><?php echo count($asigs); ?></strong></article>
    <article class="summary-card"><small>Estudiantes activos</small><strong><?php echo count($alumnos); ?></strong></article>
    <article class="summary-card"><small>Estado</small><strong class="score-good">Activo</strong></article>
</section>
<section class="panel">
    <div class="panel-heading">
        <h2>Registrar planilla</h2><span class="eyebrow">Nueva materia</span>
    </div>
    <?php if ($asigs): ?>
        <form class="form-grid" action="acciones/guardar_matriz.php" method="POST">
            <div><label for="student">Alumno</label><select id="student" name="estudiante_id" required><?php foreach ($alumnos as $alumno): ?><option value="<?php echo $alumno['id']; ?>"><?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']); ?></option><?php endforeach; ?></select></div>
            <div><label for="subject">Asignatura</label><select id="subject" name="nombre_materia" required><?php foreach ($asigs as $asig): ?><option><?php echo htmlspecialchars($asig); ?></option><?php endforeach; ?></select></div>
            <div><label for="term">Cuatrimestre</label><select id="term" name="cuatrimestre">
                    <option>1er Cuatrimestre</option>
                    <option>2do Cuatrimestre</option>
                </select></div>
            <button class="btn" type="submit" name="accion" value="crear">+ Crear planilla</button>
        </form>
    <?php else: ?><p class="empty-state">No hay materias asignadas a este docente.</p><?php endif; ?>
</section>
<section class="panel" style="margin-top: 20px;">
    <div class="panel-heading">
        <h2>Planillas y notas</h2>
        <form action="index.php" method="GET"><input name="buscar" value="<?php echo htmlspecialchars($filtro); ?>" placeholder="Buscar alumno o materia"></form>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Materia</th>
                    <th>Parciales</th>
                    <th>Recuperatorios</th>
                    <th>Nota regular</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $fila): ?>
                    <?php $form_id = 'materia-' . (int) $fila['id']; ?>
                    <form id="<?php echo $form_id; ?>" action="acciones/guardar_matriz.php" method="POST">
                        <input type="hidden" name="materia_id" value="<?php echo $fila['id']; ?>">
                    </form>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']); ?></strong></td>
                        <td><?php echo htmlspecialchars($fila['nombre_materia']); ?></td>
                        <td><?php foreach (['p1', 'p2', 'p3'] as $campo): ?><input form="<?php echo $form_id; ?>" class="grade-input" type="number" step="0.5" min="1" max="10" name="<?php echo $campo; ?>" value="<?php echo htmlspecialchars($fila[$campo] ?? ''); ?>" aria-label="<?php echo $campo; ?>"><?php endforeach; ?></td>
                        <td><?php foreach (['rec1', 'rec2', 'rec3'] as $campo): ?><input form="<?php echo $form_id; ?>" class="grade-input" type="number" step="0.5" min="1" max="10" name="<?php echo $campo; ?>" value="<?php echo htmlspecialchars($fila[$campo] ?? ''); ?>" aria-label="<?php echo $campo; ?>"><?php endforeach; ?></td>
                        <td class="score score-good"><?php echo htmlspecialchars($fila['nota_regular'] ?? '-'); ?></td>
                        <td><button form="<?php echo $form_id; ?>" class="btn btn-small" type="submit" name="accion" value="actualizar">Guardar</button> <button form="<?php echo $form_id; ?>" class="btn btn-small btn-danger" type="submit" name="accion" value="borrar">Borrar</button></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$filas): ?><tr>
                        <td class="empty-state" colspan="6">No hay registros para mostrar.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>