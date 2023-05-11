<?php 
   $fecha_actual = date('Y-m-d');
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Capturadora de imágenes de la cámara web</title>
  <?php require_once 'dependencia.php' ?>
</head>

<body onload="TablaPaciente()">
  <?php require_once 'menu.php' ?>
  <div class="container">
    

    <div class="container text-center">
      <div class="row">
        <div class="col align-self-start">
          
        </div>
        <div class="col align-self-center">
          <p class="fs-2"> Turnos Endoscopia </p>
        </div>
        <div class="col align-self-end">
          <button type="button" class="btn btn-info" onclick="SinTurno()">Sin Turno</button>
        </div>
      </div>
    </div>




    <div class="container text-center">
      <div class="row justify-content-md-center">
        <div class="col col-lg-2">
          <input type="date" class="form-control" value="<?php echo $fecha_actual ?>" id="fecha" name="fecha" onchange="TablaPaciente()">     
        </div>
      </div>
    </div>
    <br>
    <div  id="TablaPaciente">

    </div>



  </div>

  <?php require_once 'fin.php'; ?>
</body>

</html>