<?php
// Iniciamos una sesión para almacenar información del usuario una vez autenticado
session_start();

// Incluimos archivos necesarios para la conexión a la base de datos, consultas y funciones auxiliares
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

// Creamos instancias de las clases para consultas y funciones
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Verificamos si se ha enviado el formulario de validación de usuario
if (isset($_POST['validar'])) {

    // Capturamos los datos del formulario y los limpiamos para evitar inyecciones de código
    $email = $FuncionesApp->test_input($_POST['login-username']);
    $password = $FuncionesApp->test_input($_POST['login-password']);
    $emailFormato = strtolower($email);  // Convertimos el correo a minúsculas para estandarizar el formato

    // Consulta SQL para buscar al usuario por correo electrónico
    $sql = "SELECT `IDUsuario`, `Nombre`, `Apellidos`, `Correo`, `Password`, `Foto`, `Cargo`, `Estado` 
                FROM usuarios_cuentas WHERE `Correo` = ?";

    // Ejecutamos la consulta con el correo proporcionado
    $stmt = $Conexion->ejecutar_consulta_simple_Where($sql, $emailFormato);

    // Inicializamos variables para almacenar los datos obtenidos de la consulta
    $Correo = "";

    // Recorremos los resultados de la consulta para obtener los datos del usuario
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

    // Verificamos si el correo existe y si la contraseña es correcta utilizando password_verify()
    if ($emailFormato == $Correo && password_verify($password, $ObtnerContra)) {

        // Si el usuario es válido, guardamos sus datos en la sesión
        $_SESSION['iduser'] = $Iduser;
        $_SESSION['nombre'] = $Nombre;
        $_SESSION['apellido'] = $Apellidos;
        $_SESSION['email'] = $Correo;
        $_SESSION['cargo'] = $Cargo;
        $_SESSION['foto'] = $Foto;

        // Comprobamos el cargo del usuario para redirigirlo a la sección correspondiente
        switch ($Cargo) {
            case 'Soporte':
                // Si el cargo es "Soporte", redirigimos a su panel de administración
                header("Location: ../../Dashboard/Soporte/");
                break;

            default:
                // Si el cargo no es válido para administrador, mostramos un mensaje de advertencia
                $_SESSION['email'] = $email;
                $_SESSION['alertas'] = "Advertencia";
                $_SESSION['ms'] = "Tu cuenta no es de administración";
                header("Location: ../../login-admin");
                break;
        }

    } else {
        // Si el correo o la contraseña no coinciden, mostramos un error
        $_SESSION['email'] = $email;
        $_SESSION['alertas'] = "Advertencia";
        $_SESSION['ms'] = "Correo electrónico o contraseña incorrectos";
        header("Location: ../../login-admin?error=0");
    }
}
