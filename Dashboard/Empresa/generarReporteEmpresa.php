<?php
// Importación de la librería FPDF para generar PDFs
require('fpdf186/fpdf.php');

// Iniciar la sesión para acceder a variables de sesión del usuario
session_start();

// Incluir archivos necesarios para conexión y funciones
include '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

// Crear instancias de las clases de consulta y funciones
$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Obtener información del usuario de la sesión
$IDUser = $_SESSION['iduser'];
$NombresUser = $_SESSION['nombre'];
$ApellidosUser = $_SESSION['apellido'];
$CorreoUser = $_SESSION['email'];
$CargoUser = $_SESSION['cargo'];
$FotoUser = $_SESSION['foto'];

// Extraer el primer nombre y apellido del usuario para mostrar en el reporte
$PrimerNombre = explode(" ", $NombresUser)[0];
$PrimerApellido = explode(" ", $ApellidosUser)[0];


// Obtener fechas y tipo de reporte desde el formulario
$TipoReporte = $FuncionesApp->test_input($_POST['TipoReporte'] ?? null);
$fechaInicial = $FuncionesApp->test_input($_POST['FechaInicial'] ?? null);
$fechaFinal = $FuncionesApp->test_input($_POST['FechaFinal'] ?? null);

// Definir la clase para el PDF extendiendo de FPDF
class PDF extends FPDF
{
    // Método para el encabezado del PDF
    function Header()
    {
        $this->SetFont('Arial', 'B', 12); // Fuente para el encabezado
        $this->Cell(0, 10, 'Reporte de Empresa', 0, 1, 'C'); // Título centrado
        $this->Ln(10); // Espacio
    }

    // Método para el pie de página del PDF
    function Footer()
    {
        $this->SetY(-15); // Posición del pie de página a 15mm desde el final
        $this->SetFont('Arial', 'I', 8); // Fuente para el pie de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C'); // Número de página centrado
    }
}

// Crear instancia de la clase PDF y configurar página
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Realizar consulta a la base de datos dependiendo del tipo de reporte
if ($fechaInicial && $fechaFinal && $TipoReporte) {
    // Consulta SQL con filtros de fechas y tipo de reporte
    $sql = "SELECT `IDReporte`, `IDEmpresa`, `Tipo`, `contador` AS 'Contador', `fecha` AS 'Fecha'  
            FROM `reportes_generales` 
            WHERE `IDEmpresa` = ? AND `Tipo` = ? AND (`fecha` >= ? AND `fecha` <= ?) 
            ORDER BY `reportes_generales`.`fecha` DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $TipoReporte, $fechaInicial, $fechaFinal));
} elseif ($TipoReporte) {
    // Consulta SQL solo con filtro de tipo de reporte
    $sql = "SELECT `IDReporte`, `IDEmpresa`, `Tipo`, `contador` AS 'Contador', `fecha` AS 'Fecha'  
            FROM `reportes_generales` 
            WHERE `IDEmpresa` = ? AND `Tipo` = ? 
            ORDER BY `fecha` DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $TipoReporte));
} else {
    // Consulta SQL sin filtro de tipo de reporte ni fechas
    $sql = "SELECT `IDReporte`, `IDEmpresa`, `Tipo`, `contador` AS 'Contador', `fecha` AS 'Fecha'  
            FROM `reportes_generales` 
            WHERE `IDEmpresa` = ? 
            ORDER BY `fecha` DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser));
}

// Obtener los resultados de la consulta en un arreglo asociativo
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Información básica del reporte
$pdf->Cell(0, 10, 'Informacion Basica del Reporte', 0, 1, 'L');
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Empresa: ' . $PrimerNombre, 0, 1, 'L');
$pdf->Cell(0, 10, 'Fecha de Generacion: ' . date('d-m-Y'), 0, 1, 'L');
$pdf->Ln(10);

// Detalle del reporte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Detalle del Reporte', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'TipoSeguimiento', 1);
$pdf->Cell(30, 10, 'Contador', 1);
$pdf->Cell(50, 10, 'Fecha', 1);
$pdf->Ln();

// Iterar sobre cada reporte y agregar al PDF
foreach ($reportes as $reporte) {
    $pdf->Cell(40, 10, $reporte['Tipo'], 1);
    $pdf->Cell(30, 10, $reporte['Contador'], 1);
    $pdf->Cell(50, 10, date('d-m-Y', strtotime($reporte['Fecha'])), 1);
    $pdf->Ln();
}

// Configurar encabezados HTTP para la descarga del PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Reporte_Empresa_' . date('d_m_Y') . '.pdf"');

// Generar y enviar el archivo PDF al navegador
$pdf->Output('I');
?>
