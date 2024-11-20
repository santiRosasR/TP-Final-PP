<?php
include 'db.php';

$id = $_GET['id'];
$sql = "DELETE FROM proyectos WHERE id=$id";
$conn->query($sql);

$conn->close();
?>

