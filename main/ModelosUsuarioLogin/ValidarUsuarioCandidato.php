<?php
/*
 * Archivo: ValidarUsuarioCandidato.php
 * Propósito: Validar las credenciales de un candidato al iniciar sesión y redirigirlo a su dashboard o mostrar errores si es necesario.
 * Autor: [Tu Nombre]
 * Fecha de modificación: [Fecha Actual]
 */

session_start(); // Iniciar la sesión para gestionar variables de sesión

// Incluir las conexiones a la base de datos y las funciones necesarias
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

// Instanciar las clases necesarias para ejecutar consultas y manejar funciones de apoyo
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Verificar si se ha enviado el formulario de inicio de sesión
if (isset($_POST['validar'])) {

    // Limpiar y normalizar el correo electrónico y la contraseña recibidos del formulario
    $email = $FuncionesApp->test_input($_POST['login-username']);
    $password = $FuncionesApp->test_input($_POST['login-password']);
    // Convertir el correo a minúsculas para evitar problemas de sensibilidad de mayúsculas
    $emailFormato = strtolower($email);

    // Obtener datos adicionales del formulario, como la URL de redirección y el código (si existen)
    $dirrecionar = $_POST['direccionar'];
    $Codigo = $_POST['codigo'];

    // Preparar la consulta para obtener la información del usuario basado en el correo electrónico

    $sql = "SELECT `IDUsuario` , `Nombre` , `Apellidos` , `Correo` ,`Password` ,`Foto` , Cargo , `Estado`
            FROM usuarios_cuentas WHERE `Correo` = ?";
    $stmt = $Conexion->ejecutar_consulta_simple_Where($sql, $emailFormato);

    // Inicializar las variables donde se almacenarán los datos del usuario
    $Correo = "";
    while ($item = $stmt->fetch()) {
        $Iduser = $item['IDUsuario'];
        $Nombre = $item['Nombre'];
        $Apellidos = $item['Apellidos'];
        $Correo = $item['Correo'];
        $ObtnerContra = $item['Password'];
        $Foto = $item['Foto'];
        $Estado = $item['Estado'];
        $Cargo = $item['Cargo'];

    }
    // Verificar si el correo ingresado coincide con el de la base de datos y si la contraseña es correcta
    if ($emailFormato == $Correo && password_verify($password, $ObtnerContra)) {
        // Validar el estado del usuario y manejar según el caso

        if ($Estado == "Token") {
            // Estado: Token (usuario pendiente de verificación por correo)
            $_SESSION['alertas'] = "Advertenicia";
            $_SESSION['ms'] = "Tu cuenta está pendiente de verificación por parte del administrador. Por favor, contacta con el administrador para completar el proceso de activación.";
            header("Location: ../../login-candidato");
        } else if ($Estado == "Denegado") {
            // Estado: Denegado (acceso denegado por algún motivo)
            $_SESSION['alertas'] = "Advertenicia";
            $_SESSION['ms'] = "Usuario denegado verifica con Soporte técnico";
            header("Location: ../../login-candidato");
        } else if ($Estado == "Seguridad") {
            // Estado: Seguridad (usuario debe verificar un cambio de contraseña)
            $_SESSION['alertas'] = "Advertenicia";
            $_SESSION['ms'] = "Usuario denegado verifica tu correo electrónico para confirmar el cambio de contraseña";
            header("Location: ../../login-candidato?seguridad=1");
        } else {
            // Usuario con estado activo o sin restricciones
            $_SESSION['iduser'] = $Iduser;
            $_SESSION['nombre'] = $Nombre;
            $_SESSION['apellido'] = $Apellidos;
            $_SESSION['email'] = $Correo;
            $_SESSION['cargo'] = $Cargo;
            $_SESSION['foto'] = $Foto;

            if ($dirrecionar != "") {

                header("Location: ../../" . $dirrecionar . "?id=" . $Codigo);

            } else {
                switch ($Cargo) {
                    case 'Candidato':
                        header("Location: ../../Dashboard/Candidato/");
                        break;
                    default:
                        header("Location: ../../login-candidato");
                        break;

                }

            }
        }

    } else {

        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['alertas'] = "Advertenicia";
        $_SESSION['ms'] = "El correo electrónico o contraseña incorrecto.";
        header("Location: ../../login-candidato?error=0");
    }

}

?>