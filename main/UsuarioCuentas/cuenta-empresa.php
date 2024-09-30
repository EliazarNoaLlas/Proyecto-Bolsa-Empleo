<?php

// Se incluyen los archivos necesarios para la conexión, consultas, funciones auxiliares y envío de correos
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';
include_once 'EnviarCorreos.php';

// Se instancia la clase funcionesApp para utilizar funciones auxiliares
$FuncionesApp = new funcionesApp();

try {
    // Se obtiene y limpia el correo electrónico ingresado por el usuario
    $CorroFormato = $FuncionesApp->test_input($_POST['correo']);

    // Se convierte el correo a minúsculas para estandarización
    $email = strtolower($CorroFormato);

    // Se obtienen y limpian los demás datos ingresados por el usuario
    $Cargo = $FuncionesApp->test_input($_POST['Cargo']);
    $Nombre = $FuncionesApp->test_input($_POST['Nombre']);
    $Apellidos = $FuncionesApp->test_input($_POST['Apellidos']);

    // Se limpia y encripta la contraseña ingresada
    $PasswordFormato = $FuncionesApp->test_input($_POST['password']);
    $Password = password_hash($PasswordFormato, PASSWORD_DEFAULT); // Se encripta la contraseña

    // Se genera un token de seguridad aleatorio de 20 caracteres
    $Token = $FuncionesApp->generar_token_seguro(20);

    // Se realiza una consulta para verificar si el correo ya está registrado en la base de datos
    $resultEmail = Consultas::ejecutar_consulta_conteo("usuarios_cuentas", "Correo", $email);

    // Si el correo ya está en uso, se devuelve "1" como respuesta
    if ($resultEmail == 1) {
        echo "1";
    } else {
        // Si el correo no está registrado, se inserta el nuevo usuario en la base de datos
        $sql = "INSERT INTO `usuarios_cuentas` (`Nombre`, `Apellidos`, `Correo`, `Password`, `Token`, `Cargo`)
                VALUES(:Nombre, :Apellidos, :Correo, :Password, :Token, :Cargo)";

        // Se prepara la consulta SQL para insertar los datos del usuario
        $stmt = Conexion::conectar()->prepare($sql);

        // Se vinculan los parámetros de la consulta con las variables correspondientes
        $stmt->bindParam(':Nombre', $Nombre, PDO::PARAM_STR);
        $stmt->bindParam(':Apellidos', $Apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':Correo', $email, PDO::PARAM_STR);
        $stmt->bindParam(':Password', $Password, PDO::PARAM_STR);
        $stmt->bindParam(':Token', $Token, PDO::PARAM_STR);
        $stmt->bindParam(':Cargo', $Cargo, PDO::PARAM_STR);

        // Se ejecuta la consulta y se comprueba si se insertó correctamente
        if ($stmt->execute()) {
            // Si se inserta correctamente, se devuelve "2" como respuesta y se envía el correo de verificación
            echo "2";
        } else {
            // Si hay un error en la inserción, se devuelve "3" como respuesta
            echo "3";
        }
    }

} catch (PDOException $e) {
    // En caso de error en la consulta, se muestra un mensaje con el detalle del error
    die("El error de consulta es: " . $e->getMessage());
}

?>
