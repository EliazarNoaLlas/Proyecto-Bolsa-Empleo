<?php
session_start();
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

if (isset($_POST['validar'])) {

    $email = $FuncionesApp->test_input($_POST['login-username']);
    $password = $FuncionesApp->test_input($_POST['login-password']);
    $emailFormato = strtolower($email);


    $sql = "SELECT `IDUsuario` , `Nombre` , `Apellidos` , `Correo` ,`Password` ,`Foto` , Cargo , `Estado` 
            FROM usuarios_cuentas WHERE `Correo` = ?";
    $stmt = $Conexion->ejecutar_consulta_simple_Where($sql, $emailFormato);

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

    if ($emailFormato == $Correo && password_verify($password, $ObtnerContra)) {
        if ($Estado == "Token") {
            $_SESSION['alertas'] = "Advertencia";
            $_SESSION['ms'] = "Tu cuenta está pendiente de verificación por parte del administrador. Por favor, contacta con el administrador para completar el proceso de activación.";
            header("Location: ../../login-empresa");
        } else if ($Estado == "Denegado") {
            $_SESSION['alertas'] = "Advertencia";
            $_SESSION['ms'] = "Usuario denegado verfica con Soporte técnico";
            header("Location: ../../login-empresa");
        } else if ($Estado == "Seguridad") {

            $_SESSION['alertas'] = "Advertencia";
            $_SESSION['ms'] = "Usuario denegado verifica tu correo electrónico para confirmar el cambio de contraseña";
            header("Location: ../../login-empresa?seguridad=1");
        } else {

            $_SESSION['iduser'] = $Iduser;
            $_SESSION['nombre'] = $Nombre;
            $_SESSION['apellido'] = $Apellidos;
            $_SESSION['email'] = $Correo;
            $_SESSION['cargo'] = $Cargo;
            $_SESSION['foto'] = $Foto;

            switch ($Cargo) {
                case 'Empresa':
                    header("Location: ../../Dashboard/Empresa/");
                    break;
                default:
                    $_SESSION['email'] = $email;
                    $_SESSION['alertas'] = "Advertencia";
                    $_SESSION['ms'] = "Tu cuenta no es de empresa";
                    header("Location: ../../login-empresa");
                    break;
            }
        }
    } else {

        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['alertas'] = "Advertencia";
        $_SESSION['ms'] = "El correo electrónico o contraseña incorrecto";
        header("Location: ../../login-empresa?error=0");
    }
}
