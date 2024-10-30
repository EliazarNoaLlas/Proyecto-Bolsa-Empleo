<?php
/**
 * Nombre del archivo: reportes-postulaciones.php
 * Propósito: Generar reportes de postulaciones de un usuario en base a un rango de fechas
 * o todas las postulaciones si no se proporciona un rango específico.
 * Autor: Walter Stefano
 * Fecha de última modificación: 30/10/2024
 */

// Importación de archivos y clases necesarios
include '../../../../BD/Conexion.php';
include_once '../../../../BD/Consultas.php';
include_once '../../../../main/funcionesApp.php';

// Crear instancias de las clases necesarias para ejecutar funciones y consultas
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Establecer el encabezado de respuesta como JSON para la salida de datos
header('Content-Type: application/json; charset=UTF-8');

// Verificar si la solicitud POST contiene un valor 'buscar' igual a 'GenerarReporte'
if (isset($_POST['buscar']) && $_POST['buscar'] === "GenerarReporte") {

    // Validar y limpiar las entradas del usuario para evitar inyección de código malicioso
    $IDUser = $FuncionesApp->test_input($_POST['IDUser']);
    $fechaInicial = $FuncionesApp->test_input($_POST['FechaInicial']);
    $FechaFinal = $FuncionesApp->test_input($_POST['FechaFinal']);

    // Consulta SQL para obtener las postulaciones dentro del rango de fechas especificado
    $sql = "SELECT P.IDOfertaTrabajo, P.IDpostulaciones, EP.logo, EP.Nombre AS 'Empresa', 
                   OP.Plaza, EP.Confidencial, P.Estado, P.Aprobacion, P.FechaInscrita
            FROM usuario_postulaciones P
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa
            WHERE P.IDUsuario = ? AND (P.FechaInscrita >= ? AND P.FechaInscrita <= ?)
            ORDER BY P.FechaInscrita DESC";

    // Preparar y ejecutar la consulta SQL con los valores proporcionados
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $fechaInicial, $FechaFinal));

    // Obtener los datos de la consulta como un arreglo asociativo
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Codificar los datos en formato JSON y enviarlos como respuesta
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} else {

    // Si no se especificó un rango de fechas, obtener todas las postulaciones del usuario
    $IDUser = $FuncionesApp->test_input($_POST['IDUser']);

    // Consulta SQL para obtener todas las postulaciones del usuario sin filtrar por fecha
    $sql = "SELECT P.IDOfertaTrabajo, P.IDpostulaciones, EP.logo, EP.Nombre AS 'Empresa', 
                   OP.Plaza, EP.Confidencial, P.Estado, P.Aprobacion, P.FechaInscrita
            FROM usuario_postulaciones P
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa
            WHERE P.IDUsuario = ?
            ORDER BY P.FechaInscrita DESC";

    // Preparar y ejecutar la consulta SQL con el ID del usuario
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser));

    // Obtener los datos de la consulta como un arreglo asociativo
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Codificar los datos en formato JSON y enviarlos como respuesta
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
?>
