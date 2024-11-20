<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdf']) && isset($_POST['etapa_id'])) {
    $etapa_id = $_POST['etapa_id'];
    $pdf = $_FILES['pdf'];

    // Verificar que el archivo es un PDF
    $allowed_types = ['application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    if ($pdf['error'] === 0) {
        if (in_array($pdf['type'], $allowed_types)) {
            if ($pdf['size'] <= $max_size) {
                // Definir la carpeta para almacenar los archivos PDF
                $upload_dir = 'uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_path = $upload_dir . basename($pdf['name']);
                if (move_uploaded_file($pdf['tmp_name'], $file_path)) {
                    // Actualizar la base de datos con la ruta del archivo PDF
                    $sql = "UPDATE etapas SET archivo_pdf = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $file_path, $etapa_id);
                    if ($stmt->execute()) {
                        echo "Archivo PDF subido correctamente.";
                    } else {
                        echo "Error al actualizar la base de datos.";
                    }
                    $stmt->close();
                } else {
                    echo "Error al mover el archivo.";
                }
            } else {
                echo "El archivo excede el tamaño máximo permitido.";
            }
        } else {
            echo "Solo se permiten archivos PDF.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
}

$conn->close();
?>
