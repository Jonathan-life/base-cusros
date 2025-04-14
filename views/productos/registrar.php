<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Curso</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
  
  <div class="container">
    <form action="" autocomplete="off" id="formulario-registro-curso">
      <div class="card mt-3">
        <div class="card-header bg-primary text-light">Registro de curso</div>
        <div class="card-body">
          
        <div class="form-floating mb-2">
          <select name="id_categoria" id="categorias" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="1">Programación</option>
            <option value="2">Matemáticas</option>
            <option value="3">Ciencias Sociales</option>
            <option value="4">Arte</option>
          </select>
          <label for="categorias">Categoría</label>
        </div>


          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="titulo" placeholder="Alumno" required>
            <label for="titulo">Alumno</label>
          </div>


          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="descripcion" placeholder="Descripción" required>
            <label for="descripcion">Descripción</label>
          </div>

          <div class="row g-2">
            <div class="col">
              <div class="form-floating mb-2">
                <input type="number" class="form-control text-end" id="duracion_horas" placeholder="Duración (horas)" required>
                <label for="duracion_horas">Duración (horas)</label>
              </div>
            </div>
            <div class="col">
              <div class="form-floating mb-2">
                <input type="text" class="form-control text-end" id="precio" placeholder="Precio" required>
                <label for="precio">Precio</label>
              </div>
            </div>
          </div>

          <div class="form-floating">
            <select name="nivel" id="nivel" class="form-select">
              <option value="Principiante" selected>Principiante</option>
              <option value="Intermedio">Intermedio</option>
              <option value="Avanzado">Avanzado</option>
            </select>
            <label for="nivel">Nivel del curso</label>
          </div>

        </div>
        <div class="card-footer text-end">
          <button class="btn btn-sm btn-primary" type="submit">Guardar</button>
          <button class="btn btn-sm btn-secondary" type="reset">Cancelar</button>
        </div>
      </div>
    </form>
  </div>

  <!-- JavaScript -->
  <script>
    const formulario = document.querySelector("#formulario-registro-curso");

    function registrarCurso() {
      const datos = {
        id_categoria: document.querySelector("#categorias").value,
        titulo: document.querySelector("#titulo").value,
        descripcion: document.querySelector("#descripcion").value,
        duracion_horas: parseInt(document.querySelector("#duracion_horas").value),
        precio: parseFloat(document.querySelector("#precio").value),
        nivel: document.querySelector("#nivel").value,
        fecha_inicio: new Date().toISOString()
      };

      // Validación de datos
      if (!datos.id_categoria || !datos.titulo || !datos.descripcion || isNaN(datos.duracion_horas) || isNaN(datos.precio)) {
        alert("⚠️ Todos los campos son obligatorios y deben ser válidos.");
        return;
      }
      fetch(`../../app/controllers/CursoController.php?task=add`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.filas > 0) {
          formulario.reset();
          alert("✅ Curso registrado correctamente.");
        } else {
          alert("⚠️ Hubo un error al registrar el curso.");
        }
      })
      .catch(error => {
        console.error(error);
        alert("❌ Error en el servidor. Intente nuevamente.");
      });
    }

    formulario.addEventListener("submit", function (event) {
      event.preventDefault();
      if (confirm("¿Está seguro de registrar este curso?")) {
        registrarCurso();
      }
    });
  </script>

</body>
</html>
