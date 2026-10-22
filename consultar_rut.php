<?php
// Muestra errores en pantalla para ayudarnos a depurar si algo falla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');


$host = "dpg-dbcoc4mq1p3s73cpbm4g-a"; 
$port = "5432";
$user = "orienta_cft_db_user"; 
$password = "AXabCkdEaSE2Agr68BgzUCf3FMGlAMwX"; 

try {

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


    $rut = isset($_GET['rut']) ? $_GET['rut'] : '';

    if (empty($rut)) {
        echo json_encode(["exito" => false, "mensaje" => "No se ingresó ningún RUT."]);
        exit;
    }


    $sql = "SELECT e.nombre_completo, s.nombre_sala, s.piso, a.asignatura 
            FROM Estudiantes e
            JOIN Asignaciones a ON e.rut = a.rut_estudiante
            JOIN Salas s ON a.id_sala = s.id_sala
            WHERE e.rut = :rut";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rut', $rut);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode(["exito" => true, "datos" => $row]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "El RUT ingresado no tiene salas asignadas."]);
    }

} catch (PDOException $e) {
    // Si hay un error de conexión
    echo json_encode(["exito" => false, "mensaje" => "Error de conexión: " . $e->getMessage()]);
}
?>
