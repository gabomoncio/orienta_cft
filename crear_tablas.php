<?php
// Muestra errores en pantalla para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);


$host = "dpg-dbcoc4mq1p3s73cpbm4g-a"; 
$port = "5432";
$dbname = "orienta_cft_db"; 
$user = "orienta_cft_db_user"; 
$password = "AXabCkdEaSE2Agr68BgzUCf3FMGlAMwX"; 

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $sql = "
        CREATE TABLE IF NOT EXISTS Estudiantes (
            rut VARCHAR(15) PRIMARY KEY,
            nombre_completo VARCHAR(100) NOT NULL,
            carrera VARCHAR(100) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS Salas (
            id_sala INT PRIMARY KEY,
            nombre_sala VARCHAR(50) NOT NULL,
            piso VARCHAR(50) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS Asignaciones (
            id_asignacion SERIAL PRIMARY KEY,
            rut_estudiante VARCHAR(15),
            id_sala INT,
            asignatura VARCHAR(100),
            FOREIGN KEY (rut_estudiante) REFERENCES Estudiantes(rut) ON DELETE CASCADE,
            FOREIGN KEY (id_sala) REFERENCES Salas(id_sala) ON DELETE CASCADE
        );

        INSERT INTO Estudiantes (rut, nombre_completo, carrera) VALUES
        ('19252153-k', 'Gabriel Montero', 'ciberseguridad'),
        ('20796240-6', 'Mauro Guerra', 'ciberseguridad'),
        ('18256317-k', 'Jorge Encalada', 'ciberseguridad') ON CONFLICT DO NOTHING;

        INSERT INTO Salas (id_sala, nombre_sala, piso) VALUES
        (1, '101', 'primer piso'),
        (2, '202', 'segundo piso'),
        (3, '303', 'tercer piso') ON CONFLICT DO NOTHING;

        INSERT INTO Asignaciones (rut_estudiante, id_sala, asignatura) VALUES
        ('19252153-k', 1, 'Hacking Ético'),
        ('20796240-6', 2, 'Criptografía Aplicada'),
        ('18256317-k', 3, 'Auditoría de Sistemas');
    ";

    $conn->exec($sql);
    echo "<h1>¡Éxito!</h1>";
    echo "<p>Las tablas y los datos de prueba se crearon correctamente en PostgreSQL.</p>";

} catch (PDOException $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
