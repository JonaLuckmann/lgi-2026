# Sistema Estudiantes Mejorado

Esta carpeta es una versión paralela para estudiar la interfaz sin modificar `sistema_estudiantes`.

Para estudiar el código paso a paso, consulta [GUIA_PRINCIPIANTES.md](GUIA_PRINCIPIANTES.md).

## Organización

- `index.php`: inicia la sesión, procesa el login y decide qué dashboard mostrar.
- `config/`: conexión a la base de datos.
- `acciones/`: archivos que reciben formularios y modifican datos.
- `includes/`: piezas compartidas, como encabezado y pie.
- `vistas/`: HTML/PHP de cada pantalla.
- `css/`: diseño visual responsive.
- `js/`: comportamiento del navegador.

## Sufijos y nombres importantes

- `$_POST['nombre']`: busca el campo enviado cuyo atributo `name="nombre"` coincide.
- `$_GET['buscar']`: lee datos enviados en la URL, por ejemplo `?buscar=matematica`.
- `name="p1"`, `p2`, `p3`: representan los tres parciales.
- `name="rec1"`, `rec2`, `rec3`: representan los tres recuperatorios.
- `name="accion" value="actualizar"`: indica al servidor qué operación debe realizar.
- `method="POST"`: envía datos en el cuerpo de la solicitud, no en la URL.
- `action="acciones/guardar_matriz.php"`: indica qué archivo recibe el formulario.
- `??`: usa un valor alternativo si una variable no existe o es `null`.
- `isset(...)`: comprueba si una variable o clave existe.
- `htmlspecialchars(...)`: evita interpretar texto de usuario como HTML.

## Cálculo de nota

Para cada posición se usa el recuperatorio si existe; en caso contrario se usa el parcial:

`nota 1 = rec1 ?? p1`, `nota 2 = rec2 ?? p2`, `nota 3 = rec3 ?? p3`.

Luego se calcula el promedio de las notas disponibles. Esta demo mantiene la lógica de la versión original para facilitar la comparación.
