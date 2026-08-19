<?php
$busqueda = $_GET['buscar'] ?? '';
$profesor_id = $_SESSION['usuario_id'] ?? 1;

// Consulta flexible: busca por nombre, apellido, email o materia
$sql = "SELECT e.id AS estudiante_id, e.nombre, e.apellido, e.email, e.activo,
               m.id AS materia_id, m.nombre_materia, m.nota
        FROM estudiantes e
        INNER JOIN materias m ON e.id = m.estudiante_id
        WHERE (m.profesor_id = :p_id OR m.docente_id = :p_id OR m.profesor_id IS NULL)
        AND (e.nombre LIKE :b OR e.apellido LIKE :b OR e.email LIKE :b OR m.nombre_materia LIKE :b)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'p_id' => $profesor_id,
    'b'    => "%$busqueda%"
]);
$registros = $stmt->fetchAll();
?>

<h2>Panel del Docente</h2>
<p>Gestión de alumnos y carga de notas para sus materias asignadas:</p>

<!-- Formulario de Búsqueda -->
<form method="GET" action="index.php" class="search-bar" style="margin-top: 15px; margin-bottom: 20px;">
    <input type="text" name="buscar" placeholder="Buscar por alumno, email o materia..." value="<?php echo htmlspecialchars($busqueda); ?>">
    <button type="submit" class="btn">Buscar 🔍</button>
    <?php if (!empty($busqueda)): ?>
        <a href="index.php" class="btn" style="background:#7f8c8d;">Limpiar</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>Alumno</th>
            <th>Email</th>
            <th>Materia</th>
            <th>Nota Actual</th>
            <th>Acción / Editar Nota</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($registros)): ?>
            <?php foreach ($registros as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_materia']); ?></td>
                    <td><strong><?php echo $row['nota'] !== null ? $row['nota'] : '-'; ?></strong></td>
                    <td>
                        <form action="editar_nota.php" method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="materia_id" value="<?php echo $row['materia_id']; ?>">
                            <input type="number" step="0.5" min="1" max="10" name="nota" value="<?php echo $row['nota']; ?>" style="width: 65px; padding: 4px;" required>
                            <button type="submit" class="btn btn-edit">Guardar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center; color: #7f8c8d; padding: 20px;">
                    No se encontraron alumnos o materias registradas para esta búsqueda.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>