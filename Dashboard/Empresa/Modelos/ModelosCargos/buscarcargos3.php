<?php

include '../../../../BD/Conexion.php';

$salida = "";
$sql = "";
$result = "";

if (isset($_POST['consulta'])) {

    $dato = $_POST['consulta'];

    $sql = "SELECT * 
            FROM soporte_cargos_desempenado 
            WHERE IDCategoria = ? 
            GROUP BY nombre 
            ORDER BY nombre ASC";

    $stmt = Conexion::conectar()->prepare($sql);

    if (!$stmt->execute(array($dato))) {
        die("El error de Conexión es ejecutar_consulta_simple");
    }

    $salida .= "<label class='col-12' for='cargo'>Seleccione el cargo desempeñado*</label>";
    $salida .= "<select name='idCargo' id='idCargo' style='width: 100%;' class='form-control'>";
    $salida .= "<option selected disabled>Seleccione una opción</option>";

    while($item2 = $stmt->fetch()) {
        $salida .= "<option value='" . $item2['IDDesempenado'] . "'>" . htmlspecialchars($item2['nombre'], ENT_QUOTES, 'UTF-8') . "</option>";
    }

    $salida .= "</select>";

    echo $salida;

} else {

    echo "<label class='col-12' for='cargo'>Seleccione el cargo desempeñado*</label>
          <select name='idCargo2' id='idCargo' style='width: 100%;' class='form-control' disabled>
          <option selected disabled>Seleccione una Área laboral</option>
          </select>";
}

?>
