<?php

// Se incluyen los archivos necesarios para la conexión a la base de datos y las consultas
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';

// Se instancia la clase Consultas para ejecutar consultas en la base de datos
$Conexion = new Consultas();

// Se inicializan variables vacías que se utilizarán más adelante
$salida = "";
$sql = "";
$result = "";

// Verifica si se ha enviado el dato a través del método POST
if (isset($_POST['consulta'])) {

    // Se obtiene el dato (correo) enviado por el formulario
    $dato = $_POST['consulta'];

    // Se ejecuta una consulta para contar cuántos registros existen en la tabla 'usuarios_cuentas'
    // que coinciden con el correo proporcionado
    $result = $Conexion->ejecutar_consulta_conteo("usuarios_cuentas", "Correo", $dato);

    // Si se encuentra al menos un registro con ese correo, se genera una alerta indicando que el correo ya está en uso
    if ($result == 1) {
        $salida .= "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                El correo ya está en uso
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <input type='hidden' value='" . $result . "' id='validez'>
        ";
    } else {
        // Si no se encuentra ningún registro con ese correo, no se muestra ninguna alerta
        $salida .= "";
    }

    // Se devuelve el resultado (la alerta o vacío) como respuesta al cliente
    echo $salida;
}

?>
