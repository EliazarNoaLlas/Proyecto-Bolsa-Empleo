<!doctype html>
<html lang="en" class="no-focus">

<head>
    <!-- Definición del conjunto de caracteres utilizado en el documento -->
    <meta charset="utf-8">
    <!-- Configuración para hacer el sitio web responsivo en diferentes dispositivos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <!-- Título que aparecerá en la pestaña del navegador -->
    <title>BOLSA LABORAL | Login</title>

    <!-- Meta descripción para los motores de búsqueda y para información en redes sociales -->
    <meta name="description" content="Login - soporte BOLSA LABORAL">
    <meta name="author" content="BOLSA LABORAL Centro América">
    <meta name="robots" content="noindex, nofollow">

    <!-- Meta etiquetas para la integración con Open Graph, mejorando la visualización cuando se comparte el sitio en redes sociales -->
    <meta property="og:title" content="Login - Soporte de BOLSA LABORAL CA">
    <meta property="og:site_name" content="BOLSA LABORAL">
    <meta property="og:description" content="Login - soporte BOLSA LABORAL">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://mundoempleosca.com/">
    <meta property="og:image" content="">

    <!-- Definición de los íconos utilizados por navegadores y dispositivos móviles -->
    <link rel="shortcut icon" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">

    <!-- Importación de las fuentes y estilos de la página -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
    <link rel="stylesheet" id="css-main" href="Dashboard/assets/css/codebase.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugin/sweetalert/sweetalert2.css">

    <!-- Estilos personalizados para el formulario de login -->
    <style type="text/css">
        .login-form {
            width: 340px;
            margin: 50px auto;
            font-size: 15px;
        }

        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form h2 {
            margin: 0 0 15px;
        }

        .form-control, .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
<br><br><br><br>

<!-- Estructura del formulario de login -->
<div class="login-form">
    <!-- Formulario que envía los datos a login-admin.php usando el método POST -->
    <form action="login-admin.php" method="post">
        <!-- Título del formulario -->
        <h2 class="text-center">Seguridad</h2>

        <!-- Campo de entrada para el nombre de usuario -->
        <div class="form-group">
            <input type="text" name="USER" class="form-control" placeholder="Username" required="required">
        </div>

        <!-- Campo de entrada para la contraseña -->
        <div class="form-group">
            <input type="password" name="PASSWORD" class="form-control" placeholder="Password" required="required">
        </div>

        <!-- Botón para enviar el formulario -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Acceder</button>
        </div>

        <!-- Contenedor vacío para futuros elementos de diseño -->
        <div class="clearfix"></div>
    </form>
</div>

<!-- Inclusión de scripts JavaScript necesarios para el funcionamiento de la página -->
<script src="Dashboard/assets/js/codebase.core.min.js"></script>
<script src="Dashboard/assets/js/codebase.app.min.js"></script>
<script type="text/javascript" src="assets/plugin/sweetalert/sweetalert2.js"></script>
<script src="Dashboard/assets/js/pages/op_auth_signin.min.js"></script>
<script src="main/js/ValidacionesLogin.js"></script>

<!-- Inclusión del archivo para mostrar alertas -->
<?php include_once 'templates/alertas.php'; ?>

<!-- Verificación de variables GET para mostrar alertas en base al estado de la seguridad -->
<?php
// Si el parámetro 'seguridad' está presente en la URL, se muestra una advertencia
if (isset($_GET['seguridad'])) {
    echo "<script>swal({title:'Advertencia',text:'Verifica tu E-mail para confirmar el usuario',type:'error' });</script>";
}

// Si el parámetro 'verificado' está presente, se muestra un mensaje de éxito
if (isset($_GET['verificado'])) {
    echo "<script>swal({title:'Advertencia',text:'Usuario Verificado, Ahora puedes iniciar sesión',type:'success' });</script>";
}

// Si el parámetro 'success' está presente, se indica al usuario verificar su correo
if (isset($_GET['success'])) {
    echo "<script>swal({title:'Verifica tu correo electrónico',text:'Recuerda dar clic en \'(No es un correo spam)\' para que puedas recibir las notificaciones.',type:'success' });</script>";
}
?>

<!-- Script para mostrar u ocultar la contraseña en el campo de entrada -->
<script type="text/javascript">
    $('#MostrarPossword').on('click', function() {
        var cambio = document.getElementById("login-password");
        if (cambio.type == "password") {
            cambio.type = "text";
            $('.icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        } else {
            cambio.type = "password";
            $('.icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        }
    });
</script>
</body>

</html>
