<?php
// Se incluyen los archivos de conexión a la base de datos y funciones auxiliares
include_once '../../../../BD/Conexion.php';
include_once '../../../../BD/Consultas.php';
include_once '../../../../main/funcionesApp.php';

// Se instancian las clases necesarias para manejar consultas y funciones adicionales
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Se inicia la sesión para poder usar variables de sesión
session_start();

// Se verifica si se ha enviado el formulario para guardar el archivo
if (isset($_POST["Guardar"])) {

    // Se limpia el nombre del archivo usando la función test_input y se le agrega la extensión ".pdf"
    $NombreArchivo = $FuncionesApp->test_input($_POST['nombre']);
    $NombreArchivo .= ".pdf";

    // Se limpian otros campos que vienen del formulario
    $IDusuario = $FuncionesApp->test_input($_POST['Iduser']);
    $tipoarchivo = $_FILES["archivo"]["type"];
    $tamañoarchivo = $_FILES["archivo"]["size"];
    $rutaarchivo = $_FILES["archivo"]["tmp_name"];

    // Se define la ruta destino donde se guardará el archivo
    $destino = "../../../../documentos/documentos_usuarios/" . $IDusuario . "/";

    // Se consulta si ya existe un documento con el mismo nombre para el mismo usuario
    $sql2 = "SELECT COUNT(`IDDocumento`) AS 'ResultDocumento' 
             FROM `usuarios_documentos` 
             WHERE `Documento` = ? AND IDUsuario = ?";
    $stmt2 = Conexion::conectar()->prepare($sql2);
    $stmt2->execute(array($NombreArchivo, $IDusuario));

    // Se obtiene el resultado de la consulta
    while ($item = $stmt2->fetch()) {
        $ResultDocumento = $item['ResultDocumento'];
    }

    // Si ya existe un archivo con el mismo nombre, se envía un mensaje de advertencia
    if ($ResultDocumento == 1) {
        $_SESSION['alertas'] = "Advertencia";
        $_SESSION['ms'] = "Ya existe un archivo con el nombre " . $NombreArchivo . ". Intente con otro nombre.";
        #header('Location: ../../documentos');
    } else {
        // Verifica si el directorio de destino existe, y si no, intenta crear toda la estructura necesaria
        if (!file_exists($destino)) {
            // Crear la estructura de directorios si no existe
            if (!mkdir($destino, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }

        // Se agrega el nombre del archivo al destino
        $destino .= $NombreArchivo;

        // Se inserta el registro del documento en la base de datos
        $sql = "INSERT INTO `usuarios_documentos` (`IDUsuario`, `Documento`) 
                VALUES (:IDUsuario, :Documento)";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':IDUsuario', $IDusuario, PDO::PARAM_STR);
        $stmt->bindParam(':Documento', $NombreArchivo, PDO::PARAM_STR);

        // Si la inserción en la base de datos es exitosa
        if ($stmt->execute()) {
            // Se intenta copiar el archivo al directorio de destino
            if (copy($rutaarchivo, $destino)) {
                $_SESSION['alertas'] = "Mensaje";
                $_SESSION['ms'] = "Se ha agregado el documento correctamente.";
                #header('Location: ../../documentos');
            } else {
                // Si no se pudo copiar el archivo, se envía un mensaje de fallo
                $_SESSION['alertas'] = "Fallo";
                #header('Location: ../../documentos');
            }
        } else {
            // Si la inserción en la base de datos falla, se envía un mensaje de fallo
            $_SESSION['alertas'] = "Fallo";
            #header('Location: ../../documentos');
        }
    }
}
?>
