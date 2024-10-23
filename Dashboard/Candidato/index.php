<?php
// Incluir archivos necesarios para la conexión a la base de datos y consultas
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once 'templates/head.php';

// Crear una instancia de la clase Consultas para manejar las operaciones con la base de datos
$Conexion = new Consultas();

// Incluir archivo para validar el perfil del usuario
include_once 'templates/validar-perfil.php';

// Consultar el estado de la cuenta del usuario
$sqlEstadoCuenta = "SELECT `Estado` FROM `usuario_perfil` WHERE `IDUsuario` = ?";
$stmtValidarPerfil = $Conexion->ejecutar_consulta_simple_Where($sqlEstadoCuenta, $IDUser);

// Verificar el estado del perfil del usuario
//while ($item = $stmtValidarPerfil->fetch()) {
//    $verificaPerfil = $item['Estado'];
//}

// Redirigir al usuario a completar su perfil si no está activo
//if ($verificaPerfil != "Activo") {
//    // Verificar si falta completar alguna sección del perfil y redirigir a la correspondiente
//    if ($VerificaUsuarioSinPerfil == 0) {
//        echo "<script>location.href='perfil?notificar=1';</script>";
//    } else if ($VerificaUsuarioEducacion == 0) {
//        echo "<script>location.href='educacion?notificar=1';</script>";
//    } else if ($VerificaUsuarioExperiencia == 0) {
//        echo "<script>location.href='experiencia?notificar=1';</script>";
//    } else if ($VerificaUsuarioHabilidades == 0) {
//        echo "<script>location.href='habilidades?notificar=1';</script>";
//    } else if ($VerificaUsuarioIdiomas == 0) {
//        echo "<script>location.href='habilidades?notificar=1';</script>";
//    } else {
//        echo "<script>location.href='documentos?notificar=1';</script>";
//    }
//}

// Consultar conteo de varias secciones del perfil para mostrar en los cuadros informativos
$resultPerfil = $Conexion->ejecutar_consulta_conteo("usuario_perfil", "IDUsuario", $IDUser);
$resultEducacion = $Conexion->ejecutar_consulta_conteo("usuario_educacion", "IDUsuario", $IDUser);
$resultExperiencia = $Conexion->ejecutar_consulta_conteo("usuario_experiencia", "IDUsuario", $IDUser);
$ResultIdiomas = $Conexion->ejecutar_consulta_conteo("usuarios_idiomas", "IDUsuario", $IDUser);
$ResultTecnicas = $Conexion->ejecutar_consulta_conteo("usuarios_habilidades", "IDUsuario", $IDUser);
$resultHabilidades = $Conexion->ejecutar_consulta_conteo("usuarios_conocimentos", "IDUsuario", $IDUser);
$resultReferencia = $Conexion->ejecutar_consulta_conteo("usuario_referencia", "IDUsuario", $IDUser);

// Calcular el total de habilidades sumando conocimientos técnicos, idiomas y otras habilidades
$totalHabilidades = $resultHabilidades + $ResultTecnicas + $ResultIdiomas;

// Inicializar porcentaje de perfil completado
$PorcentajePerfil = 0;

// Incrementar el porcentaje del perfil según las secciones completadas
if (true) {
    $PorcentajePerfil += 14.28571428571429; // Perfil básico completado
}
if (true) {
    $PorcentajePerfil += 14.28571428571429; // Educación completada
}
if (true) {
    $PorcentajePerfil += 14.28571428571429; // Experiencia laboral completada
}
if (true) {
    $PorcentajePerfil += 14.28571428571429; // Idiomas completados
}
if (true >= 1) {
    $PorcentajePerfil += 14.28571428571429; // Habilidades técnicas completadas
}
if (true) {
    $PorcentajePerfil += 14.28571428571429; // Otras habilidades completadas
}
if (true) {
    $PorcentajePerfil += 14.28571428571429; // Referencias completadas
}

// Consultar el número de visitas de las empresas al perfil del usuario
$resultVisitas = $Conexion->ejecutar_consulta_conteo("usuarios_visitas", "IDUsuario", $IDUser);

// Consultar las visitas de las empresas al perfil del usuario y los datos de las empresas
$sql = "SELECT EP.IDEmpresa, EP.Confidencial, EP.Nombre, EP.logo, UV.visitas 
        FROM usuarios_visitas UV 
        INNER JOIN empresa_perfil EP ON UV.IDEmpresa = EP.IDEmpresa 
        LEFT JOIN usuarios_cuentas UC ON UV.IDUsuario = UC.IDUsuario 
        WHERE UV.IDUsuario = $IDUser";
