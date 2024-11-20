<?php
session_start();
include 'db.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirigir a login si no está logueado
    exit();
}

// Si el usuario es un admin, puede ver y modificar proyectos
if ($_SESSION['role'] == 'admin') {

    // Crear proyecto (si el admin lo solicita)
    if (isset($_POST['crear_proyecto'])) {
        $nombre = $_POST['nombre'];
        $contrato = $_POST['contrato'];
        $propuesta = $_POST['propuesta'];
        $monto = $_POST['monto'];
        $estado = $_POST['estado'];

        // Insertar nuevo proyecto en la base de datos
        $query = "INSERT INTO proyectos (nombre, contrato, propuesta, monto, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssis", $nombre, $contrato, $propuesta, $monto, $estado);
        $stmt->execute();
        $stmt->close();
    }

    // Modificar un proyecto (si el admin lo solicita)
    if (isset($_POST['modificar_proyecto'])) {
        $proyecto_id = $_POST['proyecto_id'];
        $nombre = $_POST['nombre'];
        $contrato = $_POST['contrato'];
        $propuesta = $_POST['propuesta'];
        $monto = $_POST['monto'];
        $estado = $_POST['estado'];

        $query = "UPDATE proyectos SET nombre = ?, contrato = ?, propuesta = ?, monto = ?, estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $nombre, $contrato, $propuesta, $monto, $estado, $proyecto_id);
        $stmt->execute();
        $stmt->close();
    }

    // Asignar proyecto a un usuario
    if (isset($_POST['asignar_proyecto'])) {
        $proyecto_id = $_POST['proyecto_id'];
        $usuario_id = $_POST['usuario_id'];

        $query = "INSERT INTO proyectos_asignados (proyecto_id, usuario_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $proyecto_id, $usuario_id);
        $stmt->execute();
        $stmt->close();
    }

} else {
    // Si es un usuario regular, solo puede ver los proyectos asignados
    $user_id = $_SESSION['user_id'];
}

// Obtener proyectos asignados
if ($_SESSION['role'] == 'admin') {
    $query = "SELECT p.id, p.nombre, p.contrato, p.propuesta, p.monto, p.estado, u.username AS asignado_a 
              FROM proyectos p
              LEFT JOIN proyectos_asignados pa ON p.id = pa.proyecto_id
              LEFT JOIN usuarios u ON pa.usuario_id = u.id";
} else {
    $query = "SELECT p.id, p.nombre, p.contrato, p.propuesta, p.monto, p.estado 
              FROM proyectos p
              JOIN proyectos_asignados pa ON p.id = pa.proyecto_id
              WHERE pa.usuario_id = ?";
}

$stmt = $conn->prepare($query);
if ($_SESSION['role'] != 'admin') {
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para filtrar proyectos en la tabla
        function buscarProyectos() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("buscar");
            filter = input.value.toUpperCase();
            table = document.getElementById("tabla-proyectos");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Empieza en 1 para evitar la cabecera
                td = tr[i].getElementsByTagName("td");
                var encontrado = false;
                // Revisar todas las celdas
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            encontrado = true;
                            break; // No es necesario seguir buscando si ya se encontró
                        }
                    }
                }
                if (encontrado) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Proyectos Asignados</h2>

        <!-- Campo de búsqueda -->
        <input type="text" id="buscar" placeholder="Buscar proyectos..." onkeyup="buscarProyectos()">

        <!-- Tabla de proyectos -->
        <table id="tabla-proyectos">
            <thead>
                <tr>
                    <th>Nombre del Proyecto</th>
                    <th>Contrato</th>
                    <th>Propuesta</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Asignado a</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nombre']}</td>
                                <td>{$row['contrato']}</td>
                                <td>{$row['propuesta']}</td>
                                <td>{$row['monto']}</td>
                                <td>{$row['estado']}</td>";
                        if ($_SESSION['role'] == 'admin') {
                            echo "<td>{$row['asignado_a']}</td>";
                        }
                        echo "<td>
                                <a href='etapas.php?proyecto_id={$row['id']}' class='btn'>Ver Etapas</a>
                              </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay proyectos asignados</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Formulario para crear proyecto (solo visible para admins) -->
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <h2>Crear Proyecto</h2>
            <form action="index.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre del Proyecto" required>
                <input type="text" name="contrato" placeholder="Contrato" required>
                <input type="text" name="propuesta" placeholder="Propuesta" required>
                <input type="number" name="monto" placeholder="Monto" required>
                <select name="estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Aprobado">Aprobado</option>
                    <option value="En Progreso">En Progreso</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
                <button type="submit" name="crear_proyecto">Crear Proyecto</button>
            </form>

            <h2>Modificar Proyecto</h2>
            <form action="index.php" method="POST">
                <select name="proyecto_id" required>
                    <option value="">Seleccionar Proyecto</option>
                    <?php
                    // Listar proyectos disponibles para modificar
                    $query = "SELECT id, nombre FROM proyectos";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $proyectos = $stmt->get_result();
                    while ($proyecto = $proyectos->fetch_assoc()) {
                        echo "<option value='{$proyecto['id']}'>{$proyecto['nombre']}</option>";
                    }
                    ?>
                </select>

                <input type="text" name="nombre" placeholder="Nuevo Nombre del Proyecto" required>
                <input type="text" name="contrato" placeholder="Nuevo Contrato" required>
                <input type="text" name="propuesta" placeholder="Nueva Propuesta" required>
                <input type="number" name="monto" placeholder="Nuevo Monto" required>
                <select name="estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Aprobado">Aprobado</option>
                    <option value="En Progreso">En Progreso</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
                <button type="submit" name="modificar_proyecto">Modificar Proyecto</button>
            </form>

            <h2>Asignar Proyecto a Usuario</h2>
            <form action="index.php" method="POST">
                <select name="proyecto_id" required>
                    <option value="">Seleccionar Proyecto</option>
                    <?php
                    // Listar proyectos disponibles para asignar
                    $query = "SELECT id, nombre FROM proyectos";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $proyectos = $stmt->get_result();
                    while ($proyecto = $proyectos->fetch_assoc()) {
                        echo "<option value='{$proyecto['id']}'>{$proyecto['nombre']}</option>";
                    }
                    ?>
                </select>

                <select name="usuario_id" required>
                    <option value="">Seleccionar Usuario</option>
                    <?php
                    // Listar usuarios disponibles para asignar proyectos
                    $query = "SELECT id, username FROM usuarios";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $usuarios = $stmt->get_result();
                    while ($usuario = $usuarios->fetch_assoc()) {
                        echo "<option value='{$usuario['id']}'>{$usuario['username']}</option>";
                    }
                    ?>
                </select>

                <button type="submit" name="asignar_proyecto">Asignar Proyecto</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
