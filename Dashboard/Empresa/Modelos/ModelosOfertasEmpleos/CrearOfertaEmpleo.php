<?php

// Incluir archivos necesarios para la conexión a la base de datos y funciones
include_once '../../../../BD/Conexion.php';
include_once '../../../../BD/Consultas.php';
include_once '../../../../main/funcionesApp.php';

// Instanciar el objeto de funciones de la aplicación
$FuncionesApp = new funcionesApp();
session_start(); // Iniciar la sesión

// Verificar si el formulario ha sido enviado
if (isset($_POST['Guardar'])) {

    // Sanitizar y obtener los datos del formulario
    $IDempresa = $FuncionesApp->test_input($_POST['Iduser']);
    $IDPais = $FuncionesApp->test_input($_POST['OrigenPais']);
    $IDDepartamento = $FuncionesApp->test_input($_POST['IDDepartemento']);
    $IDCategoria = $FuncionesApp->test_input($_POST['areaempresa']);
    $IDDesempenado = $FuncionesApp->test_input($_POST['idCargo']);
    $Plaza = $FuncionesApp->test_input($_POST['nombrePlaza']);
    $Descripcion = $FuncionesApp->test_input2($_POST['descripcion']); // Se usa test_input2 para descripciones más largas
    $Vacante = $FuncionesApp->test_input($_POST['vacantes']);
    $TipoContratacion = $FuncionesApp->test_input($_POST['Disponibilidad']);
    $Genero = $FuncionesApp->test_input($_POST['genero']);
    $EdadMenor = $FuncionesApp->test_input($_POST['edadMenor']);
    $EdadMayor = $FuncionesApp->test_input($_POST['edadMayor']);
    $SalarioMinimo = $FuncionesApp->test_input($_POST['salarioMinimo']);
    $SalarioMayor = $FuncionesApp->test_input($_POST['salarioMaximo']);
    $Vehiculo = $FuncionesApp->test_input($_POST['Vehiculo']);
    $TipoVehiculo = $FuncionesApp->test_input($_POST['Manejo']);
    $Experiencia = $FuncionesApp->test_input($_POST['ExperienciaUser']);
    $NivelExperiencia = $FuncionesApp->test_input($_POST['experiencia']);
    $Expira = $FuncionesApp->test_input($_POST['expira']); // Fecha de expiración de la oferta
    $fechaActual = date("Y-m-d"); // Fecha actual
    $Estado = $FuncionesApp->test_input($_POST['estado']); // Estado de la oferta (activa o inactiva)

    // Consulta SQL para insertar los datos de la oferta en la base de datos
    $sql = "INSERT INTO `empresa_ofertas_trabajos` 
            (`IDEmpresa`, `IDPais`, `IDDepartamento`, `IDCategoria`, `IDDesempenado`, `Plaza`, `Descripcion`, `Vacante`, 
            `TipoContratacion`, `Genero`, `EdadMenor`, `EdadMayor`, `SalarioMinimo`, `SalarioMaximo`, `Vihiculo`, 
            `TipoVehiculo`, `Experiencia`, `IDAreaExperiencia`, `Expira`, `FechaPublicacion`, `Estado`) 
            VALUES 
            (:IDEmpresa, :IDPais, :IDDepartamento, :IDCategoria, :IDDesempenado, :Plaza, :Descripcion, :Vacante, 
            :TipoContratacion, :Genero, :EdadMenor, :EdadMayor, :SalarioMinimo, :SalarioMaximo, :Vihiculo, 
            :TipoVehiculo, :Experiencia, :NivelExperiencia, :Expira, :FechaPublicacion, :Estado)";

    // Preparar la consulta SQL
    $stmt = Conexion::conectar()->prepare($sql);

    // Asignar los valores a cada parámetro de la consulta
    $stmt->bindParam('IDEmpresa', $IDempresa, PDO::PARAM_STR);
    $stmt->bindParam('IDPais', $IDPais, PDO::PARAM_STR);
    $stmt->bindParam('IDDepartamento', $IDDepartamento, PDO::PARAM_STR);
    $stmt->bindParam('IDCategoria', $IDCategoria, PDO::PARAM_STR);
    $stmt->bindParam('IDDesempenado', $IDDesempenado, PDO::PARAM_STR);
    $stmt->bindParam('Plaza', $Plaza, PDO::PARAM_STR);
    $stmt->bindParam('Descripcion', $Descripcion, PDO::PARAM_STR);
    $stmt->bindParam('Vacante', $Vacante, PDO::PARAM_STR);
    $stmt->bindParam('TipoContratacion', $TipoContratacion, PDO::PARAM_STR);
    $stmt->bindParam('Genero', $Genero, PDO::PARAM_STR);
    $stmt->bindParam('EdadMenor', $EdadMenor, PDO::PARAM_STR);
    $stmt->bindParam('EdadMayor', $EdadMayor, PDO::PARAM_STR);
    $stmt->bindParam('SalarioMinimo', $SalarioMinimo, PDO::PARAM_STR);
    $stmt->bindParam('SalarioMaximo', $SalarioMayor, PDO::PARAM_STR);
    $stmt->bindParam('Vihiculo', $Vehiculo, PDO::PARAM_STR);
    $stmt->bindParam('TipoVehiculo', $TipoVehiculo, PDO::PARAM_STR);
    $stmt->bindParam('Experiencia', $Experiencia, PDO::PARAM_STR);
    $stmt->bindParam('NivelExperiencia', $NivelExperiencia, PDO::PARAM_STR);
    $stmt->bindParam('Expira', $Expira, PDO::PARAM_STR);
    $stmt->bindParam('FechaPublicacion', $fechaActual, PDO::PARAM_STR);
    $stmt->bindParam('Estado', $Estado, PDO::PARAM_STR);

    // Ejecutar la consulta y verificar si se insertaron los datos correctamente
    if ($stmt->execute()) {

        // Verificar si existe un reporte para la fecha actual y la empresa
        $sql18 = "SELECT COUNT(`IDReporte`) AS 'FechaDelDia' 
                  FROM `reportes_generales` 
                  WHERE `fecha` = ? 
                  AND `IDEmpresa` = ? 
                  AND `Tipo` = 'Ofertas publicadas'";

        $stmt18 = Conexion::conectar()->prepare($sql18);
        $fechadelDia = date("Y-m-d"); // Fecha del día actual

        // Ejecutar consulta para contar reportes
        $stmt18->execute(array($fechadelDia, $IDempresa));
        while ($item = $stmt18->fetch()) {
            $ResultReporteDelDia = $item['FechaDelDia'];
        }

        // Si no hay reportes, crear uno nuevo
        if ($ResultReporteDelDia == 0) {
            $sql19 = "INSERT INTO `reportes_generales` 
                      (`IDEmpresa`, `Tipo`, `contador`, `fecha`) 
                      VALUES (:IDEmpresa, 'Ofertas publicadas', '1', :fecha)";
            $stmt19 = Conexion::conectar()->prepare($sql19);
            $stmt19->bindParam('IDEmpresa', $IDempresa, PDO::PARAM_STR);
            $stmt19->bindParam('fecha', $fechadelDia, PDO::PARAM_STR);
            if (!$stmt19->execute()) {
                echo "No se ha podido guardar el conteo de reportes";
            }
        } else {
            // Si ya existe un reporte, actualizar el contador
            $sql20 = "SELECT `contador` 
                      FROM reportes_generales 
                      WHERE `fecha` = ?  
                      AND `IDEmpresa` = ? 
                      AND `Tipo` = 'Ofertas publicadas'";
            $stmt20 = Conexion::conectar()->prepare($sql20);
            $stmt20->execute(array($fechadelDia, $IDempresa));
            while ($item = $stmt20->fetch()) {
                $contador = $item['contador']; // Contador actual de visitas
            }

            $incremento = $contador + 1; // Incrementar el contador en 1

            // Actualizar el reporte con el nuevo contador
            $sql19 = "UPDATE `reportes_generales` 
                      SET `contador`= :contador 
                      WHERE `fecha` = :fecha  
                      AND `IDEmpresa` = :IDEmpresa 
                      AND `Tipo` = 'Ofertas publicadas'";
            $stmt19 = Conexion::conectar()->prepare($sql19);
            $stmt19->bindParam('contador', $incremento, PDO::PARAM_STR);
            $stmt19->bindParam('fecha', $fechadelDia, PDO::PARAM_STR);
            $stmt19->bindParam('IDEmpresa', $IDempresa, PDO::PARAM_STR);
            if (!$stmt19->execute()) {
                echo "No se ha podido guardar el conteo de reportes";
            }
        }

        // Redirigir al usuario con un mensaje de éxito
        $_SESSION['alertas'] = "Mensaje";
        $_SESSION['ms'] = "Se ha agregado la oferta de empleo";
        header('Location: ../../ofertas-empleos');

    } else {
        // Si falla la inserción, redirigir con mensaje de error
        $_SESSION['alertas'] = "Fallo";
        header('Location: ../../nueva-oferta-empleo');
    }

} else {
    // Si no se reciben datos, redirigir con un mensaje de pérdida de datos
    $_SESSION['alertas'] = "PerdidaDatos";
    header('Location: ../../nueva-oferta-empleo');
}

?>
