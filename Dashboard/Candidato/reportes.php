<?php
include_once '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';
include_once 'templates/head.php';
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();
include_once 'templates/seguridadCpanel.php';

?>
<title>Usuario | Reportes de Postulaciones</title>
<?php
include_once 'templates/styles.php';
include_once 'templates/MenuRight.php';
include_once 'templates/MenuLeft.php';
include_once 'templates/header.php';
?>

<style type="text/css">
    #imgbanner {
        background: url('../assets/media/photos/Reportes_Estadísticas.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        height: auto;
    }
</style>

<main id="main-container">

    <div class="bg-image bg-image-bottom" id="imgbanner">
        <div>
            <div class="content content-top text-center overflow-hidden">
                <div class="pt-40 pb-20">
                    <h3 class="font-size-h2 font-w300 mt-20" data-toggle="appear" data-class="animated flipInY"
                        id="titulos" style="color: white;">Reportes de Postulaciones</h3>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-right:2%; margin-left:2%;">

        <!-- Page Content -->
        <div class="content">

            <div class="text-center">
                <a href="./" class="btn btn-rounded btn-noborder btn-alt-primary mr-5 mb-5">
                    <i class="si si-action-undo fa-2x5"></i> Ir al panel
                </a>
            </div>
            <br><br>

            <div class="text-center">
                <p>Esta plataforma cuenta con las herramientas para generar los reportes de tus postulaciones.</p>
                <br><br>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-12">
                        <label>Fecha Inicial</label>
                        <input type="date" name="fechaInicial" id="fechaInicial" class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-2 col-12">
                        <label>Fecha Final</label>
                        <input type="date" name="fechaFinal" id="fechaFinal" class="form-control">
                    </div>

                    <div class="col-lg-4 col-md-4 col-12">
                        <br>
                        <center>
                            <input type="submit" name="btnReporte" id="btnReporte" value="Generar Reporte"
                                   class="btn btn-alt-primary btn-lg btn-block btn-rounded">
                        </center>
                    </div>
                </div>
            </div>
            <br>

            <div class="block">
                <div class="block-content block-content-full"><br>
                    <h3>Reporte de Postulaciones</h3>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="TablasReportes" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Logo</th>
                                        <th>Plaza</th>
                                        <th>Confidencial</th>
                                        <th>Estado</th>
                                        <th>Aprobación</th>
                                        <th>Fecha Inscrita</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include_once 'templates/footer.php';
include_once 'templates/script.php';
include_once '../../templates/alertas.php';
?>

<script type="text/javascript">
    $(MostrarReportes(""));

    function MostrarReportes(buscar, FechaInicial, FechaFinal) {
        $('#TablasReportes').DataTable({
            "language": {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "ajax": {
                "url": "Modelos/ModelosReportes/reportes-postulaciones.php",
                "method": 'POST',
                "data": {
                    IDUser: "<?php echo $IDUser; ?>",
                    buscar: buscar,
                    FechaInicial: FechaInicial,
                    FechaFinal: FechaFinal
                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "Empresa"},
                {"data": "logo", "render": function(data) {
                        return '<img src="' + data + '" style="width: 50px; height: 50px;">';
                    }},
                {"data": "Plaza"},
                {"data": "Confidencial"},
                {"data": "Estado"},
                {"data": "Aprobacion"},
                {"data": "FechaInscrita"}
            ]
        });
    }

    $("#btnReporte").click(function () {
        var table = $('#TablasReportes').DataTable();
        table.destroy();

        var FechaInicial = $('#fechaInicial').val();
        var FechaFinal = $('#fechaFinal').val();

        if (FechaInicial == "") {
            swal({title: 'Alerta', text: 'Debe seleccionar la fecha inicial', type: 'error'});
        } else if (FechaFinal == "") {
            swal({title: 'Alerta', text: 'Debe seleccionar la fecha final', type: 'error'});
        } else {
            MostrarReportes("GenerarReporte", FechaInicial, FechaFinal);
        }
    });
</script>
