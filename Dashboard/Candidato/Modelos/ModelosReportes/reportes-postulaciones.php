<?php

include '../../../../BD/Conexion.php';
include_once '../../../../BD/Consultas.php';
$Conexion = new Consultas();
include_once '../../../../main/funcionesApp.php';
$FuncionesApp = new funcionesApp();

header('Content-Type: application/json; charset=UTF-8');

if (isset($_POST['buscar']) && $_POST['buscar'] == "GenerarReporte") {

    $IDUser = $FuncionesApp->test_input($_POST['IDUser']);
    $fechaInicial = $FuncionesApp->test_input($_POST['FechaInicial']);
    $FechaFinal = $FuncionesApp->test_input($_POST['FechaFinal']);

    $sql = "SELECT P.IDOfertaTrabajo, P.IDpostulaciones, EP.logo, EP.Nombre AS 'Empresa', OP.Plaza, EP.Confidencial, P.Estado, P.Aprobacion, P.FechaInscrita 
            FROM usuario_postulaciones P 
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones 
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa 
            WHERE P.IDUsuario = ? AND (P.FechaInscrita >= ? AND P.FechaInscrita <= ?) 
            ORDER BY P.FechaInscrita DESC";
    $stmt =  Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $fechaInicial, $FechaFinal));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} else {

    $IDUser = $FuncionesApp->test_input($_POST['IDUser']);
    $sql = "SELECT P.IDOfertaTrabajo, P.IDpostulaciones, EP.logo, EP.Nombre AS 'Empresa', OP.Plaza, EP.Confidencial, P.Estado, P.Aprobacion, P.FechaInscrita 
            FROM usuario_postulaciones P 
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones 
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa 
            WHERE P.IDUsuario = ? 
            ORDER BY P.FechaInscrita DESC";
    $stmt =  Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}

?>
