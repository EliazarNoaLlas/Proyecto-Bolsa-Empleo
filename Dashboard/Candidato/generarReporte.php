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
        // Fondo del header
        $this->SetFillColor(255, 255, 255);
        $this->Rect(0, 0, 210, 40, 'F');


        // Imagen circular para la foto del usuario
        $this->Circle(10, 10, 15, 'F'); // Círculo de fondo
        // Logo de la empresa
        $this->Image('../../assets/img/user/img02.jpeg', 10, 10, 20);


        // Título del reporte
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(30, 64, 175); // Color azul oscuro
        $this->SetXY(50, 15);
        $this->Cell(110, 10, 'Reporte de Postulaciones', 0, 1, 'C');

        // Línea decorativa
        $this->SetLineWidth(0.5);
        $this->SetDrawColor(59, 130, 246); // Color azul
        $this->Line(10, 35, 200, 35);
        $this->Ln(10);
    }

    // Método para dibujar un círculo relleno
    function Circle($x, $y, $r, $style = 'D')
    {
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    // Método para dibujar una elipse
    function Ellipse($x, $y, $rx, $ry, $style = 'D')
    {
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';

        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;

        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x) * $k, ($h - $y) * $k,
            ($x + $lx) * $k, ($h - $y) * $k,
            ($x + $rx) * $k, ($h - $y + $ly) * $k,
            ($x + $rx) * $k, ($h - $y + $ry) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k, ($h - $y + 2 * $ry - $ly) * $k,
            ($x + $lx) * $k, ($h - $y + 2 * $ry) * $k,
            ($x) * $k, ($h - $y + 2 * $ry) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $lx) * $k, ($h - $y + 2 * $ry) * $k,
            ($x - $rx) * $k, ($h - $y + 2 * $ry - $ly) * $k,
            ($x - $rx) * $k, ($h - $y + $ry) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x - $rx) * $k, ($h - $y + $ly) * $k,
            ($x - $lx) * $k, ($h - $y) * $k,
            ($x) * $k, ($h - $y) * $k,
            $op));
    }

    // Método para el pie de página del PDF
    function Footer()
    {
        $this->SetY(-15); // Posición del pie de página a 15mm desde el final
        $this->SetFont('Arial', 'I', 8); // Fuente para el pie de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C'); // Número de página centrado
    }

    // Función para crear sección con título
    function SectionTitle($title)
    {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(30, 64, 175); // Azul oscuro
        $this->Cell(0, 10, utf8_decode($title), 0, 1, 'L');
        $this->SetLineWidth(0.2);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    // Función para crear barra de progreso
    function ProgressBar($x, $y, $width, $height, $progress)
    {
        $this->SetFillColor(229, 231, 235); // Color de fondo gris
        $this->Rect($x, $y, $width, $height, 'F');
        $this->SetFillColor(59, 130, 246); // Color de progreso azul
        $this->Rect($x, $y, ($width * $progress / 100), $height, 'F');
    }

    // Función para crear badge de estado
    function StatusBadge($x, $y, $status)
    {
        switch ($status) {
            case 'enviado':
                $this->SetFillColor(219, 234, 254); // Azul claro
                $this->SetTextColor(30, 64, 175);
                $status = 'En Proceso';
                break;
            case 'Aceptado':
                $this->SetFillColor(220, 252, 231); // Verde claro
                $this->SetTextColor(22, 101, 52);
                break;
            case 'Rechazada':
                $this->SetFillColor(254, 226, 226); // Rojo claro
                $this->SetTextColor(153, 27, 27);
                break;
        }
        $this->RoundedRect($x, $y, 30, 6, 2, 'F');
        $this->SetXY($x, $y);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, utf8_decode($status), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }

    // Función para crear rectángulo redondeado
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        $this->_out(sprintf('%.2F %.2F l', ($x + $w - $r) * $k, ($hp - $y) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $w) * $k, ($hp - $y) * $k,
            ($x + $w) * $k, ($hp - ($y + $r)) * $k,
            ($x + $w - $r) * $k, ($hp - ($y + $h)) * $k));
        $this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - ($y + $h)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x * $k, ($hp - ($y + $h)) * $k,
            $x * $k, ($hp - ($y + $h - $r)) * $k,
            ($x + $r) * $k, ($hp - ($y + $h)) * $k));
        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - ($y + $r)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x * $k, ($hp - $y) * $k,
            ($x + $r) * $k, ($hp - $y) * $k,
            ($x + $r) * $k, ($hp - $y) * $k));
        if ($style == 'F')
            $this->_out('f');
        elseif ($style == 'FD' || $style == 'DF')
            $this->_out('B');
        else
            $this->_out('S');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Información del Candidato
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Información del Candidato'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 7, 'Nombre:', 0);
$pdf->Cell(0, 7, utf8_decode($PrimerNombre . ' ' . $PrimerApellido), 0, 1);
$pdf->Cell(40, 7, 'Correo:', 0);
$pdf->Cell(0, 7, $CorreoUser, 0, 1);
$pdf->Cell(40, 7, 'Cargo:', 0);
$pdf->Cell(0, 7, utf8_decode($CargoUser), 0, 1);
$pdf->Cell(40, 7, utf8_decode('Fecha de emisión:'), 0);
$pdf->Cell(0, 7, date('d/m/Y'), 0, 1);
$pdf->Ln(10);

