<?php
require_once "./conSanatorio.php";
mysqli_set_charset($conSanatorio, "utf8");

if (isset($_POST['TablaPaciente'])){

    $fecha = $_POST['fecha'];

    $query = "SELECT t.dnipaciente, q.numero, q.abreviada as descripcion, t.nombre, CONCAT(DATE_FORMAT(t.horainicio,'%H:%i'),' - ',DATE_FORMAT(t.horafin,'%H:%i')) AS horainicio,
    (CASE WHEN t.parainternar = 1 THEN 'REQUIERE INTERNACION' ELSE 'AMBULATORIO' END) AS parainternar,
    t.fecha, t.estado, t.parainternar, TIMESTAMPDIFF(MINUTE, t.horainicio, t.horafin) AS tiempo, p.nombre AS profesional, pro.nombre AS anestesista, pq.descripcion AS pracquir 
    FROM quirofanos AS q 
    INNER JOIN turnosquirofano AS t ON q.numero = t.quirofano 
    INNER JOIN turnosquirofanospracticas AS tp ON t.numero = tp.numero 
    INNER JOIN practicasquirofano AS pq ON pq.codigo = tp.codigopractica
    INNER JOIN profesionales AS p ON t.matriculaprofesional = p.matricula
    LEFT JOIN turnosquirofanoayudante AS tqa ON tqa.numero = t.numero AND tqa.tipoayudante = '2' 
    LEFT JOIN profesionales AS pro ON tqa.matprofesional = pro.matricula
    WHERE q.numero IN (8, 16) 
    AND t.fecha = '$fecha'
    AND t.estado IN ('PENDIENTE', 'REALIZADO')
    ORDER BY q.numero, t.horainicio";

    $resultado = mysqli_query($conSanatorio, $query);

    $salida = <<<HTML
    <div class="table  table-responsive table-sm">
        <table class="display compact table table-condensed table-striped table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Sala</th>
                    <th>Horario</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Profecional</th>
                    <th>Anestesista</th>
                    <th>Practica</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody>
    HTML;
    
    while ($fila = $resultado->fetch_assoc()) {
        $sala = ($fila['numero'] == 8) ? "sala 1" : "sala 2";
        $salida .= <<<HTML
            <tr>
                
                <td>{$sala}</td>
                <td>{$fila['horainicio']}</td>
                <td>{$fila['dnipaciente']}</td>
                <td>{$fila['nombre']}</td>
                <td>{$fila['profesional']}</td>
                <td>{$fila['anestesista']}</td>
                <td>{$fila['pracquir']}</td>
                <td>
                    <button type="button" class="btn btn-primary" onclick="Seleccionar({$fila['dnipaciente']})" >Seleccionar</button>
                </td>
            </tr>
    HTML;
    }
    
    $salida .= <<<HTML
            </tbody>
        </table>
    </div>
    
    <script type="text/javascript">
    $(document).ready(function() {
        $("#example").DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            },
            "pageLength": 100
        });
    });
    </script>
    
    HTML;

    echo $salida;

    

}
  
   