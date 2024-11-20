<?php
session_start();
include 'db.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contrato = $_POST['contrato'];
    $propuesta = $_POST['propuesta'];
    $monto = $_POST['monto'];
    $usuario_id = $_POST['usuario_id'];

    // Insertar el nuevo proyecto
    $sql = "INSERT INTO proyectos (nombre, contrato, propuesta, monto, estado) VALUES (?, ?, ?, ?, 'pendiente')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $nombre, $contrato, $propuesta, $monto);

    if ($stmt->execute()) {
        $proyecto_id = $stmt->insert_id;

        // Asignar el proyecto al usuario
        $asignar_sql = "INSERT INTO proyectos_asignados (proyecto_id, usuario_id, variables) VALUES (?, ?, NULL)";
        $asignar_stmt = $conn->prepare($asignar_sql);
        $asignar_stmt->bind_param("ii", $proyecto_id, $usuario_id);
        
        if ($asignar_stmt->execute()) {
            echo "Proyecto creado y asignado con éxito.";
        } else {
            echo "Error al asignar el proyecto: " . $conn->error;
        }

        $asignar_stmt->close();
    } else {
        echo "Error al crear el proyecto: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
