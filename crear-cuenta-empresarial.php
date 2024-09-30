<?php include_once 'templates/head.php'; ?>
<title>MUNDO EMPLEO | CENTRO AMERICA</title>
<?php include_once 'templates/style.php'; ?>
<?php include_once 'templates/header.php'; ?>
<?php include_once 'templates/leftmunu.php'; ?>

<style type="text/css">

	.section-title h1{
		color: #0B3486;
	}

	#imgbanner{

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
					<div class="bread-title"><h2 id="titulos">Crear Cuenta Empresarial.</h2></div>
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
					<h4 id="titulos">Regístrate Gratis</h4> <hr>
					<p>Los campos con asterisco (*) son obligatorios y deben ser completados para continuar con el proceso de registro.</p>
					<br>
					<p> La contraseña debe tener entre 8 carácteres y de preferencia utilice  dígitos, minúscula o al menos una mayúscula. Puede tener otros símbolos (@,$,!,%,*,#,?,&).</p>
					
					<div class="row">




						<div class="col-lg-6 col-md-6 col-12">
							<div class="form-group">
								<div class="icon"><i class="fa fa-user"></i></div>
								<input class="form-control" id="Nombres" name="Nombres" type="text"  placeholder="Nombres*"  >
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-12">
							<div class="form-group">
								<div class="icon"><i class="fa fa-user"></i></div>
								<input class="form-control valid" id="Apellidos"   name="Apellidos" type="text"  placeholder="Apellidos*"  >
							</div>
						</div>

						<div class="col-lg-12 col-md-6 col-12">
							<div class="form-group">
								<div class="icon"><i class="fa fa-envelope"></i></div>
								<input class="form-control" id="correo" name="correo" type="text" placeholder="Correo Electrónico:*" >
								<div id="respuesta"></div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-12">
							<div class="form-group">
								<div class="icon"><i class="fa fa-tag"></i></div>
								<input class="form-control valid" id="password" name="password" type="password" placeholder="Contraseña*"  >
								<span id="passstrength"></span>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-12">
							<div class="form-group">
								<div class="icon"><i class="fa fa-tag"></i></div>
								<input class="form-control valid" id="confirmarPass" name="confirmarPass" type="password"  placeholder="confirmar la contraseña*">
								<div id="verificaPassword"></div>
							</div>
						</div>



						<div  class="col-lg-6 col-md-6 col-12">
							<br>
							<div class="form-check" >
								<input  type="checkbox" id="mostrar_contrasena" title="clic para mostrar contraseña"/>
								&nbsp;&nbsp;Mostrar Contraseña

							</div>
						</div>


						<div  class="col-lg-6 col-md-6 col-12">
							<br>
							<div class="form-check" style="margin-left: 18px;">
							
								<input class="form-check-input" type="checkbox" value="" id="validarContrato">
								Acepto los Términos & condiciones

								<br><br>
								<a href="terminos-condiciones" target="_blank"  >Leer terminos y servicios</a>
								
							</div>
						</div>


						<div class="col-12">
							<div class="form-group button">
								<input type="button"class="bizwheel-btn theme-2" id="crear-cuenta"  value="Crear Cuenta">
							</div>
						</div>


					</div>
				</div>
				<!--/ End contact Form -->
			</div>
			<div class="col-lg-5 col-md-5 col-12">
				<div class="contact-box-main m-top-30">

					<div class="contact-title">
						<p style="text-align: center;">
							<img src="assets/recusosMundoEmpleo/logo.png" class="img-fluid">
						</p>
					</div>


					<div class="contact-title">
						<h2>CONTÁCTENOS:</h2>						
					</div>
					<!-- Single Contact -->
					<div class="single-contact-box">
						<div class="c-icon"><i class="fa fa-clock-o"></i></div>
						<div class="c-text">
							<h4>Hora de apertura</h4>
							<p>Lunes a Viernes<br>08AM - 10PM (Todas las dias)</p>
						</div>
					</div>
					<!--/ End Single Contact -->
					<!-- Single Contact -->
					<div class="single-contact-box">
						<div class="c-icon"><i class="fa fa-phone"></i></div>
						<div class="c-text">
							<h4>Llámanos ahora</h4>
							<p>Tel.: +503 2222 2222<br> Mob.: +503 654 3451</p>
						</div>
					</div>
					<!--/ End Single Contact -->
					<!-- Single Contact -->
					<div class="single-contact-box">
						<div class="c-icon"><i class="fa fa-envelope-o"></i></div>
						<div class="c-text">
							<h4>Envíanos un correo electrónico</h4>
							<p>contact@bizwheel.com<br>info@bizwheel.com</p>
						</div>
					</div>
					<!--/ End Single Contact -->
					<div class="button">
						¿Ya tienes una cuenta?<br>
						<a href="login-empresa" class="bizwheel-btn theme-1">Iniciar Sesión<i class="fa fa-angle-right"></i></a> <br>
						
					</div>
				</div>
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
$(document).ready(function() {

    $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');

    var Email = $('#correo').val();
    buscar_datos(Email);

    $('#correo').on('change', function() {

        var Email = $('#correo').val();
        buscar_datos(Email);

});

// Evento click en el botón de crear cuenta
$('#crear-cuenta').on('click', function() {

    // Verifica si se ha aceptado el checkbox de términos y condiciones
    if ($('#validarContrato').is(':checked')) {

        // Expresión regular para validar que solo se ingresen letras en nombres y apellidos
        var regcampos = /^[A-Za-z _ñÑáéíóúÁÉÍÓÚ]*[A-Za-zñÑáéíóúÁÉÍÓÚ][A-Za-z _ñÑáéíóúÁÉÍÓÚ]*$/;

        // Expresión regular para validar el formato del correo electrónico
        var regemail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        // Obtener los valores de los campos del formulario
        var nombres = $('#Nombres').val();
        var apellidos = $('#Apellidos').val();
        var Email = $('#correo').val();
        var password1 = $('#password').val();
        var password2 = $('#confirmarPass').val();
        var validarEmail = $('#validez').val();

        // Verifica que todos los campos estén completos
        if (nombres != "" && apellidos != "" && Email != "" && password1 != "" && password2 != "") {

            // Verifica que los nombres no contengan caracteres especiales
            if (!regcampos.test(nombres)) {
                swal({ title: 'Error', text: 'No se permite caracteres especiales', type: 'warning' });
                return false;

                // Verifica que los apellidos no contengan caracteres especiales
            } else if (!regcampos.test(apellidos)) {
                swal({ title: 'Error', text: 'No se permite caracteres especiales', type: 'warning' });
                return false;

            } else {
                // Verifica que el nombre tenga al menos 4 caracteres
                if (nombres.length <= 3) {
                    swal({ title: 'Advertencia', text: 'El campo nombre debe tener mínimo 4 caracteres', type: 'warning' });
                    return false;

                    // Verifica que los apellidos tengan al menos 4 caracteres
                } else if (apellidos.length <= 3) {
                    swal({ title: 'Advertencia', text: 'El campo apellidos debe tener mínimo 4 caracteres', type: 'warning' });
                    return false;

                    // Verifica el formato del correo electrónico
                } else if (!regemail.test(Email)) {
                    swal({ title: 'Advertencia', text: 'El correo electrónico no es válido', type: 'warning' });
                    return false;

                    // Verifica si el correo electrónico ya está en uso
                } else if (validarEmail == 1) {
                    swal({ title: 'Advertencia', text: 'El correo electrónico ya está en uso', type: 'warning' });
                    return false;

                    // Verifica que la contraseña tenga al menos 8 caracteres
                } else if (password1.length <= 7) {
                    swal({ title: 'Advertencia', text: 'La contraseña debe tener al menos 8 caracteres', type: 'warning' });
                    return false;

                    // Verifica que ambas contraseñas coincidan
                } else if (password1 != password2) {
                    swal({ title: 'Advertencia', text: 'Las contraseñas no coinciden', type: 'warning' });
                    return false;

                } else {
                    // Si todas las validaciones son correctas, se envía el formulario mediante AJAX
                    $.ajax({
                        url: 'main/UsuarioCuentas/cuenta-empresa.php',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            correo: Email,
                            Cargo: 'Empresa',
                            Nombre: nombres,
                            Apellidos: apellidos,
                            password: password1
                        },

                        // Muestra un mensaje de "Cargando" mientras se realiza la petición
                        beforeSend: function() {
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

                        // Se ejecuta cuando la petición AJAX finaliza correctamente
                        .done(function(response) {
                            var result = response;

                            // Si el correo ya está en uso, muestra una advertencia
                            if (result == 1) {
                                swal({ title: 'Advertencia', text: 'El correo electrónico ya está en uso', type: 'warning' });

                                // Si la cuenta se creó correctamente
                            } else if (result == 2) {

                                // Limpia los campos del formulario
                                $('#Nombres').val("");
                                $('#Apellidos').val("");
                                $('#correo').val("");
                                $('#password').val("");
                                $('#confirmarPass').val("");
                                $('#validez').val("");

                                // Reinicia la barra de progreso de fuerza de la contraseña
                                $('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');

                                // Muestra un mensaje de éxito
                                swal({
                                    title: "Se ha creado la cuenta como Empresa",
                                    text: "Tu cuenta ha sido creada exitosamente. Puedes iniciar sesión con tus credenciales ahora mismo."
                                    type: "success",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                    // Redirige al usuario al login después de cerrar el mensaje
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            setTimeout("location.href='login-empresa?success=1'");
                                        }
                                    });

                                // Si hubo un error en el servidor, muestra un mensaje
                            } else if (result == 3) {
                                swal({ title: 'Advertencia', text: 'Intente de nuevo', type: 'warning' });
                            } else {
                                alert(result);
                            }
                        })

                        // Se ejecuta si hubo un error en la petición AJAX
                        .fail(function(request, errorType, errorMessage) {
                            swal({ title: 'Alerta', text: 'Intente de nuevo para procesar el envío', type: 'error' });
                            console.log(errorType);
                            alert(errorMessage);
                        })

                        // Se ejecuta siempre que la petición AJAX finaliza
                        .always(function() {
                            $('#myModal').modal('hide');
                        });
                }
            }

            // Si faltan campos por completar, muestra una advertencia
        } else {
            swal({ title: 'Advertencia', text: 'Complete todos los campos', type: 'warning' });
            return false;
        }

        // Si no se aceptaron los términos y condiciones, muestra una advertencia
    } else {
        swal({ title: 'Advertencia', text: 'No ha seleccionado Términos & condiciones', type: 'warning' });
    }
});

$('#password').keyup(function(e) {

	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	var Password =$("#password").val();

	if(Password == "")
	{
		$('#passstrength').html('<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');
	}else{

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



$('#confirmarPass').keyup(function(e) {

	var passwordConfir =$("#confirmarPass").val();
	var Password =$("#password").val();

	if (Password == passwordConfir) 
	{
		$('#verificaPassword').html('');
		VerificarPassword = 0;
	}else
	{
		$('#verificaPassword').html('<div class="alert alert-warning alert-dismissible fade show" role="alert"> Contraseña no coinciden  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>  </div>');
		VerificarPassword = 1;
		return false;
	}

});


$('#correo').keyup(function(e) {
	var Email = $('#correo').val();

	if (Email != "") {
		buscar_datos(Email);
	}
});



function buscar_datos(consulta){
	$.ajax({
		url: 'main/ModelosUsuarioCuentas/ValidarUsuarioCorreo.php' ,
		type: 'POST' ,
		dataType: 'html',
		data: {consulta: consulta},
	})
	.done(function(respuesta){
		$("#respuesta").html(respuesta);
	})
	.fail(function(){
		console.log("error");
	});
}



});


</script>