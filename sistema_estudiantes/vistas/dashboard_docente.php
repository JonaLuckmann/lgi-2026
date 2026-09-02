<?php
$busqueda   = $_GET['buscar'] ?? '';
$docente_id = $_SESSION['usuario_id'];

$stmt_materia_doc = $pdo->prepare("SELECT nombre_materia FROM docente_materia WHERE docente_id = ?");
$stmt_materia_doc->execute([$docente_id]);
$mis_materias = $stmt_materia_doc->fetchAll(PDO::FETCH_COLUMN);

$stmt_alumnos  = $pdo->query("SELECT id, nombre, apellido FROM estudiantes WHERE activo = 1 ORDER BY apellido ASC");
$lista_alumnos = $stmt_alumnos->fetchAll();

$sql = "SELECT m.*, e.nombre, e.apellido 
        FROM materias m
        INNER JOIN estudiantes e ON m.estudiante_id = e.id
        WHERE m.docente_id = :d_id 
        AND (e.nombre LIKE :b OR e.apellido LIKE :b OR m.nombre_materia LIKE :b)
        ORDER BY m.nombre_materia ASC, e.apellido ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['d_id' => $docente_id, 'b' => "%$busqueda%"]);
$registros = $stmt->fetchAll();
?>

<h2>Panel del Docente - Carga de Parciales y Recuperatorios</h2>

<details style="margin-bottom: 20px; background: #eef2f5; padding: 15px; border-radius: 6px;" open>
    <summary style="font-weight: bold; cursor: pointer; color: #2c3e50;">➕ Registrar Alumno en Materia</summary>

    <?php if (!empty($mis_materias)): ?>
        <form action="guardar_matriz.php" method="POST" style="display: flex; gap: 10px; margin-top: 15px; align-items: flex-end; flex-wrap: wrap;">
            <div>
                <label style="display:block;">Alumno:</label>
                <select name="estudiante_id" required style="padding: 6px;">
                    <?php foreach ($lista_alumnos as $al): ?>
                        <option value="<?php echo $al['id']; ?>"><?php echo htmlspecialchars($al['nombre'] . ' ' . $al['apellido']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block;">Asignatura:</label>
                <select name="nombre_materia" required style="padding: 6px;">
                    <?php foreach ($mis_materias as $mat): ?>
                        <option value="<?php echo htmlspecialchars($mat); ?>"><?php echo htmlspecialchars($mat); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block;">Cuatrimestre:</label>
                <select name="cuatrimestre" style="padding: 6px;">
                    <option value="1er Cuatrimestre">1er Cuatrimestre</option>
                    <option value="2do Cuatrimestre">2do Cuatrimestre</option>
                </select>
            </div>
            <button type="submit" name="accion" value="crear" style="background: #27ae60; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 4px;">Crear Planilla</button>
        </form>
    <?php endif; ?>
</details>

<div style="overflow-x: auto;">
    <table border="1" style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.9em;">
        <thead>
            <tr style="background: #2c3e50; color: white;">
                <th rowspan="2">Alumno</th>
                <th rowspan="2">Materia</th>
                <th colspan="3" style="background: #2980b9;">PARCIALES</th>
                <th colspan="3" style="background: #d35400;">RECUPERATORIOS</th>
                <th rowspan="2" style="background: #27ae60;">Nota Regular</th>
                <th rowspan="2">Acciones</th>
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
            <?php if (!empty($registros)): ?>
                <?php foreach ($registros as $row): ?>
                    <form action="guardar_matriz.php" method="POST">
                        <input type="hidden" name="materia_id" value="<?php echo $row['id']; ?>">
                        <tr>
                            <td style="text-align: left; padding: 5px; font-weight: bold;"><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                            <td style="text-align: left; padding: 5px;"><?php echo htmlspecialchars($row['nombre_materia']); ?></td>

                            <!-- Parciales -->
                            <td><input type="number" step="0.5" min="1" max="10" name="p1" value="<?php echo $row['p1']; ?>" style="width: 45px;"></td>
                            <td><input type="number" step="0.5" min="1" max="10" name="p2" value="<?php echo $row['p2']; ?>" style="width: 45px;"></td>
                            <td><input type="number" step="0.5" min="1" max="10" name="p3" value="<?php echo $row['p3']; ?>" style="width: 45px;"></td>


                            <td style="background: #fef9e7;"><input type="number" step="0.5" min="1" max="10" name="rec1" value="<?php echo $row['rec1']; ?>" style="width: 45px;"></td>
                            <td style="background: #fef9e7;"><input type="number" step="0.5" min="1" max="10" name="rec2" value="<?php echo $row['rec2']; ?>" style="width: 45px;"></td>
                            <td style="background: #fef9e7;"><input type="number" step="0.5" min="1" max="10" name="rec3" value="<?php echo $row['rec3']; ?>" style="width: 45px;"></td>


                            <td style="font-weight: bold; background: #e8f8f5;"><?php echo $row['nota_regular'] ?? '-'; ?></td>


                            <td style="padding: 5px; white-space: nowrap;">
                                <button type="submit" name="accion" value="actualizar" style="background: #2980b9; color: white; border: none; padding: 4px 8px; cursor: pointer; border-radius: 3px;">💾 Guardar</button>
                                <button type="submit" name="accion" value="borrar" onclick="return confirm('¿Borrar registro?')" style="background: #c0392b; color: white; border: none; padding: 4px 8px; cursor: pointer; border-radius: 3px;">🗑️</button>
                            </td>
                        </tr>
                    </form>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="padding: 15px;">No hay registros cargados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>