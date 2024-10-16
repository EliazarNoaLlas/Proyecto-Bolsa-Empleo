<?php
session_start();

if (isset($_SESSION['iduser'])) {
    if ($_SESSION['cargo'] == "Candidato") {
        header('Location: Dashboard/Candidato/');
        exit;
    }
}

?>
<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>BOLSA LABORAL | Login</title>

    <meta name="description" content="Login - candidato BOLSA LABORAL">
    <meta name="author" content="BOLSA LABORAL Centro América">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Login - candidato BOLSA LABORAL">
    <meta property="og:site_name" content="BOLSA LABORAL">
    <meta property="og:description" content="Login - candidato BOLSA LABORAL">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://mundoempleosca.com/">
    <meta property="og:image" content="">

    <!-- Icons -->
    <link rel="shortcut icon" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
    <link rel="stylesheet" id="css-main" href="Dashboard/assets/css/codebase.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugin/sweetalert/sweetalert2.css">

    <style type="text/css">
        .btn-warning:hover {
            color: white;
            background-color: #0B3486;
            border-color: #0B3486;
        }
        .btn-warning {
            color: #0B3486;
            font-weight: bold;
        }
        @font-face {
            font-family: "Azonix";
            src: url("assets/recusosMundoEmpleo/Azonix.otf");
        }
        #titulos {
            font-family: "Azonix";
        }
        #titulos2 {
            font-family: "Azonix";
            color: #0B3486;
        }
    </style>
</head>
<body>
<div id="page-container" class="main-content-boxed">
    <main id="main-container">
        <div class="bg-image" style="background-image: url('img/portada-login/Amarillo_Candidato.jpg');">
            <div class="row mx-0 ">
                <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                    <div class="p-30 invisible" data-toggle="appear">
                        <p id="titulos2" style="font-size: 25px;"><b>PANEL DEL CANDIDATO</b></p>
                    </div>
                </div>
                <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible"
                     data-toggle="appear" data-class="animated fadeInRight">
                    <div class="content content-full">
                        <div class="px-30 py-10">
                            <h1 class="h3 font-w700 mt-30 mb-10">Bienvenido a tu Panel</h1>
                            <h2 class="h5 font-w400 text-muted mb-0">Por favor, Identifícate</h2>
                        </div>

                        <form class="js-validation-signin px-30"
                              action="main/ModelosUsuarioLogin/ValidarUsuarioCandidato.php" method="post">
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="form-material floating">
                                        <input type="text" class="form-control" id="login-username" name="login-username" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>">
                                        <label for="login-username">Correo Electrónico</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="form-material floating input-group">
                                        <input type="password" class="form-control" id="login-password" name="login-password" value="<?php if (isset($_SESSION['password'])) { echo $_SESSION['password']; } ?>">
                                        <label for="material-addon-icon2">Contraseña</label>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span id="MostrarPossword" class="fa fa-eye-slash icon"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="codigo" value="<?php if (isset($_GET['id'])) { echo $_GET['id']; } ?>">
                            <input type="hidden" name="direccionar" value="<?php if (isset($_GET['direccionar'])) { echo $_GET['direccionar']; } ?>">

                            <div class="form-group">
                                <p class="text-center">
                                    <button type="submit" class="btn btn-sm btn-hero btn-warning btn-rounded" id="validar" name="validar">
                                        <i class="si si-login mr-10"></i>Iniciar Sesión
                                        <center>
                                            <div id="respuesta"></div>
                                        </center>
                                    </button>
                                </p>

                                <div class="mt-30 text-center">
                                    <a class="link-effect text-dark mr-10 mb-5 d-inline-block" href="recuperacion">
                                        <b style="font-size: 16px;"><i class="fa fa-warning mr-5"></i> ¿Olvidaste la contraseña?</b>
                                    </a>
                                    <a class="link-effect text-dark mr-10 mb-5 d-inline-block" href="crear-cuenta-candidato">
                                        <b style="font-size: 16px;"><i class="fa fa-user-plus mr-5"></i> Crear Cuenta</b>
                                    </a>
                                    <a class="link-effect text-dark mr-10 mb-5 d-inline-block" href="candidato">
                                        <b style="font-size: 16px;"><i class="si si-arrow-left mr-5"></i> Retroceder</b>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="Dashboard/assets/js/codebase.core.min.js"></script>
<script src="Dashboard/assets/js/codebase.app.min.js"></script>
<script type="text/javascript" src="assets/plugin/sweetalert/sweetalert2.js"></script>
<script src="Dashboard/assets/js/pages/op_auth_signin.min.js"></script>
<script src="main/js/ValidacionesLogin.js"></script>

<?php include_once 'templates/alertas.php'; ?>

<?php if (isset($_GET['seguridad'])) {
    echo "<script>swal({title:'Advertencia', text:'Usuario denegado, verifica tu correo electrónico.', type:'error' });</script>";
} ?>

<?php if (isset($_GET['verificado'])) {
    echo "<script>swal({title:'Usuario Verificado', text:'Ahora puedes iniciar sesión.', type:'success' });</script>";
} ?>

<script type="text/javascript">
    $('#MostrarPossword').on('click', function () {
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
