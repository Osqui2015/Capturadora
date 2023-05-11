<?php
  if (isset($_FILES['images'])) {

    $dni = isset($_POST['dni']) ? $_POST['dni'] : null;

    $fecha_actual = date('Ymd');
    $nombre = "{$dni}-{$fecha_actual}";

    $carpeta = "images/{$nombre}";
    if (!file_exists($carpeta)) {
      mkdir($carpeta);
    }

    $imageCount = count($_FILES['images']['name']);

    for ($i = 0; $i < $imageCount; $i++) {
      $tmpName = $_FILES['images']['tmp_name'][$i];
      $name = $_FILES['images']['name'][$i];
      move_uploaded_file($tmpName, "$carpeta/$name");
    }
    echo "Las imágenes se han guardado correctamente.";
  } else {
    echo "No se han recibido imágenes.";
  }
?>
