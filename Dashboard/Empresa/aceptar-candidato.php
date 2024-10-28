<?php
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

// Crear una instancia de las clases necesarias
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Verificar si se ha recibido un ID de candidato y una acción (aceptar o denegar)
if (isset($_GET['id'])) {
    $idCandidato = base64_decode($_GET['id']);

    // Dependiendo de la acción, ejecutar la consulta correspondiente
    $sql = "UPDATE usuario_postulaciones SET Estado = 'Aceptado' WHERE IDUsuario = ?";

    // Preparar y ejecutar la consulta
    $stmt = Conexion::conectar()->prepare($sql);

    if (!$stmt->execute([$idCandidato])) {
        die("Error al actualizar el estado del candidato.");
    }

    // Redirigir después de la acción
    header("Location: candidos_postulados.php?mensaje=Acción realizada con éxito");
    exit;
} else {
    die("ID de candidato o acción no especificados.");
}
?>
