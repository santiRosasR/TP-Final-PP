<?php
session_start();
include 'db.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $proyecto_id = $_POST['proyecto_id'];
    $usuario_id = $_POST['usuario_id'];

    // Asignar el proyecto al usuario (sin columna 'variables')
    $sql = "INSERT INTO proyectos_asignados (proyecto_id, usuario_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $proyecto_id, $usuario_id);

    if ($stmt->execute()) {
        echo "Proyecto asignado con éxito.";
    } else {
        echo "Error al asignar el proyecto: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
