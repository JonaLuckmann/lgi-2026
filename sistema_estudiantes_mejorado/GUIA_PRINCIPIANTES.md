# Guía para principiantes: cómo viajan las notas

Esta guía explica la versión mejorada desde el punto de vista de una persona que recién empieza con PHP.

## 1. El recorrido completo

Cuando el docente guarda una nota ocurre esto:

```text
1. El navegador muestra dashboard_docente.php.
2. El docente escribe valores en los input.
3. Cada input tiene un atributo name: p1, p2, rec1, etc.
4. El botón tiene name="accion" y value="actualizar".
5. El formulario envía todos esos datos por POST.
6. acciones/guardar_matriz.php recibe los datos.
7. PHP calcula nota_regular.
8. SQL actualiza la fila de materias.
9. PHP redirige a index.php.
10. El dashboard vuelve a consultar y muestra el resultado.
```

La conexión entre pantalla y servidor es el formulario:

```php
<form action="acciones/guardar_matriz.php" method="POST">
    <input type="hidden" name="materia_id" value="15">
    <input type="number" name="p1" value="7">
    <input type="number" name="rec1" value="">
    <button type="submit" name="accion" value="actualizar">Guardar</button>
</form>
```

- `action`: archivo que recibirá el formulario.
- `method`: forma de envío. `POST` manda los datos ocultos en la solicitud.
- `name`: nombre de la clave que recibirá PHP.
- `value`: valor que se envía.
- `type="hidden"`: dato que se envía, aunque no se vea.
- `type="submit"`: botón que envía el formulario.

El navegador enviaría algo parecido a esto:

```text
materia_id=15&p1=7&rec1=&accion=actualizar
```

En PHP se recibe así:

```php
$_POST['materia_id']; // 15
$_POST['p1'];         // 7
$_POST['rec1'];       // cadena vacía
$_POST['accion'];     // actualizar
```

## 2. Dónde empieza el programa

Archivo: `index.php`.

### Sesión y conexión

Al principio:

```php
session_start();
require_once __DIR__ . '/config/database.php';
```

- `session_start()` permite leer y guardar datos del usuario conectado.
- `require_once` importa otro archivo una sola vez.
- `__DIR__` significa la carpeta del archivo actual. Evita problemas con rutas.

### Login

Cuando el formulario de login usa `method="POST"`, `index.php` entra en este bloque:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = trim($_POST['clave'] ?? '');
}
```

- `$_SERVER['REQUEST_METHOD']` dice si llegó una solicitud `GET` o `POST`.
- `trim()` quita espacios al comienzo y al final.
- `?? ''` significa: si la clave no existe, usar texto vacío.
- `$` indica que se trata de una variable PHP.

Después se decide qué panel mostrar:

```php
$es_docente = in_array($_SESSION['rol'], ['docente', 'profesor'], true);

if ($es_docente) {
    require __DIR__ . '/vistas/dashboard_docente.php';
} else {
    require __DIR__ . '/vistas/dashboard_estudiante.php';
}
```

`in_array()` busca el rol dentro de una lista. El tercer argumento `true` exige que el tipo también coincida.

## 3. Qué significa cada sufijo de nota

Los sufijos no son funciones especiales de PHP. Son nombres elegidos por el programador.

| Nombre          | Significado                  | Ejemplo                     |
| --------------- | ---------------------------- | --------------------------- |
| `p1`            | parcial número 1             | `name="p1"`                 |
| `p2`            | parcial número 2             | `name="p2"`                 |
| `p3`            | parcial número 3             | `name="p3"`                 |
| `rec1`          | recuperatorio del parcial 1  | `name="rec1"`               |
| `rec2`          | recuperatorio del parcial 2  | `name="rec2"`               |
| `rec3`          | recuperatorio del parcial 3  | `name="rec3"`               |
| `nota_regular`  | promedio calculado           | columna de la base de datos |
| `materia_id`    | identificador de una fila    | `value="15"`                |
| `estudiante_id` | identificador del estudiante | `value="8"`                 |
| `docente_id`    | identificador del docente    | viene de la sesión          |

Los nombres `$alum_id`, `$prof_id`, `$asig` y `$cuat` son abreviaturas internas:

```text
$alum_id = alumno_id
$prof_id = profesor_id
$asig    = asignatura o materia
$cuat    = cuatrimestre
$stmt    = statement, objeto de consulta preparado
$fila    = una fila devuelta por la base de datos
```

Por eso estas dos partes deben coincidir:

```php
// En la vista
<input name="p1" value="7">

// En el servidor
$parcial_1 = $_POST['p1'];
```

Si la vista usa `name="parcial_uno"`, pero el servidor busca `$_POST['p1']`, el servidor no encontrará ese valor.

El sufijo numérico indica una posición. No significa que `p1` sea una función ni que PHP sepa automáticamente qué es un parcial.

## 4. La posición de cada nota

En el dashboard docente, la lista:

```php
foreach (['p1', 'p2', 'p3'] as $campo)
```

significa: repetir el mismo HTML tres veces, una vez por cada nombre.

Es equivalente a escribir manualmente:

```php
<input name="p1">
<input name="p2">
<input name="p3">
```

La versión con `foreach` evita repetir código. La variable `$campo` va tomando estos valores:

```text
Primera vuelta:  $campo = p1
Segunda vuelta: $campo = p2
Tercera vuelta:  $campo = p3
```

El mismo patrón se usa para recuperatorios:

```php
foreach (['rec1', 'rec2', 'rec3'] as $campo) {
    // Aquí $campo cambia entre rec1, rec2 y rec3.
}
```

## 5. Cómo se transforma un recuperatorio

Archivo: `acciones/guardar_matriz.php`, dentro de `if ($accion === 'actualizar')`.

Primero se recopilan los seis campos:

```php
$campos_nota = ['p1', 'p2', 'p3', 'rec1', 'rec2', 'rec3'];
$notas = [];

