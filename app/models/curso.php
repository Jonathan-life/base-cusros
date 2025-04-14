<?php
class Curso {
  private $pdo;

  public function __construct() {
    $this->pdo = $this->connect();
  }

  private function connect() {
    try {
      $pdo = new PDO("mysql:host=localhost;dbname=cursosdb;charset=utf8", "root", "");
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }
  }

  // Obtener todos los cursos (vista)
  public function getAll() {
    try {
      $sql = "SELECT * FROM vista_cursos_todos";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return ["error" => $e->getMessage()];
    }
  }

  // Obtener un curso por ID
  public function getById($id) {
    try {
      $sql = "SELECT * FROM vista_cursos_todos WHERE id = ?";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return ["error" => $e->getMessage()];
    }
  }

  // Agregar un nuevo curso usando el procedimiento almacenado
  public function add($data) {
    try {
      $stmt = $this->pdo->prepare("CALL spu_cursos_registrar(?, ?, ?, ?, ?, ?, ?)");

      $stmt->bindParam(1, $data['id_categoria'], PDO::PARAM_INT);
      $stmt->bindParam(2, $data['titulo'], PDO::PARAM_STR);
      $stmt->bindParam(3, $data['descripcion'], PDO::PARAM_STR); // Asegúrate de tener este campo en la tabla y procedimiento
      $stmt->bindParam(4, $data['duracion_horas'], PDO::PARAM_INT);
      $stmt->bindParam(5, $data['nivel'], PDO::PARAM_STR);
      $stmt->bindParam(6, $data['precio']);
      $stmt->bindParam(7, $data['fecha_inicio']);

      $stmt->execute();

      return $stmt->rowCount(); // Retorna filas afectadas
    } catch (PDOException $e) {
      return ["error" => $e->getMessage()];
    }
  }

  // Eliminar un curso por ID
  public function delete($data) {
    try {
      $sql = "DELETE FROM cursos WHERE id = ?";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$data['id']]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      return ["error" => $e->getMessage()];
    }
  }
}
?>
