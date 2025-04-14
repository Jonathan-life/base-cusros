<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Curso</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <form action="" autocomplete="off" id="formulario-curso">
      <div class="card mt-3">
        <div class="card-header bg-primary text-white">Editar Curso</div>
        <div class="card-body">

          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="titulo" placeholder="Título del curso" required>
            <label for="titulo">Título</label>
          </div>

          <div class="form-floating mb-2">
            <textarea class="form-control" id="descripcion" placeholder="Descripción" required style="height: 100px"></textarea>
            <label for="descripcion">Descripción</label>
          </div>

          <div class="row g-2">
            <div class="col-md-4">
              <div class="form-floating mb-2">
                <input type="number" class="form-control" id="duracion_horas" placeholder="Duración en horas" required>
                <label for="duracion_horas">Duración (horas)</label>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-floating mb-2">
                <select id="nivel" class="form-select" required>
                  <option value="">Seleccione</option>
                  <option value="Básico">Básico</option>
                  <option value="Intermedio">Intermedio</option>
                  <option value="Avanzado">Avanzado</option>
                </select>
                <label for="nivel">Nivel</label>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-floating mb-2">
                <input type="number" class="form-control" id="precio" placeholder="Precio" required step="0.01">
                <label for="precio">Precio</label>
              </div>
            </div>
          </div>

          <div class="row g-2">
            <div class="col-md-6">
              <div class="form-floating mb-2">
                <input type="date" class="form-control" id="fecha_inicio" required>
                <label for="fecha_inicio">Fecha de Inicio</label>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-floating mb-2">
                <select id="id_categoria" class="form-select" required>
                  <option value="">Seleccione categoría</option>
                 
                </select>
                <label for="id_categoria">Categoría</label>
              </div>
            </div>
          </div>

        </div>
        <div class="card-footer text-end">
          <button class="btn btn-sm btn-primary" type="submit">Actualizar</button>
          <button class="btn btn-sm btn-secondary" type="reset">Cancelar</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      
      fetch("../../app/controllers/CursoController.php?task=getCategorias", { method: 'GET' })
        .then(res => res.json())
        .then(data => {
          const categoriaSelect = document.getElementById("id_categoria");
          data.forEach(categoria => {
            const option = document.createElement("option");
            option.value = categoria.id;
            option.textContent = categoria.categoria;
            categoriaSelect.appendChild(option);
          });
        })
        .catch(error => console.error("Error al cargar las categorías:", error));

      const URL = new URLSearchParams(window.location.search);
      const id = URL.get('id');

      if (id) {
        fetch(`../../app/controllers/CursoController.php?task=getCursoById&id=${id}`, { method: 'GET' })
          .then(res => res.json())
          .then(data => {
            document.getElementById("titulo").value = data.titulo;
            document.getElementById("descripcion").value = data.descripcion;
            document.getElementById("duracion_horas").value = data.duracion_horas;
            document.getElementById("nivel").value = data.nivel;
            document.getElementById("precio").value = data.precio;
            document.getElementById("fecha_inicio").value = data.fecha_inicio;
            document.getElementById("id_categoria").value = data.id_categoria;

            
            const categoriaSelect = document.getElementById("id_categoria");
            categoriaSelect.value = data.id_categoria; 
          })
          .catch(error => console.error("Error al obtener el curso:", error));
      }

      document.getElementById("formulario-curso").addEventListener("submit", function(e) {
        e.preventDefault();

        const id = URL.get('id');
        if (!id) {
          alert("ID del curso no especificado.");
          return;
        }

        const datos = {
          id: id,
          titulo: document.getElementById("titulo").value.trim(),
          descripcion: document.getElementById("descripcion").value.trim(),
          duracion_horas: document.getElementById("duracion_horas").value,
          nivel: document.getElementById("nivel").value,
          precio: document.getElementById("precio").value,
          fecha_inicio: document.getElementById("fecha_inicio").value,
          id_categoria: document.getElementById("id_categoria").value
        };

        if (!datos.titulo || !datos.descripcion || !datos.duracion_horas || !datos.nivel || !datos.precio || !datos.fecha_inicio || !datos.id_categoria) {
          alert("Por favor, complete todos los campos.");
          return;
        }

        fetch(`../../app/controllers/CursoController.php?task=update`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(data => {
          if (data.filas === 1) {
            alert("Curso actualizado correctamente");
            window.location.href = "listar.php"; 
          } else {
            alert("No se pudo actualizar el curso.");
            console.error(data);
          }
        })
        .catch(error => {
          alert("Error al actualizar el curso.");
          console.error("Error:", error);
        });
      });
    });
  </script>

</body>
</html>