$stmtVisitas = $Conexion->ejecutar_consulta_simple($sql);

// Consultar la última actualización del perfil del usuario
$sql2 = "SELECT `UltimaActualizacion` FROM `usuario_perfil` WHERE IDUsuario = ?";
$stmt2 = $Conexion->ejecutar_consulta_simple_Where($sql2, $IDUser);
while ($item = $stmt2->fetch()) {
    $CVActualizacion = $item['UltimaActualizacion'];
}

// Consultar la última conexión del usuario
$sql3 = "SELECT `UltimaConexion` FROM `usuarios_cuentas` WHERE `IDUsuario` = ?";
$stmt3 = $Conexion->ejecutar_consulta_simple_Where($sql3, $IDUser);
while ($item = $stmt3->fetch()) {
    $UltimaConexion = $item['UltimaConexion'];
}

// Actualizar la última conexión si es la primera vez que se conecta o si es un nuevo día
$fechaActual = date('d-m-Y');
if ($UltimaConexion == "") {
    $sql4 = "UPDATE `usuarios_cuentas` SET `UltimaConexion` = :UltimaConexion WHERE `IDUsuario` = :IDUsuario";
    $stmt = Conexion::conectar()->prepare($sql4);
    $stmt->bindParam(':IDUsuario', $IDUser, PDO::PARAM_STR);
    $stmt->bindParam(':UltimaConexion', $fechaActual, PDO::PARAM_STR);
    $stmt->execute();
} else if ($UltimaConexion != $fechaActual) {
    $sql4 = "UPDATE `usuarios_cuentas` SET `UltimaConexion` = :UltimaConexion WHERE `IDUsuario` = :IDUsuario";
    $stmt = Conexion::conectar()->prepare($sql4);
    $stmt->bindParam(':IDUsuario', $IDUser, PDO::PARAM_STR);
    $stmt->bindParam(':UltimaConexion', $fechaActual, PDO::PARAM_STR);
    $stmt->execute();
}

// Consultar el número total de ofertas aplicadas por el usuario con estado 'Enviado'
$sql5 = "SELECT COUNT(`IDOfertaTrabajo`) AS 'TotalAplicado' 
         FROM usuario_postulaciones 
         WHERE IDUsuario = ? AND `Estado` = 'Enviado'";
$stmt5 = Conexion::conectar()->prepare($sql5);
$stmt5->execute(array($IDUser));

while ($item = $stmt5->fetch()) {
    $TotalAplicado = $item['TotalAplicado'];
}

// Consultar el número total de ofertas vistas por el usuario con estado 'Visto'
$sql6 = "SELECT COUNT(`IDOfertaTrabajo`) AS 'TotalVisto' 
         FROM usuario_postulaciones 
         WHERE IDUsuario = ? AND `Estado` = 'Visto'";
$stmt6 = Conexion::conectar()->prepare($sql6);
$stmt6->execute(array($IDUser));

while ($item = $stmt6->fetch()) {
    $TotalVisto = $item['TotalVisto'];
}
?>
    <title>Candidato | Home</title>
