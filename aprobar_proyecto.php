<?php
include 'db.php';

$id = $_GET['id'];
$sql = "UPDATE proyectos SET estado='aprobado', fecha_aprobacion=NOW() WHERE id=$id";
$conn->query($sql);

$conn->close();
?>

