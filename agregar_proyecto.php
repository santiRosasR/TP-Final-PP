<?php
include 'db.php';

// Validar que todos los datos requeridos están presentes
if (isset($_POST['nombre'], $_POST['contrato'], $_POST['propuesta'], $_POST['monto'], $_POST['estado'])) {
    $nombre = $_POST['nombre'];
    $contrato = $_POST['contrato'];
    $propuesta = $_POST['propuesta'];
    $monto = (float)$_POST['monto'];
    $estado = $_POST['estado'];

    // Asignar la fecha de aprobación solo si el estado es "aprobado"
    $fecha_aprobacion = ($estado === 'aprobado') ? date("Y-m-d H:i:s") : null;

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conn->prepare("INSERT INTO proyectos (nombre, contrato, propuesta, monto, estado, fecha_de_creacion, fecha_de_aprobacion) 
                            VALUES (?, ?, ?, ?, ?, NOW(), ?)");
    
    // Aquí utilizamos 's' para strings y 'd' para el monto decimal, y 's' para la fecha_aprobacion
    $stmt->bind_param("sssdss", $nombre, $contrato, $propuesta, $monto, $estado, $fecha_aprobacion);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Proyecto creado con éxito.";
    } else {
        echo "Error al crear el proyecto: " . $stmt->error;
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo "Error: Faltan datos requeridos para crear el proyecto.";
}

// Cerrar la conexión
$conn->close();
?>
