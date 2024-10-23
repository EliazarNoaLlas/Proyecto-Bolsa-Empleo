<?php
// Verifica si no existe un parámetro 'error' en la URL, lo que indica que no hubo un error previo en el login
if (!isset($_GET['error'])) {

    // Captura los valores del formulario si están disponibles
    $user = $_POST['USER'] ?? '';
    $password = $_POST['PASSWORD'] ?? '';

    // Verifica si las credenciales coinciden con un administrador
    if ($user === "user-admin" && $password === 'P@as$$w0rd246') {
        // Redirigir o manejar la lógica cuando el usuario es administrador
    } else {
        // Redirige si las credenciales son incorrectas
        header("Location: seguridad-soporte");
        exit();
    }
}

// Inicia la sesión para manejar la autenticación
session_start();
?>

<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>BOLSA LABORAL | Login</title>
    <meta name="description" content="Login - soporte BOLSA LABORAL">
    <meta name="author" content="BOLSA LABORAL Centro América">
    <meta name="robots" content="noindex, nofollow">

    <!-- Metadatos para redes sociales (Open Graph) -->
    <meta property="og:title" content="Login - Soporte de BOLSA LABORAL CA">
    <meta property="og:site_name" content="BOLSA LABORAL">
    <meta property="og:description" content="Login - soporte BOLSA LABORAL">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://mundoempleosca.com/">
    <meta property="og:image" content="">

    <!-- Iconos -->
    <link rel="shortcut icon" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/recusosMundoEmpleo/lupaMundoEmpleo.png">

    <!-- Estilos -->
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

        #titulos, #titulos2 {
            font-family: "Azonix";
        }

        #titulos2 {
            color: #0B3486;
        }
    </style>
</head>
<body>
<div id="page-container" class="main-content-boxed">
    <main id="main-container">
        <div class="bg-image" style="background-image: url('img/portada-login/soporte.jpg');">
            <div class="row mx-0">
                <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                    <div class="p-30 invisible" data-toggle="appear">
                        <p id="titulos2" style="font-size: 25px;"><b>PANEL DEL ADMINISTRADOR</b></p>
                    </div>
                </div>
                <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible"
                     data-toggle="appear" data-class="animated fadeInRight">
                    <div class="content content-full">
                        <div class="px-30 py-10 text-center">
                            <h1 class="h3 font-w700 mt-30 mb-10">Soporte técnico</h1>
                            <h2 class="h5 font-w400 text-muted mb-0">Por favor, Identifícate</h2>
                        </div>

                        <!-- Formulario de inicio de sesión -->
                        <form class="js-validation-signin px-30"
                              action="main/ModelosUsuarioLogin/ValidarUsuarioAdmin.php" method="post">
                            <div class="form-group">
                                <div class="form-material floating">
                                    <input type="text" class="form-control" id="login-username" name="login-username"
                                           value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>" required>
                                    <label for="login-username">Correo electrónico</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-material floating input-group">
                                    <input type="password" class="form-control" id="login-password" name="login-password"
                                           value="<?= isset($_SESSION['password']) ? $_SESSION['password'] : '' ?>" required>
                                    <label for="login-password">Contraseña</label>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span id="togglePassword" class="fa fa-eye-slash icon"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-sm btn-hero btn-warning btn-rounded" id="validar" name="validar">
                                    <i class="si si-login mr-10"></i>Iniciar Sesión
                                </button>
                                <div id="respuesta"></div>
                            </div>

                            <div class="text-center mt-30">
                                <a class="link-effect text-muted" href="recuperacion">
                                    <i class="fa fa-warning mr-5"></i> ¿Olvidaste la contraseña?
                                </a>
                                <a class="link-effect text-muted" href="index">
                                    <i class="si si-arrow-left mr-5"></i>Regresar
                                </a>
                            </div>
                        </form>
                        <!-- Fin del formulario -->
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="Dashboard/assets/js/codebase.core.min.js"></script>
<script src="Dashboard/assets/js/codebase.app.min.js"></script>
<script src="assets/plugin/sweetalert/sweetalert2.js"></script>

<!-- Mostrar y ocultar contraseña -->
<script type="text/javascript">
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById("login-password");
        const icon = this;
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
</script>

<?php include_once 'templates/alertas.php'; ?>

<?php if (isset($_GET['seguridad'])): ?>
    <script>
        swal({ title: 'Advertencia', text: 'Verifica tu E-mail para confirmar el usuario', type: 'error' });
    </script>
<?php endif; ?>

<?php if (isset($_GET['verificado'])): ?>
    <script>
        swal({ title: 'Usuario Verificado', text: 'Ahora puedes iniciar sesión', type: 'success' });
    </script>
<?php endif; ?>

</body>
</html>
