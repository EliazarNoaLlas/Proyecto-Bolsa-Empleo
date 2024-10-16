<?php
// Inicia la sesión para permitir el uso de variables de sesión
session_start();

// Incluye las conexiones a la base de datos y las funciones necesarias
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

// Instancia de las clases para manejar la conexión y funciones
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Verifica si se ha enviado el formulario de validación
if (isset($_POST['validar'])) {

    // Limpia y normaliza los datos recibidos (email y password) del formulario
    $email = $FuncionesApp->test_input($_POST['login-username']);
    $password = $FuncionesApp->test_input($_POST['login-password']);
    // Convierte el email a minúsculas para evitar problemas de case-sensitive
    $emailFormato = strtolower($email);

    // Obtiene los valores opcionales 'direccionar' y 'codigo' del formulario
    $dirrecionar = $_POST['direccionar'] ?? '';  // URL a redirigir si está definida
    $Codigo = $_POST['codigo'] ?? '';            // Código adicional si es necesario

    // Prepara la consulta para obtener los datos del usuario a partir del correo
    $sql = "SELECT `IDUsuario`, `Nombre`, `Apellidos`, `Correo`, `Password`, `Foto`, `Cargo`, `Estado` 
            FROM usuarios_cuentas WHERE `Correo` = ?";
    // Ejecuta la consulta con el correo proporcionado
    $stmt = $Conexion->ejecutar_consulta_simple_Where($sql, $emailFormato);

    // Inicializa variables para almacenar los datos del usuario
    $Correo = "";
    while ($item = $stmt->fetch()) {
        // Almacena los datos del usuario obtenidos de la base de datos
        $Iduser = $item['IDUsuario'];
        $Nombre = $item['Nombre'];
        $Apellidos = $item['Apellidos'];
        $Correo = $item['Correo'];            // Correo registrado en la BD
        $ObtnerContra = $item['Password'];    // Contraseña hasheada almacenada en la BD
        $Foto = $item['Foto'];                // Foto de perfil del usuario
        $Estado = $item['Estado'];            // Estado del usuario (Activo, Denegado, etc.)
        $Cargo = $item['Cargo'];              // Cargo del usuario (Candidato, Empresa, etc.)
    }

    // Verifica si el correo ingresado coincide con el correo en la BD y si la contraseña es correcta
    if ($emailFormato == $Correo && password_verify($password, $ObtnerContra) && $Estado == "Activo") {

        // Si la validación es correcta, se guardan los datos del usuario en la sesión
        $_SESSION['iduser'] = $Iduser;
        $_SESSION['nombre'] = $Nombre;
        $_SESSION['apellido'] = $Apellidos;
        $_SESSION['email'] = $Correo;
        $_SESSION['cargo'] = $Cargo;
        $_SESSION['foto'] = $Foto;

        // Si se especificó una dirección para redirigir, se redirige a esa URL con el código
        if ($dirrecionar != "") {
            header("Location: ../../" . $dirrecionar . "?id=" . $Codigo);
        } else {
            // Si no hay redirección específica, se redirige según el cargo del usuario
            switch ($Cargo) {
                case 'Candidato':
                    header("Location: ../../Dashboard/Candidato/");  // Redirige al dashboard de Candidato
                    break;
                default:
                    header("Location: ../../login-candidato");      // Redirige a la página de login
                    break;
            }
        }
    } else {
        // Si el correo o contraseña no son correctos, se almacenan en sesión para mostrar un mensaje de error
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['alertas'] = "Advertencia";
        $_SESSION['ms'] = "El correo electrónico o contraseña incorrecto";
        // Redirige de nuevo a la página de login con un mensaje de error
        header("Location: ../../login-candidato?error=0");
    }
}
?>
