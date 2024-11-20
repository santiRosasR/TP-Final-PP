<?php
session_start();
include 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se recibió el ID del proyecto por URL
if (isset($_GET['proyecto_id'])) {
    $proyecto_id = $_GET['proyecto_id'];
} else {
    echo "No se especificó un proyecto.";
    exit();
}

// Obtener información del proyecto
$query = "SELECT nombre FROM proyectos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $proyecto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $proyecto = $result->fetch_assoc();
    $nombre_proyecto = $proyecto['nombre'];
} else {
    echo "Proyecto no encontrado.";
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Proyectos - Etapas</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function mostrarDetalleEtapa(nombre, propuesta, monto, fecha, archivo) {
            const modal = document.getElementById('detalle-etapa');
            document.getElementById('detalle-contenido').innerHTML = `
                <h3>Detalles de la Etapa</h3>
                <p><strong>Nombre:</strong> ${nombre}</p>
                <p><strong>Propuesta:</strong> ${propuesta}</p>
                <p><strong>Monto:</strong> $${monto}</p>
                <p><strong>Fecha:</strong> ${fecha}</p>
                <p><strong>Archivo:</strong> ${archivo ? '<a href="uploads/' + archivo + '" target="_blank">Ver Archivo</a>' : 'No disponible'}</p>
                <button class="close-button" onclick="cerrarDetalle()">Cerrar</button>
            `;
            modal.style.display = 'flex';
        }

        function cerrarDetalle() {
            document.getElementById('detalle-etapa').style.display = 'none';
        }
    </script>
</head>
<body>
<header>
    <h1>Seguimiento de Etapas de Proyectos</h1>
    <h2>Etapas del Proyecto: <?php echo $nombre_proyecto; ?></h2>
</header>

<div class="container">
    <?php if ($_SESSION['role'] == 'admin'): ?>
        <h2>Crear Nueva Etapa</h2>
        <form action='crear_etapa.php' method='POST' enctype="multipart/form-data">
            <input type='hidden' name='proyecto_id' value='<?php echo $proyecto_id; ?>'>
            <input type='text' name='nombre' placeholder='Nombre de la Etapa' required>
            <textarea name='propuesta' placeholder='Descripción de la Propuesta'></textarea>
            <input type='number' step='0.01' name='monto' placeholder='Monto' required>
            <input type='date' name='fecha' required>
            <input type="file" name="archivo">
            <button type='submit'>Crear Etapa</button>
        </form>
    <?php endif; ?>
</div>

<div class="container2">
    <h2>Etapas del Proyecto</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Propuesta</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener etapas del proyecto específico
            $query = "SELECT id, nombre, propuesta, monto, fecha, archivo 
                      FROM etapas 
                      WHERE proyecto_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $proyecto_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['nombre']}</td>
                            <td>{$row['propuesta']}</td>
                            <td>\${$row['monto']}</td>
                            <td>{$row['fecha']}</td>
                            <td>
                                <button onclick=\"mostrarDetalleEtapa(
                                    '{$row['nombre']}',
                                    '{$row['propuesta']}',
                                    '{$row['monto']}',
                                    '{$row['fecha']}',
                                    '{$row['archivo']}'
                                )\">Ver Detalle</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay etapas registradas para este proyecto</td></tr>";
            }
            $stmt->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Modal para mostrar detalles de la etapa -->
<div id="detalle-etapa" class="modal">
    <div class="modal-content" id="detalle-contenido">
        <button class="close-button" onclick="cerrarDetalle()">Cerrar</button>
    </div>
</div>

</body>
</html>
