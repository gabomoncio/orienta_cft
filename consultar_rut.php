<?php
header('Content-Type: application/json; charset=utf-8');

$servername = "sql308.infinityfree.com"; 
$username = "if0_43239017"; 
$password = "Gabo150395"; 
$dbname = "if0_43239017_orienta_cft"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["exito" => false, "mensaje" => "Error de conexión a la base de datos."]));
}


$rut = isset($_GET['rut']) ? $_GET['rut'] : '';

if (empty($rut)) {
    echo json_encode(["exito" => false, "mensaje" => "No se ingresó ningún RUT."]);
    $conn->close();
    exit;
}


$sql = "SELECT e.nombre_completo, s.nombre_sala, s.piso, a.asignatura 
        FROM Estudiantes e
        JOIN Asignaciones a ON e.rut = a.rut_estudiante
        JOIN Salas s ON a.id_sala = s.id_sala
        WHERE e.rut = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rut);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si encuentra el RUT, devuelve los datos en formato JSON
    $row = $result->fetch_assoc();
    echo json_encode(["exito" => true, "datos" => $row]);
} else {
    // Si no lo encuentra
    echo json_encode(["exito" => false, "mensaje" => "El RUT ingresado no tiene salas asignadas."]);
}

$stmt->close();
$conn->close();
?>