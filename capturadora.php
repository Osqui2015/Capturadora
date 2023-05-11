<?php
    $valor = $_GET['valor'];
    require_once "./conSanatorio.php";
    mysqli_set_charset($conSanatorio, "utf8");
    
    $tpaciente = mysqli_query($conSanatorio, "SELECT * FROM pacientes WHERE NumeroDocumentoIdentidad = '$valor'");

    if (mysqli_num_rows($tpaciente) > 0) {
      while ($consulta = mysqli_fetch_array($tpaciente)) {        
        $valores['numDoc'] = $valor;
        $valores['Apellido'] = $consulta['Apellido'];
        $valores['Nombre'] = empty($consulta['Nombre']) ? 0 : $consulta['Nombre'];
        $valores['CodigoObraSocial'] = empty($consulta['CodigoObraSocial']) ? 0 : $consulta['CodigoObraSocial'];
        $valores['CodigoPlan'] = empty($consulta['CodigoPlan']) ? 0 : $consulta['CodigoPlan'];
        $valores['CodigoAfiliado'] = empty($consulta['CodigoAfiliado']) ? 0 : $consulta['CodigoAfiliado'];
        $valores['Telefono'] = empty($consulta['Telefono']) ? 0 : $consulta['Telefono'];
        $valores['FechaNacimiento'] = empty($consulta['FechaNacimiento']) ? 0 : $consulta['FechaNacimiento'];
        $valores['telefono'] = empty($consulta['Telefono']) ? 0 : $consulta['Telefono'];
      }
    } else {
      echo "No";
    }    

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Capturadora de imágenes de la cámara web</title>
  <?php require_once 'dependencia.php' ?>

</head>

<body>
<?php require_once 'menu.php' ?>
<br>
  <div class="container">
    <div class="row">
      <div class="col">
        <p class="text-end">DNI</p>
      </div>
      <div class="col">
        <input type="number" class="form-control" id="Dni" name="Dni" value="<?php echo intval($valor) ?>">
      </div>
      <div class="col">
        <p class="text-end">Apellido y Nombre</p>
      </div>
      <div class="col-4">
            <input type="text" class="form-control" id="AyN" name="AyN" value="<?php echo $valores['Apellido']; ?>" readonly disabled>                   
      </div>
    </div>
  </div>
<br>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h1>Cámara web</h1>
        <video id="video" width="400" height="300"></video>
        <br>
        <button id="capture" class="btn btn-primary">Tomar foto</button>
      </div>

      <div class="col-md-6">
        <h1>Imágenes capturadas</h1>
        <div id="captured-images"></div>
        <button id="download" class="btn btn-primary">Guardar En Carpeta</button>
      </div>      
    </div>
  </div>
  <?php require_once 'fin.php'; ?>
</body>
<script>
  const video = document.getElementById('video');
  const captureButton = document.getElementById('capture');
  const capturedImagesDiv = document.getElementById('captured-images');
  const downloadButton = document.getElementById('download');

  // Configurar la cámara web
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
      video.play();}
  );

  // Tomar una foto cuando se presiona el botón "Tomar foto"
  captureButton.addEventListener('click', () => {
    // Crear un elemento canvas y copiar el contenido del video en él
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Crear un elemento de imagen y asignarle el contenido del canvas
    const img = document.createElement('img');
    img.src = canvas.toDataURL('image/png');
    img.width = 200;
    img.height = 150;

    // Crear un botón de eliminar y agregar un evento de click que elimine la imagen correspondiente
    const deleteButton = document.createElement('button');
    deleteButton.innerText = 'Eliminar';
    deleteButton.addEventListener('click', () => {
      capturedImagesDiv.removeChild(imageContainer);
    });

    // Crear un contenedor para la imagen y el botón de eliminar
    const imageContainer = document.createElement('div');
    imageContainer.appendChild(img);
    imageContainer.appendChild(deleteButton);

    // Agregar el contenedor a la página
    capturedImagesDiv.appendChild(imageContainer);
  });

  
  downloadButton.addEventListener('click', () => {
    const dni = document.getElementById('Dni').value;
    const images = capturedImagesDiv.querySelectorAll('img');
    const imageCount = images.length;
    const formData = new FormData();
    formData.append('dni', dni);
    for (let i = 0; i < imageCount; i++) {
      const dataUri = images[i].src;
      const binary = atob(dataUri.split(',')[1]);
      const array = [];
      for (let j = 0; j < binary.length; j++) {
        array.push(binary.charCodeAt(j));
      }
      const blob = new Blob([new Uint8Array(array)], {type: 'image/png'});
      formData.append('images[]', blob, `${dni}_${i}.png`);
    }
  
    $.ajax({
      url: 'save_images.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        alert(response);
        setTimeout(function(){
            $(location).attr('href','/capturadoraF/index.php');
          }, 0);
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });
</script>

</html>