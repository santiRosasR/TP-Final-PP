<?php
include 'db.php';

// Verificar si hay un ID en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener datos del proyecto
    $sql = "SELECT * FROM proyectos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $proyecto = $result->fetch_assoc();
    } else {
        echo "Proyecto no encontrado.";
        exit();
    }
}

// Procesar formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $contrato = $_POST['contrato'];
    $propuesta = $_POST['propuesta'];
    $monto = $_POST['monto'];
    $estado = $_POST['estado'];

    $sql = "UPDATE proyectos SET 
            nombre = '$nombre', 
            contrato = '$contrato', 
            propuesta = '$propuesta', 
            monto = $monto, 
            estado = '$estado' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error al actualizar el proyecto: " . $conn->error;
    }
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Proyecto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Modificar Proyecto</h1>
    <form action="modificar_proyecto.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $proyecto['id']; ?>">

        <label>Nombre del Proyecto</label>
        <input type="text" name="nombre" value="<?php echo $proyecto['nombre']; ?>" required>

        <label>Contrato</label>
        <textarea name="contrato" required><?php echo $proyecto['contrato']; ?></textarea>

        <label>Propuesta</label>
        <textarea name="propuesta" required><?php echo $proyecto['propuesta']; ?></textarea>

        <label>Monto</label>
        <input type="number" name="monto" value="<?php echo $proyecto['monto']; ?>" required>

        <label>Estado</label>
        <select name="estado">
            <option value="pendiente" <?php if ($proyecto['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
            <option value="en_proceso" <?php if ($proyecto['estado'] == 'en_proceso') echo 'selected'; ?>>En Proceso</option>
            <option value="aprobado" <?php if ($proyecto['estado'] == 'aprobado') echo 'selected'; ?>>Aprobado</option>
        </select>

        <button type="submit">Guardar Cambios</button>
        <a href="index.php" class="button">Cancelar</a>
    </form>
</div>
</body>
</html>
