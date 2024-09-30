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

    $dirrecionar = $_POST['direccionar'] ?? '';
    $Codigo = $_POST['codigo'] ?? '';

    $sql = "SELECT `IDUsuario`, `Nombre`, `Apellidos`, `Correo`, `Password`, `Foto`, `Cargo`, `Estado` FROM usuarios_cuentas WHERE `Correo` = ?";
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

    if($emailFormato == $Correo && password_verify($password, $ObtnerContra)) {
        // Eliminamos la verificación del estado "Token", "Denegado" y "Seguridad"
        $_SESSION['iduser'] = $Iduser;
        $_SESSION['nombre'] = $Nombre;
        $_SESSION['apellido'] = $Apellidos;
        $_SESSION['email'] = $Correo;
        $_SESSION['cargo'] = $Cargo;
        $_SESSION['foto'] = $Foto;

        if($dirrecionar != "") {
            header("Location: ../../".$dirrecionar."?id=".$Codigo);
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
    } else {
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['alertas'] = "Advertenicia";
        $_SESSION['ms'] = "El correo electrónico o contraseña incorrecto";
        header("Location: ../../login-candidato?error=0");
    }
}
?>
