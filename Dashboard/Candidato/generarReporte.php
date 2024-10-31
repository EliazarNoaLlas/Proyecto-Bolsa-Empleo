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

// Obtener fechas del formulario, asignando null si no están proporcionadas
$fechaInicial = $FuncionesApp->test_input($_POST['FechaInicial'] ?? null);
$fechaFinal = $FuncionesApp->test_input($_POST['FechaFinal'] ?? null);

// Definir la clase para el PDF extendiendo de FPDF
class PDF extends FPDF
{
    // Método para el encabezado del PDF
    function Header()
    {
        $this->SetFont('Arial', 'B', 12); // Fuente para el encabezado
        $this->Cell(0, 10, 'Reporte de Postulaciones', 0, 1, 'C'); // Título centrado
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

// Realizar consulta a la base de datos para obtener postulaciones del usuario
if ($fechaInicial && $fechaFinal) {
    // Consulta SQL con filtro de rango de fechas
    $sql = "SELECT EP.Nombre AS 'Empresa', OP.Plaza, P.Estado, P.FechaInscrita
            FROM usuario_postulaciones P
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa
            WHERE P.IDUsuario = ? AND (P.FechaInscrita >= ? AND P.FechaInscrita <= ?)
            ORDER BY P.FechaInscrita DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $fechaInicial, $fechaFinal));
} else {
    // Consulta SQL sin filtro de fechas
    $sql = "SELECT EP.Nombre AS 'Empresa', OP.Plaza, P.Estado, P.FechaInscrita
            FROM usuario_postulaciones P
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa
            WHERE P.IDUsuario = ?
            ORDER BY P.FechaInscrita DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser));
}

// Obtener los resultados de la consulta en un arreglo asociativo
$postulaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Contar y clasificar el estado de las postulaciones para el resumen
$totalPostulaciones = count($postulaciones);
$enProceso = count(array_filter($postulaciones, function ($p) {
    return $p['Estado'] === 'enviado';
}));
$aceptadas = count(array_filter($postulaciones, function ($p) {
    return $p['Estado'] === 'Aceptado';
}));
$rechazadas = count(array_filter($postulaciones, function ($p) {
    return $p['Estado'] === 'Rechazada';
}));

// Información básica del reporte
$pdf->Cell(0, 10, 'Informacion Basica del Reporte', 0, 1, 'L');
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Candidato: ' . $PrimerNombre . ' ' . $PrimerApellido, 0, 1, 'L');
$pdf->Cell(0, 10, 'Fecha de Generacion: ' . date('d-m-Y'), 0, 1, 'L');
$pdf->Ln(10);

// Resumen de postulaciones
// Sección de resumen de postulaciones
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Resumen de Postulaciones', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Total de Postulaciones: ' . $totalPostulaciones, 0, 1, 'L');
$pdf->Cell(0, 10, 'En Proceso: ' . $enProceso, 0, 1, 'L');
$pdf->Cell(0, 10, 'Aceptadas: ' . $aceptadas, 0, 1, 'L');
$pdf->Cell(0, 10, 'Rechazadas: ' . $rechazadas, 0, 1, 'L');
$pdf->Ln(10);

// Detalle de postulaciones
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Detalle de Postulaciones', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 10, 'Empresa', 1);
$pdf->Cell(50, 10, 'Plaza', 1);
$pdf->Cell(40, 10, 'Estado', 1);
$pdf->Cell(40, 10, 'Fecha', 1);
$pdf->Ln();

// Iterar sobre cada postulacion y agregar al PDF
foreach ($postulaciones as $postulacion) {
    $pdf->Cell(60, 10, $postulacion['Empresa'], 1);
    $pdf->Cell(50, 10, $postulacion['Plaza'], 1);
    $pdf->Cell(40, 10, $postulacion['Estado'], 1);
    $pdf->Cell(40, 10, date('d-m-Y', strtotime($postulacion['FechaInscrita'])), 1);
    $pdf->Ln();
}

// Configurar encabezados HTTP para la descarga del PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Reporte_Postulaciones_' . date('d_m_Y') . '.pdf"');

// Generar y enviar el archivo PDF al navegador
$pdf->Output('I');
?>
