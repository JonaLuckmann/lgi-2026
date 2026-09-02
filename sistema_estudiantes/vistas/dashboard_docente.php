<?php
$busqueda      = $_GET['buscar'] ?? '';
$cuatri_filtro = $_GET['cuatrimestre'] ?? '';
$docente_id    = $_SESSION['usuario_id'];

$stmt_alumnos  = $pdo->query("SELECT id, nombre, apellido FROM estudiantes WHERE activo = 1 ORDER BY apellido ASC");
$lista_alumnos = $stmt_alumnos->fetchAll();

$sql = "SELECT m.id AS materia_id, e.nombre, e.apellido, m.nombre_materia, 
               m.cuatrimestre, m.evaluacion, m.nota
        FROM materias m
        INNER JOIN estudiantes e ON m.estudiante_id = e.id
        WHERE (m.docente_id = :d_id OR m.profesor_id = :d_id OR :d_id = 1)
        AND (e.nombre LIKE :b OR e.apellido LIKE :b OR m.nombre_materia LIKE :b)";

if (!empty($cuatri_filtro)) {
    $sql .= " AND m.cuatrimestre = :cuatri";
}

$sql .= " ORDER BY m.cuatrimestre ASC, e.apellido ASC";

$stmt   = $pdo->prepare($sql);
$params = ['d_id' => $docente_id, 'b' => "%$busqueda%"];
if (!empty($cuatri_filtro)) $params['cuatri'] = $cuatri_filtro;

$stmt->execute($params);
$registros = $stmt->fetchAll();
?>

<h2>Panel del Docente - Calificaciones Cuatrimestrales</h2>

<details style="margin-bottom: 20px; background: #eef2f5; padding: 15px; border-radius: 6px;">
    <summary style="font-weight: bold; cursor: pointer; color: #2c3e50;">➕ Cargar Nueva Nota / Evaluación</summary>
    <form action="nueva_nota.php" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; margin-top: 15px;">
        <div>
            <label>Alumno:</label>
            <select name="estudiante_id" required style="width: 100%; padding: 6px;">
                <?php foreach ($lista_alumnos as $al): ?>
                    <option value="<?php echo $al['id']; ?>"><?php echo htmlspecialchars($al['nombre'] . ' ' . $al['apellido']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Materia:</label>
            <input type="text" name="nombre_materia" required placeholder="Ej: Programación Web" style="width: 100%; padding: 6px;">
        </div>
        <div>
            <label>Cuatrimestre:</label>
            <select name="cuatrimestre" style="width: 100%; padding: 6px;">
                <option value="1er Cuatrimestre">1er Cuatrimestre</option>
                <option value="2do Cuatrimestre">2do Cuatrimestre</option>
            </select>
        </div>
        <div>
            <label>Evaluación:</label>
            <input type="text" name="evaluacion" placeholder="Ej: Parcial 1, TP" required style="width: 100%; padding: 6px;">
        </div>
        <div>
            <label>Nota (1 al 10):</label>
            <input type="number" step="0.5" min="1" max="10" name="nota" required style="width: 100%; padding: 6px;">
        </div>
        <div style="grid-column: 1 / -1; text-align: right; margin-top: 5px;">
            <button type="submit" class="btn">Guardar Nota</button>
        </div>
    </form>
</details>

<form method="GET" action="index.php" class="search-bar" style="margin-bottom: 20px;">
    <input type="text" name="buscar" placeholder="Buscar alumno o materia..." value="<?php echo htmlspecialchars($busqueda); ?>">
    <select name="cuatrimestre" style="padding: 8px;">
        <option value="">-- Todos los Cuatrimestres --</option>
        <option value="1er Cuatrimestre" <?php if ($cuatri_filtro === '1er Cuatrimestre') echo 'selected'; ?>>1er Cuatrimestre</option>
        <option value="2do Cuatrimestre" <?php if ($cuatri_filtro === '2do Cuatrimestre') echo 'selected'; ?>>2do Cuatrimestre</option>
    </select>
    <button type="submit" class="btn">Filtrar 🔍</button>
</form>

<table>
    <thead>
        <tr>
            <th>Alumno</th>
            <th>Materia</th>
            <th>Cuatrimestre</th>
            <th>Evaluación</th>
            <th>Nota</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($registros)): ?>
            <?php foreach ($registros as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_materia']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row['cuatrimestre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['evaluacion']); ?></td>
                    <td><strong><?php echo $row['nota']; ?></strong></td>
                    <td>
                        <form action="editar_nota.php" method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="materia_id" value="<?php echo $row['materia_id']; ?>">
                            <input type="number" step="0.5" min="1" max="10" name="nota" value="<?php echo $row['nota']; ?>" style="width: 60px; padding: 3px;" required>
                            <button type="submit" class="btn btn-edit">Modificar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center; color: #7f8c8d; padding: 20px;">No se encontraron registros.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>