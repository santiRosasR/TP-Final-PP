<?php
include 'db.php';

$sql = "SELECT * FROM proyectos";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["nombre"] . "</td>";
    echo "<td>" . $row["contrato"] . "</td>";
    echo "<td>" . $row["propuesta"] . "</td>";
    echo "<td>" . $row["monto"] . "</td>";
    echo "<td><span class='status status-" . $row["estado"] . "'></span></td>";
    echo "<td><button class='aprobar' onclick='aprobarProyecto(" . $row["id"] . ")'>Aprobar</button>";
    echo "<button class='eliminar' onclick='eliminarProyecto(" . $row["id"] . ")'>Eliminar</button>";
    echo "<button class='modificar' onclick='modificarProyecto(" . $row["id"] . ")'>Modificar</button></td>";
    echo "</tr>";
}
$conn->close();
?>

