<?php
header('Content-Type: application/json'); 


if (isset($_GET['task'])) {

    
    if ($_GET['task'] == 'getAll') {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
            $sql = "SELECT cursos.id, categorias.categoria AS categoria, cursos.titulo, cursos.descripcion, 
                           cursos.duracion_horas, cursos.nivel, cursos.precio 
                    FROM cursos 
                    LEFT JOIN categorias ON cursos.id_categoria = categorias.id";
            $stmt = $pdo->query($sql);

            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($cursos);

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

    
    if ($_GET['task'] == 'getByCategory' && isset($_GET['id_categoria'])) {
        try {
            $id_categoria = $_GET['id_categoria'];
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
            $sql = "SELECT cursos.id, categorias.categoria AS categoria, cursos.titulo, cursos.descripcion, 
                           cursos.duracion_horas, cursos.nivel, cursos.precio 
                    FROM cursos 
                    LEFT JOIN categorias ON cursos.id_categoria = categorias.id 
                    WHERE cursos.id_categoria = :id_categoria";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);

            $stmt->execute();
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($cursos);

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

    
    if ($_GET['task'] == 'getCategorias') {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT id, categoria FROM categorias";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

    
    if ($_GET['task'] == 'getCursoById' && isset($_GET['id'])) {
        try {
            $id = $_GET['id'];
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM cursos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $curso = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($curso);

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

   
    if ($_GET['task'] == 'delete' && isset($_GET['id'])) {
        try {
            $id = $_GET['id'];
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "DELETE FROM cursos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            echo json_encode(["filas" => $stmt->execute() ? 1 : 0]);

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

   
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['task'] == 'update') {
        $datos = json_decode(file_get_contents('php://input'), true);

        if (!isset($datos['id'], $datos['id_categoria'], $datos['titulo'], $datos['descripcion'], $datos['duracion_horas'], $datos['precio'], $datos['nivel'], $datos['fecha_inicio'])) {
            echo json_encode(["error" => "Datos incompletos o inválidos"]);
            exit;
        }

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE cursos SET 
                        id_categoria = :id_categoria, 
                        titulo = :titulo, 
                        descripcion = :descripcion, 
                        duracion_horas = :duracion_horas, 
                        precio = :precio, 
                        nivel = :nivel, 
                        fecha_inicio = :fecha_inicio 
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_categoria', $datos['id_categoria']);
            $stmt->bindParam(':titulo', $datos['titulo']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':duracion_horas', $datos['duracion_horas']);
            $stmt->bindParam(':precio', $datos['precio']);
            $stmt->bindParam(':nivel', $datos['nivel']);
            $stmt->bindParam(':fecha_inicio', $datos['fecha_inicio']);
            $stmt->bindParam(':id', $datos['id']);

            echo json_encode(["filas" => $stmt->execute() ? 1 : 0]);

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

   
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['task'] == 'add') {
        $datos = json_decode(file_get_contents('php://input'), true);

        if (!isset($datos['id_categoria'], $datos['titulo'], $datos['descripcion'], $datos['duracion_horas'], $datos['precio'], $datos['nivel'])) {
            echo json_encode(["error" => "Datos incompletos o inválidos"]);
            exit;
        }

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=cursosdb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO cursos (id_categoria, titulo, descripcion, duracion_horas, precio, nivel, fecha_inicio) 
                    VALUES (:id_categoria, :titulo, :descripcion, :duracion_horas, :precio, :nivel, NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_categoria', $datos['id_categoria']);
            $stmt->bindParam(':titulo', $datos['titulo']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':duracion_horas', $datos['duracion_horas']);
            $stmt->bindParam(':precio', $datos['precio']);
            $stmt->bindParam(':nivel', $datos['nivel']);

            echo json_encode(["filas" => $stmt->execute() ? 1 : 0]);

        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al conectar con la base de datos: " . $e->getMessage()]);
        }
        exit;
    }

} else {

    echo json_encode(["error" => "No se especificó la tarea a realizar."]);
}