<?php
// Incluir los archivos de estilo y las plantillas del menú y el encabezado
include_once 'templates/styles.php';
include_once 'templates/MenuRight.php';
include_once 'templates/MenuLeft.php';
include_once 'templates/header.php';
?>

    <style type="text/css">

        #imgbanner {

            background: url('../assets/media/photos/Incio_de_Sesion_Usuario.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 200px;
        }


    </style>

    <main id="main-container">

        <div class="bg-image bg-image-bottom" id="imgbanner">
            <div class="">
                <div class="content content-top text-center overflow-hidden">
                    <div class="pt-40 pb-20">

                        <h2 class="h2 font-w400  invisible" id="titulos" style="color: white;" data-toggle="appear"
                            data-class="animated fadeInUp">¡ Bienvenido a tu panel <br> <?php echo $PrimerNombre[0] ?> !
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-right:2%; margin-left:2%;">

            <div class="row gutters-tiny invisible" data-toggle="appear" data-class="animated bounceInLeft">

                <div class="col-6 col-md-4 col-xl-2">
                    <a class=" text-center" href="actualizar-cuenta">
                        <div class="block-content ribbon ribbon-bookmark ribbon-crystal  cuadros">
                            <p class="mt-5">
                                <i class="si si-user-follow fa-3x text-white"></i>
                            </p>
                            <p class="font-w600 text-white" style="margin-bottom: 33px;">Cuenta <br> Usuario</p>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <a class=" text-center" href="../../todas-las-ofertas.php">
                        <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left cuadros">
                            <p class="mt-5">
                                <img src="../assets/iconos/candidato/Buscar_Empleo.png">
                            </p>
                            <p class="font-w600 text-white">Buscar <br>Empleos</p>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <a class=" text-center" href="alertas">
                        <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left cuadros">
                            <p class="mt-5">
                                <img src="../assets/iconos/candidato/Alertas_Trabajo.png">
                            </p>
                            <p class="font-w600 text-white">Alertas <br> Trabajos</p>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <a class=" text-center" href="empresas">
                        <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left cuadros">
                            <p class="mt-5">
                                <img src="../assets/iconos/candidato/Area_de_Empresa.png">
                            </p>
                            <p class="font-w600 text-white">Listado <br> Empresa</p>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <a class=" text-center" href="postulaciones">
                        <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left cuadros">
                            <p class="mt-5">
                                <img src="../assets/iconos/candidato/Mis_Postulaciones.png">
                            </p>
                            <p class="font-w600 text-white">Mis <br> Postulaciones</p>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <a class=" text-center" href="postulaciones">
                        <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left cuadros">
                            <p class="mt-5">
                                <img src="../assets/iconos/candidato/Mis_Postulaciones.png">
                            </p>
                            <p class="font-w600 text-white">Mis <br> Postulaciones</p>
                        </div>
                    </a>
                </div>

            </div>

            <!--nuevo contenido-->

            <div class="row gutters-tiny invisible" data-toggle="appear" data-class="animated bounceInRight">

                <div class="col-md-6">
                    <div class="block block-transparent bg-gd-dusk">
                        <div class="block-content block-content-full">
                            <div class="block block-transparent  d-flex align-items-center w-100" class="cuadros">
                                <div class="block-content block-content-full">

                                    <center>
                                        <a class="img-link" href="cv">
                                            <img class="img-avatar img-avatar-thumb"
                                                 style="width: 135px; height: 135px;"
                                                 src="../../assets/img/user/<?php echo $FotoUser ?>" alt="userfoto">
                                        </a>
                                    </center>


                                    <div class="block-content block-content-full block-content-sm text-center">
                                        <div class="font-w600 text-white mb-5">
                                            <b><?php echo $NombresUser . " " . $ApellidosUser ?></b></div>
                                        <div class="font-size-sm text-white"><b><?php echo $CorreoUser ?></b></div>
                                    </div>


                                    <div class="block-content text-center">
                                        <div class="row items-push">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <br>
                                                <div class="mb-5">
                                                    <button type="button" data-toggle="modal" data-target="#modal-terms"
                                                            class="btn btn-hero btn-rounded btn-noborder btn-alt-primary mr-5 mb-5">
                                                        <i class="si si-eye fa-2x5 text-white"> </i> <?php echo $resultVisitas ?>
                                                        visitas
                                                    </button>

                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <br>
                                                <button class="btn btn-hero btn-rounded btn-noborder btn-alt-primary mr-5 mb-5"
                                                        data-toggle="modal" data-target="#modal-terms2">Subir Foto
                                                </button>
                                                <br><br>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="block block-transparent bg-gd-dusk">
                        <div class="block-content block-content-full">
                            <div class="block block-transparent  d-flex align-items-center w-100" class="cuadros">
                                <div class="block-content block-content-full">


                                    <div class="py-15 px-20 clearfix border-black-op-b">
                                        <div class="float-right mt-15 d-none d-sm-block">
                                            <i class="si si-book-open fa-2x text-white"></i>
                                        </div>
                                        <div class="font-size-sm font-w600 text-uppercase text-white">Resumen Perfil
                                            Profesional
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm">
                                                <h6 class="text-white">Actualizado: <?php
                                                    if ($CVActualizacion == "") {
                                                        echo "No has creado un perfil";
                                                    } else {
                                                        $date = date_create($CVActualizacion);
                                                        echo date_format($date, "d/m/Y");
                                                    } ?> </h6>
                                            </div>

                                            <div class="col-sm">
                                                <h6 class="text-white">
                                                    Completado: <?php echo round($PorcentajePerfil, 2); ?>%</h6>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="py-15 px-20 clearfix border-black-op-b">
                                        <div class="float-right mt-15 d-none d-sm-block">
                                            <i class="si si-eye fa-2x text-white"></i>
                                        </div>
                                        <div class="font-size-sm font-w600 text-uppercase text-white">Resumen del estado
                                            de tus postulaciones
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col">
                                                <a href="postulaciones" class="text-white"><b>Aplicado
                                                        en <?php echo $TotalAplicado ?> ofertas</b></a>
                                            </div>
                                            <div class="col">
                                                <a href="postulaciones" class="text-white"><b>CV visto
                                                        en <?php echo $TotalVisto ?> ofertas</b></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="py-15 px-20 clearfix border-black-op-b">
                                        <div class="float-right mt-15 d-none d-sm-block">
                                            <i class="si si-doc fa-2x text-white"></i>
                                        </div>
                                        <div class="font-size-sm font-w600 text-uppercase text-white">otros</div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <a href="cv.php"
                                                   class="btn btn-hero btn-rounded btn-noborder btn-alt-primary mr-5 mb-5">Ver
                                                    Perfil</a>


                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <a href="documentos-usuario"
                                                   class="btn btn-hero btn-rounded btn-noborder btn-alt-primary mr-5 mb-5">
                                                    Subir Documentos</a>
                                            </div>
                                        </div>


                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div><!--Fin del conenido-->


        <!-- Terms Modal -->
        <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-slidedown" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Vistas empresas</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <h3 class="text-center">Empresas que ha vistos tu perfil</h3>
                            <p>A continuación se muestran las empresas que han visitados tu perfil desde la más reciente
                                a la más antigua. <b>Las empresas con estado confidencial no podras ver el perfil de
                                    empresas por motivo de privacidad.</b></p>

                            <!-- Dynamic Table Full -->
                            <div class="block">
                                <div class="block-content block-content-full">
                                    <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                                    <table class="table table table-responsive table-bordered table-striped table-vcenter js-dataTable-full ">
                                        <thead>
                                        <tr>
                                            <th class="text-center">N°</th>
                                            <th class="text-center" style="width: 50%;">Logo</th>
                                            <th>Empresa</th>
                                            <th class="text-center">Vistas</th>
                                            <th style="width: 50%;">Opción</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        <?php

                                        $total = 1;
                                        $LogoImagen = "";
                                        $Empresa = "";
                                        $Boton = "";
                                        while ($item = $stmtVisitas->fetch()) {

                                            if ($item['Confidencial'] == "Si") {
                                                $LogoImagen = '<img src="../../main/img/LogosEmpresas/confidential.png" class="img-fluid img-thumbnail" style="width: 100px;">';
                                            } else {
                                                $LogoImagen = '<img src="../../main/img/LogosEmpresas/' . $item['logo'] . '" class="img-fluid img-thumbnail" style="width: 100px;">';
                                            }

                                            if ($item['Confidencial'] == "Si") {
                                                $Empresa = 'Confidencial';
                                            } else {
                                                $Empresa = $item['Nombre'];
                                            }

                                            if ($item['Confidencial'] == "Si") {
                                                $Boton = "<center><button class='btn btn-primary btn-icon-split' disabled ><i class='fa fa-close'></i></button></center>";
                                            } else {
                                                $Boton = "<center><a href='empresa?id=" . base64_encode($item['IDEmpresa']) . "'  class='btn btn-primary btn-icon-split' target='_blank'>Ver empresa</a></center>";
                                            }

                                            echo "<tr>
                                        <td>" . $total . "</td>
                                        <td class='text-center'>" . $LogoImagen . "</td>
                                        <td class='text-center' >" . $Empresa . "</td>
                                        <td class='text-center'><b>" . $item['visitas'] . "</b></td>
                                        <td>" . $Boton . "</td>
                                        </tr>";
                                            $total++;
                                        }

                                        ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END Dynamic Table Full -->


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cerrar</button>

                    </div>
                </div>
            </div>
        </div>
        <!-- END Terms Modal -->


    </main>

<?php
include_once 'templates/footer.php';
include_once 'templates/Instrucciones.php';
include_once 'templates/script.php';
include_once '../../templates/alertas.php';
?>

<?php if (isset($_GET['seguridad'])) {
//    echo "<script>swal({title:'Advertencia',text:' Verifica tu correo electrónico para confirmar el cambio de contraseña. Para poder volver iniciar sesión de nuevo',type:'warning'  });</script>";
} ?>