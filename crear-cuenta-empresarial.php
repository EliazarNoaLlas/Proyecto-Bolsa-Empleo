<?php include_once 'templates/head.php'; ?>
<title>BOLSA LABORAL</title>
<?php include_once 'templates/style.php'; ?>
<?php include_once 'templates/header.php'; ?>
<?php include_once 'templates/leftmunu.php'; ?>

<style type="text/css">

    .section-title h1 {
        color: #0B3486;
    }

    #imgbanner {

        background: url('img/bannercadaservicio/Amarillo/Servicio_Empresarial.jpg');
        background-repeat: no-repeat;
        background-size: cover;

    }


</style>

<div class="breadcrumbs" id="imgbanner">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <!-- Bread Menu -->
                    <!-- Bread Title -->
                    <div class="bread-title"><h2 id="titulos">Crear Cuenta de Empresa.</h2></div>
                </div>
            </div>
        </div>
    </div>

</div>


<section class="contact-us section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-12">
                <!-- Contact Form -->
                <div class="contact-form-area m-top-30">
                    <h4 id="titulos">Crear cuenta</h4>
                    <hr>
                    <!--                    <p>Los campos con asterisco (*) son obligatorios y deben ser completados para continuar con el proceso de registro.</p>-->
                    <br>
                    <!--                    <p> La contraseña debe tener entre 8 carácteres y de preferencia utilice  dígitos, minúscula o al menos una mayúscula. Puede tener otros símbolos (@,$,!,%,*,#,?,&).</p>-->

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <div class="icon"><i class="fa fa-user"></i></div>
                                <input class="form-control" id="Nombres" name="Nombres" type="text"
                                       placeholder="Nombres*">
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="form-group">
                                <div class="icon"><i class="fa fa-envelope"></i></div>
                                <input class="form-control" id="correo" name="correo" type="text"
                                       placeholder="Correo Electrónico:*">
                                <div id="respuesta"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <div class="icon"><i class="fa fa-tag"></i></div>
                                <input class="form-control valid" id="password" name="password" type="password"
                                       placeholder="Contraseña*">
                                <span id="passstrength"></span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <div class="icon"><i class="fa fa-tag"></i></div>
                                <input class="form-control valid" id="confirmarPass" name="confirmarPass"
                                       type="password" placeholder="confirmar la contraseña*">
                                <div id="verificaPassword"></div>
                            </div>
                        </div>


                        <div class="col-lg-6 col-md-6 col-12">
                            <br>
                            <div class="form-check">
                                <input type="checkbox" id="mostrar_contrasena" title="clic para mostrar contraseña"/>
                                &nbsp;&nbsp;Mostrar Contraseña

                            </div>
                        </div>


                        <div class="col-lg-6 col-md-6 col-12">
                            <br>
                            <div class="form-check" style="margin-left: 18px;">

                                <input class="form-check-input" type="checkbox" value="" id="validarContrato">
                                Acepto los Términos & condiciones

                                <br><br>
                                <a href="terminos-condiciones" target="_blank">Leer terminos y servicios</a>

                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group button">
                                <input type="button" class="bizwheel-btn theme-2" id="crear-cuenta"
                                       value="Crear Cuenta">
                            </div>
                        </div>


                    </div>
                </div>
                <!--/ End contact Form -->
            </div>
        </div>
    </div>
</section>


<?php include_once 'templates/footer.php'; ?>
<?php include_once 'templates/script.php'; ?>
<script type="text/javascript">


    $(document).ready(function () {
        $('#mostrar_contrasena').click(function () {

            if ($('#mostrar_contrasena').is(':checked')) {
                $('#password').attr('type', 'text');
            } else {
                $('#password').attr('type', 'password');
            }

            if ($('#mostrar_contrasena').is(':checked')) {
                $('#confirmarPass').attr('type', 'text');
            } else {
                $('#confirmarPass').attr('type', 'password');
            }


        });
    });

    // Funcion para validar el correo
    $(document).ready(function () {

        $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');

        var Email = $('#correo').val();
        buscar_datos(Email);

        $('#correo').on('change', function () {

            var Email = $('#correo').val();
            buscar_datos(Email);

        });

        //keydown, keyup
        $('#crear-cuenta').on('click', function () {

            if ($('#validarContrato').is(':checked')) {

                var regcampos = /^[A-Za-z _ñÑáéíóúÁÉÍÓÚ]*[A-Za-zñÑáéíóúÁÉÍÓÚ][A-Za-z _ñÑáéíóúÁÉÍÓÚ]*$/; // Validar campos
                var regemail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/; // Expresion Regular email

                var nombres = $('#Nombres').val();
                var apellidos = $('#Apellidos').val(); // Ahora siempre es "N/A"
                var Email = $('#correo').val();
                var password1 = $('#password').val();
                var password2 = $('#confirmarPass').val();
                var validarEmail = $('#validez').val();

                if (nombres != "" && Email != "" && password1 != "" && password2 != "") {

                    if (!regcampos.test(nombres)) {
                        swal({
                            title: 'Error',
                            text: 'No se permite caracteres especiales en el nombre',
                            type: 'warning'
                        });
                        return false;
                    } else if (nombres.length <= 3) {
                        swal({
                            title: 'Advertencia',
                            text: 'El campo nombre debe tener mínimo 4 caracteres',
                            type: 'warning'
                        });
                        return false;
                    } else if (!regemail.test(Email)) {
                        swal({title: 'Advertencia', text: 'El correo electrónico no es válido', type: 'warning'});
                        return false;
                    } else if (validarEmail == 1) {
                        swal({
                            title: 'Advertencia',
                            text: 'El correo electrónico ya está en uso',
                            type: 'warning'
                        });
                        return false;
                    } else if (password1.length <= 7) {
                        swal({
                            title: 'Advertencia',
                            text: 'La contraseña debe tener mínimo 8 caracteres',
                            type: 'warning'
                        });
                        return false;
                    } else if (password1 != password2) {
                        swal({title: 'Advertencia', text: 'Las contraseñas no coinciden', type: 'warning'});
                        return false;
                    } else {

                        $.ajax({
                            url: 'main/UsuarioCuentas/cuenta-empresa.php',
                            type: 'POST',
                            dataType: 'html',
                            data: {
                                correo: Email,
                                Cargo: 'Empresa',
                                Nombre: nombres,
                                Apellidos: 'N/A',  // Valor por defecto
                                password: password1
                            },

                            beforeSend: function () {
                                swal({
                                    title: "Cargando...",
                                    text: "Por favor espere",
                                    imageUrl: "assets/img/icono/loader.gif",
                                    button: false,
                                    closeOnClickOutside: false,
                                    closeOnEsc: false,
                                    imageWidth: 100,
                                    imageHeight: 100,
                                    showCancelButton: false,
                                    showConfirmButton: false
                                });
                            }

                        })
                            .done(function (response) {
                                if (response == 1) {
                                    swal({
                                        title: 'Advertencia',
                                        text: 'El correo electrónico ya está en uso',
                                        type: 'warning'
                                    });
                                } else if (response == 2) {
                                    $('#Nombres').val("");
                                    $('#Apellidos').val("N/A");
                                    $('#correo').val("");
                                    $('#password').val("");
                                    $('#confirmarPass').val("");

                                    $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');
                                    swal({
                                        title: "Se ha creado la cuenta como Empresa",
                                        text: "La cuenta se ha creado correctamente",
                                        type: "success",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                        .then((willDelete) => {
                                            if (willDelete) {
                                                setTimeout("location.href='login-empresa?success=1'");
                                            }
                                        });
                                } else if (result == 3) {
                                    swal({title: 'Advertencia', text: 'Intente de nuevo', type: 'warning'});
                                } else {
                                    alert(result);
                                }
                            });
                    }
                }
            }
        });


        $('#password').keyup(function (e) {

            var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
            var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
            var enoughRegex = new RegExp("(?=.{6,}).*", "g");
            var Password = $("#password").val();

            if (Password == "") {
                $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');
            } else {

                if (false == enoughRegex.test($(this).val())) {
                    $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>');
                } else if (strongRegex.test($(this).val())) {
                    $('#passstrength').className = 'ok';
                    $('#passstrength').html('<div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>');
                } else if (mediumRegex.test($(this).val())) {
                    $('#passstrength').className = 'alert';
                    $('#passstrength').html('<div class="progress"><div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>');
                } else {
                    $('#passstrength').className = 'error';
                    $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div></div>');

                }


                return true;

            }


        });


        $('#confirmarPass').keyup(function (e) {

            var passwordConfir = $("#confirmarPass").val();
            var Password = $("#password").val();

            if (Password == passwordConfir) {
                $('#verificaPassword').html('');
                VerificarPassword = 0;
            } else {
                $('#verificaPassword').html('<div class="alert alert-warning alert-dismissible fade show" role="alert"> Contraseña no coinciden  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>  </div>');
                VerificarPassword = 1;
                return false;
            }

        });


        $('#correo').keyup(function (e) {
            var Email = $('#correo').val();

            if (Email != "") {
                buscar_datos(Email);
            }
        });


        function buscar_datos(consulta) {
            $.ajax({
                url: 'main/ModelosUsuarioCuentas/ValidarUsuarioCorreo.php',
                type: 'POST',
                dataType: 'html',
                data: {consulta: consulta},
            })
                .done(function (respuesta) {
                    $("#respuesta").html(respuesta);
                })
                .fail(function () {
                    console.log("error");
                });
        }


    });


</script>