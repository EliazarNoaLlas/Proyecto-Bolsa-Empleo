<?php
/**
 * Archivo: reportes.php
 * Propósito: Generar reportes de postulaciones para los usuarios, mostrando información como empresa, plaza, y estado de
 * la postulación.
 * Autor: Walter Stefano
 * Fecha de modificación: 2024-10-29
 */

include_once '../../BD/Conexion.php'; // Conexión a la base de datos
include_once '../../BD/Consultas.php'; // Clase para consultas a la base de datos
include_once '../../main/funcionesApp.php'; // Funciones adicionales de la aplicación
include_once 'templates/head.php'; // Encabezado HTML de la página

$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();
include_once 'templates/seguridadCpanel.php'; // Verificación de seguridad para el acceso

?>

<title>Usuario | Reportes de Postulaciones</title>

<?php
include_once 'templates/styles.php'; // Estilos adicionales
include_once 'templates/MenuRight.php'; // Menú lateral derecho
include_once 'templates/MenuLeft.php'; // Menú lateral izquierdo
include_once 'templates/header.php'; // Encabezado de la aplicación
?>

<style type="text/css">
    #imgbanner {
        background: url('../assets/media/photos/Reportes_Estadísticas.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        height: auto;
    }
</style>

<!-- Estructura proncipal -->
<main id="main-container">

    <!-- Sección de encabezado con imagen de fondo -->
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

        <!-- Contenido principal de la página -->
        <div class="content">

            <!-- Botón para regresar al panel principal -->
            <div class="text-center">
                <a href="./" class="btn btn-rounded btn-noborder btn-alt-primary mr-5 mb-5">
                    <i class="si si-action-undo fa-2x5"></i> Ir al panel
                </a>
            </div>
            <br><br>

            <!-- Mensaje introductorio y formulario de fechas para el reporte -->
            <div class="text-center">
                <p>Esta plataforma cuenta con las herramientas para generar los reportes de tus postulaciones.</p>
                <br><br>
                <div class="row">
                    <!-- Selección de fecha inicial -->
                    <div class="col-lg-2 col-md-2 col-12">
                        <label>Fecha Inicial</label>
                        <input type="date" name="fechaInicial" id="fechaInicial" class="form-control">
                    </div>

                    <!-- Selección de fecha final -->
                    <div class="col-lg-2 col-md-2 col-12">
                        <label>Fecha Final</label>
                        <input type="date" name="fechaFinal" id="fechaFinal" class="form-control">
                    </div>

                    <!-- Botón para generar el reporte -->
                    <div class="col-lg-4 col-md-4 col-12 d-flex justify-content-center align-items-center">
                        <!-- Botón de Generar Reporte -->
                        <input type="submit" name="btnReporte" id="btnReporte" value="Generar Reporte"
                               class="btn btn-alt-primary btn-lg btn-block btn-rounded">

                        <!-- Botón desplegable de descarga -->
                        <div class="btn-group ml-2">
                            <button type="button"
                                    class="btn btn-alt-primary btn-lg btn-block btn-rounded dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Descargar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="exportarReporte()">PDF</a>
                                <a class="dropdown-item" href="#" onclick="exportarReporte()">Word</a>
                                <a class="dropdown-item" href="#" onclick="exportarReporte()">Excel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <!-- Tabla para mostrar los resultados del reporte -->
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

<?php
// Inclusión de pie de página y scripts
include_once 'templates/footer.php';
include_once 'templates/script.php';
include_once '../../templates/alertas.php';
?>

<script type="text/javascript">
    // Llama a la función para cargar el reporte de postulaciones al cargar la página
    $(MostrarReportes(""));

    /**
     * Función para mostrar el reporte de postulaciones en una tabla
     * @param {string} buscar - Parámetro para filtrar los resultados
     * @param {string} FechaInicial - Fecha inicial para filtrar el reporte
     * @param {string} FechaFinal - Fecha final para filtrar el reporte
     */
    function MostrarReportes(buscar, FechaInicial, FechaFinal) {
        $('#TablasReportes').DataTable({
            "language": {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
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
                "url": "Modelos/ModelosReportes/reportes-postulaciones.php", // Archivo que proporciona los datos
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
                {
                    "data": "logo", "render": function (data) {
                        return '<img src="' + data + '" style="width: 50px; height: 50px;">';
                    }
                },
                {"data": "Plaza"},
                {"data": "Confidencial"},
                {"data": "Estado"},
                {"data": "Aprobacion"},
                {"data": "FechaInscrita"}
            ]
        });
    }

    // Evento para generar el reporte al hacer clic en el botón
    $("#btnReporte").click(function () {
        var table = $('#TablasReportes').DataTable();
        table.destroy(); // Reinicia la tabla para actualizar datos

        var FechaInicial = $('#fechaInicial').val();
        var FechaFinal = $('#fechaFinal').val();

        // Validación de las fechas seleccionadas
        if (FechaInicial == "") {
            swal({title: 'Alerta', text: 'Debe seleccionar la fecha inicial', type: 'error'});
        } else if (FechaFinal == "") {
            swal({title: 'Alerta', text: 'Debe seleccionar la fecha final', type: 'error'});
        } else {
            MostrarReportes("GenerarReporte", FechaInicial, FechaFinal); // Llama a la función de mostrar reporte
        }
    });
    // Función para exportar el reporte en PDF
    function exportarReporte() {
        console.log("Exportando reporte en formato PDF");

        // Realizar una solicitud AJAX para generar y descargar el reporte
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "generarReporte.php", true); // Ruta de tu archivo PHP

        // Configuración para manejar el archivo generado como respuesta
        xhr.responseType = "blob";
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                // Crear un enlace de descarga para el archivo generado
                const blob = xhr.response;
                const downloadLink = document.createElement("a");
                downloadLink.href = window.URL.createObjectURL(blob);

                // Nombre del archivo descargado
                const fileName = "Reporte_Postulaciones.pdf";
                downloadLink.download = fileName;

                // Agregar el enlace al DOM y simular un clic
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            } else {
                console.error("Error al generar el reporte.");
            }
        };

        // Enviar solicitud sin datos adicionales, ya que el formato es PDF
        xhr.send();
    }
</script>