foreach ($campos_nota as $campo) {
    $notas[$campo] = ($_POST[$campo] ?? '') !== ''
        ? $_POST[$campo]
        : null;
}
```

Aquí hay un arreglo asociativo:

```php
$notas['p1'];   // valor del primer parcial
$notas['rec1']; // valor del primer recuperatorio
```

El operador ternario tiene esta forma:

```php
$resultado = condicion ? valor_si_es_verdadero : valor_si_es_falso;
```

En este caso:

```php
// Si el campo no está vacío, conserva su valor.
// Si está vacío, guarda null.
```

Luego se elige la nota efectiva de cada posición:

```php
$nota_1 = $notas['rec1'] ?? $notas['p1'];
$nota_2 = $notas['rec2'] ?? $notas['p2'];
$nota_3 = $notas['rec3'] ?? $notas['p3'];
```

Esto significa:

```text
posición 1: usar rec1; si rec1 es null, usar p1
posición 2: usar rec2; si rec2 es null, usar p2
posición 3: usar rec3; si rec3 es null, usar p3
```

Ejemplo:

```text
p1 = 5       rec1 = 8       nota efectiva 1 = 8
p2 = 7       rec2 = vacío   nota efectiva 2 = 7
p3 = vacío   rec3 = vacío   nota efectiva 3 = vacío
```

Importante: `??` reemplaza cuando el valor es `null`, no simplemente cuando es cualquier valor falso.

## 6. Cómo se calcula el promedio

```php
$notas_validas = array_filter(
    [$nota_1, $nota_2, $nota_3],
    fn ($nota) => $nota !== null
);
```

- `[$nota_1, $nota_2, $nota_3]` crea una lista.
- `array_filter()` elimina los elementos que no cumplen una condición.
- `fn ($nota) => ...` es una función corta.
- `!==` significa “distinto, incluyendo el tipo”.

Después:

```php
$nota_regular = count($notas_validas) > 0
    ? number_format(array_sum($notas_validas) / count($notas_validas), 2)
    : null;
```

Ejemplo con `8` y `7`:

```text
array_sum([8, 7]) = 15
count([8, 7]) = 2
15 / 2 = 7.5
number_format(..., 2) = 7.50
```

No se divide por tres si falta una nota. Solo se cuentan las posiciones disponibles.

## 7. Por qué hay tres bloques `if ($accion ...)`

El mismo formulario puede enviar distintas acciones:

```php
<button name="accion" value="actualizar">Guardar</button>
<button name="accion" value="borrar">Borrar</button>
```

El servidor recibe un único valor según el botón presionado:

```php
$accion = $_POST['accion'] ?? '';
```

Después escoge el comportamiento:

```php
if ($accion === 'crear') {
    // INSERT: crea una fila nueva.
}

if ($accion === 'actualizar') {
    // UPDATE: modifica una fila existente.
}

if ($accion === 'borrar') {
    // DELETE: elimina una fila.
}
```

- `crear` usa `INSERT`.
- `actualizar` usa `UPDATE`.
- `borrar` usa `DELETE`.
- `===` compara valor y tipo exactamente.

## 8. Los signos `?` de SQL

En esta consulta:

```php
$stmt = $pdo->prepare(
    'UPDATE materias SET p1=?, p2=?, p3=? WHERE id=? AND docente_id=?'
);
$stmt->execute([$p1, $p2, $p3, $asig_id, $prof_id]);
```

Cada `?` recibe un valor en el mismo orden:

```text
primer ?  -> $p1
segundo ? -> $p2
tercer ?  -> $p3
cuarto ?  -> $asig_id
quinto ?  -> $prof_id
```

`prepare()` y `execute()` ayudan a enviar datos de forma segura y evitan construir SQL concatenando texto del usuario.

## 9. Por qué se usa `htmlspecialchars`

Cuando se muestra texto que viene de la base de datos:

```php
<?php echo htmlspecialchars($materia['nombre_materia']); ?>
```

`htmlspecialchars()` convierte caracteres especiales para que se muestren como texto y no como HTML ejecutable.

Ejemplo:

```text
Texto guardado: <b>Matemática</b>
Pantalla segura: &lt;b&gt;Matemática&lt;/b&gt;
```

## 10. Ejercicio de prueba

Busca una fila en `dashboard_docente.php` y prueba mentalmente estos valores:

```text
p1 = 4
p2 = 8
p3 = vacío
rec1 = 6
rec2 = vacío
rec3 = vacío
```

El resultado debe ser:

```text
nota_1 = 6
nota_2 = 8
nota_3 = vacío
nota_regular = 7.00
```

La razón es que el recuperatorio `rec1` reemplaza al parcial `p1`, mientras `p2` se mantiene y `p3` no participa porque no tiene nota.
