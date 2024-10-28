<?php
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

// Crear una instancia de las clases necesarias
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Verificar si se ha recibido un ID de candidato
if (isset($_GET['id'])) {
    // Decodifica el ID de usuario recibido por la URL
    $idCandidato = base64_decode($_GET['id']);

    // Actualizar el estado del candidato a "Denegado"
    $sql = "UPDATE usuario_postulaciones SET Estado = 'Denegado' WHERE IDUsuario = ?";

    // Preparar y ejecutar la consulta
    $stmt = Conexion::conectar()->prepare($sql);

    if (!$stmt->execute([$idCandidato])) {
        die("Error al actualizar el estado del candidato.");
    }

    // Redirigir después de la acción
    header("Location: candidos_postulados.php?mensaje=El candidato ha sido denegado correctamente.");
    exit;
} else {
    die("ID de candidato no especificado.");
}
?>
