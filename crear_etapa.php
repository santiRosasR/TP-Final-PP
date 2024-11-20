<?php
session_start();
include 'db.php';  // Asegúrate de que la conexión a la base de datos sea correcta

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $propuesta = $_POST['propuesta'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $proyecto_id = $_POST['proyecto_id'];
    
    // Validar si los campos no están vacíos
    if (empty($nombre) || empty($propuesta) || empty($monto) || empty($fecha) || empty($proyecto_id)) {
        die("Todos los campos son obligatorios.");
    }

    // Subir el archivo si existe
    $archivo_subido = null;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        // Definir la carpeta donde se guardarán los archivos
        $carpeta_destino = 'uploads/';
        
        // Verificar si la carpeta 'uploads/' existe
        if (!is_dir($carpeta_destino)) {
            // Si no existe, intenta crearla
            if (!mkdir($carpeta_destino, 0777, true)) {
                die("Error al crear el directorio de carga de archivos.");
            }
        }
        
        // Definir la ruta del archivo
        $archivo_nombre = basename($_FILES['archivo']['name']);
        $archivo_ruta = $carpeta_destino . $archivo_nombre;

        // Mover el archivo a la carpeta destino
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo_ruta)) {
            $archivo_subido = $archivo_nombre;  // Guardamos solo el nombre del archivo
        } else {
            die("Error al subir el archivo.");
        }
    }

    // Insertar la etapa en la base de datos, incluyendo el archivo (si existe)
    $sql = "INSERT INTO etapas (nombre, propuesta, monto, fecha, proyecto_id, estado, archivo) 
            VALUES (?, ?, ?, ?, ?, 'pendiente', ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error); // Verifica si la preparación falla
    }

    // Bind de parámetros: string, string, decimal, date, int, string (archivo)
    $stmt->bind_param("ssdsds", $nombre, $propuesta, $monto, $fecha, $proyecto_id, $archivo_subido);
    
    if ($stmt->execute()) {
        echo "Etapa creada con éxito.";
    } else {
        echo "Error al crear la etapa: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