// Consulta a la base de datos
if ($fechaInicial && $fechaFinal) {
    $sql = "SELECT EP.Nombre AS 'Empresa', OP.Plaza, P.Estado, P.FechaInscrita
            FROM usuario_postulaciones P
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa
            WHERE P.IDUsuario = ? AND (P.FechaInscrita >= ? AND P.FechaInscrita <= ?)
            ORDER BY P.FechaInscrita DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $fechaInicial, $fechaFinal));
} else {
    $sql = "SELECT EP.Nombre AS 'Empresa', OP.Plaza, P.Estado, P.FechaInscrita
            FROM usuario_postulaciones P
            INNER JOIN empresa_ofertas_trabajos OP ON P.IDpostulaciones = OP.IDpostulaciones
            LEFT JOIN empresa_perfil EP ON OP.IDEmpresa = EP.IDEmpresa
            WHERE P.IDUsuario = ?
            ORDER BY P.FechaInscrita DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser));
}

$postulaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
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

// Sección de Estadísticas
$pdf->SectionTitle('Resumen de Postulaciones');
$pdf->SetFont('Arial', '', 11);

// Crear gráfico de progreso
$pdf->Cell(0, 10, 'Estado General de Postulaciones:', 0, 1);
$totalWidth = 180;
$height = 8;
$y = $pdf->GetY();

// Barras de progreso para cada estado
if ($totalPostulaciones > 0) {
    $porcentajeEnProceso = ($enProceso / $totalPostulaciones) * 100;
    $porcentajeAceptadas = ($aceptadas / $totalPostulaciones) * 100;
    $porcentajeRechazadas = ($rechazadas / $totalPostulaciones) * 100;

    $pdf->Cell(40, 6, 'En Proceso:', 0);
    $pdf->ProgressBar(50, $y, 100, 6, $porcentajeEnProceso);
    $pdf->Cell(160, 6, sprintf('%.1f%%', $porcentajeEnProceso), 0, 1, 'R');

    $y += 8;
    $pdf->Cell(40, 6, 'Aceptadas:', 0);
    $pdf->ProgressBar(50, $y, 100, 6, $porcentajeAceptadas);
    $pdf->Cell(160, 6, sprintf('%.1f%%', $porcentajeAceptadas), 0, 1, 'R');

    $y += 8;
    $pdf->Cell(40, 6, 'Rechazadas:', 0);
    $pdf->ProgressBar(50, $y, 100, 6, $porcentajeRechazadas);
    $pdf->Cell(160, 6, sprintf('%.1f%%', $porcentajeRechazadas), 0, 1, 'R');
}

$pdf->Ln(10);

// Detalle de Postulaciones
$pdf->SectionTitle('Detalle de Postulaciones');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(247, 250, 252); // Color de fondo para la cabecera
$pdf->Cell(60, 8, 'Empresa', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Plaza', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Estado', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Fecha', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
foreach ($postulaciones as $postulacion) {
    $pdf->Cell(60, 8, utf8_decode($postulacion['Empresa']), 1);
    $pdf->Cell(50, 8, utf8_decode($postulacion['Plaza']), 1);

    // Posición para el badge de estado
    $x = $pdf->GetX();
    $y = $pdf->GetY() + 1;
    $pdf->Cell(30, 8, '', 1); // Celda vacía para el estado
    $pdf->StatusBadge($x + 0.5, $y, $postulacion['Estado']);

    $pdf->Cell(50, 8, date('d/m/Y', strtotime($postulacion['FechaInscrita'])), 1, 1);
}

// Configurar headers y generar PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Reporte_Postulaciones_' . date('d_m_Y') . '.pdf"');
$pdf->Output('I');
?>