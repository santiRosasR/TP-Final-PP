document.addEventListener("DOMContentLoaded", () => {
    cargarProyectos();
});

function cargarProyectos() {
    fetch('mostrar_proyectos.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById("tabla-proyectos").innerHTML = data;
        });
}

function aprobarProyecto(id) {
    fetch(`aprobar_proyecto.php?id=${id}`)
        .then(response => response.text())
        .then(() => {
            cargarProyectos();
        });
}

function eliminarProyecto(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este proyecto?")) {
        fetch(`eliminar_proyecto.php?id=${id}`)
            .then(response => response.text())
            .then(() => {
                cargarProyectos();
            });
    }
}

function modificarProyecto(id) {
    // Lógica para modificar el proyecto, que redirige a un formulario de edición
    window.location.href = `modificar_proyecto.php?id=${id}`;
}
