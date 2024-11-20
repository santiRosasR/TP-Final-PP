<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Etapas de Proyecto</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
    function mostrarDetalle(nombre, propuesta, monto, fecha) {
        const modal = document.getElementById('detalle-etapa');
        document.getElementById('detalle-contenido').innerHTML = `
            <h3>Detalles de la Etapa</h3>
            <p><strong>Nombre:</strong> ${nombre}</p>
            <p><strong>Propuesta:</strong> ${propuesta}</p>
            <p><strong>Monto:</strong> ${monto}</p>
            <p><strong>Fecha:</strong> ${fecha}</p>
            <button class="close-button" onclick="cerrarDetalle()">Cerrar</button>
            <button onclick="exportarPDF('${nombre}', '${propuesta}', '${monto}', '${fecha}')">Descargar PDF</button>
        `;
        modal.style.display = 'flex';
    }

    function cerrarDetalle() {
        document.getElementById('detalle-etapa').style.display = 'none';
    }

    function exportarPDF(nombre, propuesta, monto, fecha) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text(`Detalles de la Etapa: ${nombre}`, 10, 10);
        doc.text(`Propuesta: ${propuesta}`, 10, 20);
        doc.text(`Monto: ${monto}`, 10, 30);
        doc.text(`Fecha: ${fecha}`, 10, 40);

        // Guarda el PDF
        doc.save(`${nombre}_detalles.pdf`);
    }
    </script>
</head>
<body>
<header>
    <h1>Seguimiento de Etapas de Proyecto</h1>
</header>

<div class="container">
    <?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    if ($_SESSION['role'] == 'admin') {
        echo "<h2>Crear Etapa</h2>";
        echo "<form action='crear_etapa.php' method='POST'>
                <input type='text' name='nombre' placeholder='Nombre de la Etapa' required>
                <textarea name='propuesta' placeholder='Propuesta'></textarea>
                <input type='number' name='monto' placeholder='Monto' step='0.01' required>
                <input type='date' name='fecha' required>
                <label for='proyecto_id'>Proyecto:</label>
                <select name='proyecto_id' required>";

        $proyectos = $conn->query("SELECT id, nombre FROM proyectos");
        while ($proyecto = $proyectos->fetch_assoc()) {
            echo "<option value='{$proyecto['id']}'>{$proyecto['nombre']}</option>";
        }
        echo "</select>
              <button type='submit'>Crear Etapa</button>
              </form>";
    }
    ?>
</div>

<div class="container2">
    <h2>Etapas de los Proyectos</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre de la Etapa</th>
                <th>Propuesta</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT e.nombre, e.propuesta, e.monto, e.fecha, e.estado, p.nombre AS proyecto 
                      FROM etapas e
                      JOIN proyectos p ON e.proyecto_id = p.id";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['nombre']}</td>
                            <td>{$row['propuesta']}</td>
                            <td>{$row['monto']}</td>
                            <td>{$row['fecha']}</td>
                            <td>{$row['estado']}</td>
                            <td><button onclick=\"mostrarDetalle(
                                '{$row['nombre']}',
                                '{$row['propuesta']}',
                                '{$row['monto']}',
                                '{$row['fecha']}'
                            )\">Mostrar Detalles</button></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay etapas registradas</td></tr>";
            }

            $stmt->close();
            ?>
        </tbody>
    </table>
</div>

<div id="detalle-etapa" class="modal">
    <div class="modal-content" id="detalle-contenido">
        <button class="close-button" onclick="cerrarDetalle()">Cerrar</button>
    </div>
</div>

</body>
</html>
