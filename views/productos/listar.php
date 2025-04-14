<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CursosApp</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
  
  <div class="container">
    <div class="card mt-3">
      <div class="card-header">Lista de Cursos</div>
      <div class="card-body">
        <table class="table table-striped table-sm" id="tabla-cursos">
          <colgroup>
            <col style="width: 4%;">  <!-- ID -->
            <col style="width: 18%;"> <!-- Categoría -->
            <col style="width: 17%;"> <!-- Título -->
            <col style="width: 27%;"> <!-- Descripción -->
            <col style="width: 10%;"> <!-- Duración -->
            <col style="width: 7%;"> <!-- Nivel -->
            <col style="width: 7%;"> <!-- Precio -->
            <col style="width: 10%;"> <!-- Acciones -->
          </colgroup>
          <thead>
            <tr>
              <th>ID</th>
              <th>Categoría</th>
              <th>Estudiante</th>
              <th>Descripción</th>
              <th>Duración (h)</th>
              <th>Nivel</th>
              <th>Precio</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- Contenido de forma dinámica -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>

    const tabla = document.querySelector("#tabla-cursos tbody");

  
    function obtenerDatos() {
      fetch("../../app/controllers/CursoController.php?task=getAll", {
        method: 'GET'
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);  
        tabla.innerHTML = ``;
        data.forEach(element => {
          tabla.innerHTML += `
            <tr>
              <td>${element.id}</td>
              <td>${element.categoria}</td>
              <td>${element.titulo}</td>
              <td>${element.descripcion}</td>
              <td>${element.duracion_horas}</td>
              <td>${element.nivel}</td>
              <td>${element.precio}</td>
              <td>
                <a href='editar.php?id=${element.id}' title='Editar' class='btn btn-info btn-sm edit'><i class="fa-solid fa-pen"></i></a>
                <a href='#' title='Eliminar' data-idcurso='${element.id}' class='btn btn-danger btn-sm delete'><i class="fa-solid fa-trash"></i></a>
              </td>
            </tr>
          `;
        });
      })

      .catch(error => console.error(error));
    }

    document.addEventListener("DOMContentLoaded", () => {
      obtenerDatos();


      tabla.addEventListener("click", (event) => {
        const enlace = event.target.closest('a'); 

        if (enlace && enlace.classList.contains('delete')) {
          event.preventDefault();
          const idcurso = enlace.getAttribute('data-idcurso');
          
          if (confirm("¿Está seguro de eliminar el registro?")) {
            fetch(`../../app/controllers/CursoController.php?task=delete&id=${idcurso}`, { method: 'GET' })
              .then(response => response.json())
              .then(datos => {
                if (datos.filas > 0) {
                  const filaEliminar = enlace.closest('tr');
                  if (filaEliminar) { filaEliminar.remove(); }
                }
              })
              .catch(error => console.error(error));
          }
        }
      });
    });
  </script>

</body>
</html>
