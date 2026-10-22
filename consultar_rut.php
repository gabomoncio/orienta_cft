<?php
// 1. Desactivar salidas de error HTML de PHP para no romper el formato JSON
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

$servername = "sql308.infinityfree.com"; 
$username   = "if0_43239017"; 
$password   = "Gabo150395"; 
$dbname     = "if0_43239017_orienta_cft"; 

// 2. Intentar conexión silenciando advertencias HTML
$conn = @new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "exito" => false, 
        "mensaje" => "Error: InfinityFree no permite conexiones remotas a su MySQL."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$rut = isset($_GET['rut']) ? trim($_GET['rut']) : '';

if (empty($rut)) {
    echo json_encode(["exito" => false, "mensaje" => "No se ingresó ningún RUT."], JSON_UNESCAPED_UNICODE);
    $conn->close();
    exit;
}

$sql = "SELECT e.nombre_completo, s.nombre_sala, s.piso, a.asignatura 
        FROM Estudiantes e
        JOIN Asignaciones a ON e.rut = a.rut_estudiante
        JOIN Salas s ON a.id_sala = s.id_sala
        WHERE e.rut = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["exito" => false, "mensaje" => "Error al preparar la consulta."], JSON_UNESCAPED_UNICODE);
    $conn->close();
    exit;
}

$stmt->bind_param("s", $rut);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["exito" => true, "datos" => $row], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["exito" => false, "mensaje" => "El RUT ingresado no tiene salas asignadas."], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();
?>
