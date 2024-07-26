<?php

header("Access-Control-Allow-Origin: *"); //puedes recibir peticiones de cualquier dominio
header('Content-type: application/json');
require '../conexion.php';

$parentesco = $_POST['parentesco'];
$nameTutor = $_POST['nameTutor'];
$apTutor = $_POST['apTutor'];
$amTutor = $_POST['amTutor'];
$email = $_POST['email'];
$tutor = $_POST['tutor'];


$insert = mysqli_query($mysqli, "insert into tutor(nombre, apellido_paterno, apellido_materno, parentesco,email, telefono) values ('$nameTutor','$apTutor','$amTutor','$email', '$tutor')");

$sqlTutor = "select id from tutor order by id desc limit 1";
$resulTutor = $mysqli->query($sqlTutor);
$rowTutor = $resultTutor->fetch_assoc();

$id_tutor = $rowTutor['id'];

if (!$insert) {
  $res = array(
    "err" => true,
    "statusText" => "Error al guardar informacion del tutor"
  );

  echo json_encode($res);
  exit;
}


$alumnosJson = $_POST['alumnos'];
$alumnos = json_decode($alumnosJson, true);

$id_alumno1;
$id_alumno2;
$id_alumno3;

$id_formato;

$dia = date('d');
$mes = date('n');
$year = date('Y');

switch ($mes) {
  case 7:
    $mes = "julio";
    break;
  case 8:
    $mes = "agosto";
    break;
  case 9:
    $mes = "septiembre";
    break;
}

$fecha = $dia . " " . "de" . " " . $mes . " " . "de" . " 2024";


if (json_last_error() === JSON_ERROR_NONE) {
  // Procesar los datos de los alumnos
  $aux = 1;
  foreach ($alumnos as $alumno) {
    $grado = isset($alumno['grado']) ? $alumno['grado'] : '';
    $grupo = isset($alumno['grupo']) ? $alumno['grupo'] : '';
    $nameAlumno = isset($alumno['nameAlumno']) ? $alumno['nameAlumno'] : '';
    $apAlumno = isset($alumno['apAlumno']) ? $alumno['apAlumno'] : '';
    $amAlumno = isset($alumno['amAlumno']) ? $alumno['amAlumno'] : '';
    $niaAlumno = isset($alumno['niaAlumno']) ? $alumno['niaAlumno'] : '';

    $insertAlumno =
      mysqli_query($mysqli, "insert into alumnos(nombre, apellido_paterno, apellido_materno, nia,grado,grupo,tutor) values ('$nameAlumno','$apAlumno','$amAlumno','$niaAlumno, '$id_tutor')");

    if (!$insertAlumno) {
      $res = array(
        "err" => true,
        "statusText" => "Error al guardar informacion del alumno: $nameAlumno"
      );

      echo json_encode($res);
      exit;
    }

    $sqlAlumno = "select id from alumnos order by id desc limit 1";
    $resulAlumno = $mysqli->query($sqlAlumno);
    $rowAlumno = $resultAlumno->fetch_assoc();
    switch ($aux) {
      case 1:
        $id_alumno1 = $rowAlumno['id'];
        break;
      case 2:
        $id_alumno2 = $rowAlumno['id'];
        break;
      case 3:
        $id_alumno3 = $rowAlumno['id'];
        break;
    }

    mysqli_query($mysqli, "insert into formato_inscripcion(id_tutor, id_alumno1, id_alumno2, id_alumno3) values ('$id_tutor','$id_alumno1','$id_alumno2','$id_alumno3')");

    $sqlFormato = "select id from alumnos order by id desc limit 1";
    $resulFormato = $mysqli->query($sqlFormato);
    $rowFormato = $resultFormato->fetch_assoc();

    $id_formato = $rowFormato['id'];

    $aux++;
  }
} else {
  $res = array(
    "err" => true,
    "statusText" => "Error al decodificar JSON"
  );

  echo json_encode($res);
  exit;
}

ob_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Formato Inscripción</title>
  <style>
    html {
      box-sizing: content-box;
      font-family: "Montserrat", sans-serif;
      font-size: 12pt;
      margin: 0;
      padding: 0;
    }

    *::after,
    *::before {
      box-sizing: inherit;
    }

    body {
      width: 20.59cm;
      min-height: 27.94cm;
      margin: 0;
      padding: 0 1cm;
      font-family: inherit;
      overflow-x: hidden;
      border: thin solid black;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    header {
      width: 100%;
      height: 3cm;
      align-content: center;
    }

    .header-figure {
      align-content: center;
      display: inline-block;
      width: 5cm;
      padding: 0;
      margin: 0;
      text-align: center;
    }

    .header-title {
      text-align: center;
      display: inline-block;
      width: 12cm;
    }

    .header-id {
      display: inline-block;
      width: 3cm;
      padding: 0;
      margin: 0;
      text-align: center;
      font-weight: 500;
    }

    h1 {
      font-size: 1.4rem;
    }

    h2 {
      font-size: 1rem;
    }

    .section-recibo {
      text-align: center;
      margin-top: 2rem;
    }

    p {
      margin: 0.5rem;
    }

    .alumno {
      font-weight: bold;
    }

    .alumno-nombre,
    .alumno-grado,
    .alumno-grupo {
      display: inline-block;
    }

    .alumno-grado {
      margin: 0 1rem 0 20rem;
    }

    footer {
      text-align: center;
      margin-top: 2rem;
      font-weight: 500;
    }

    .footer-title {
      font-size: 1rem;
      font-weight: 600;
      margin-top: 5rem;
    }

    .footer-fl {
      display: inline-block;
      margin-left: 4rem;
    }

    .footer-sl {
      display: inline-block;
      margin-left: 5rem;
    }

    .comprobante {
      border-bottom: medium solid black;
      background-image: url(./assets/logo-aquiles-serdan-blur.png);
      background-position: center;
      background-repeat: no-repeat;
      background-size: auto;
    }
  </style>
</head>

<body>
  <article class="comprobante">
    <header>
      <figure class="header-figure"></figure>
      <section class="header-title">
        <h1>
          ESCUELA PRIMARIA AQUILES SERDÁN
        </h1>
        <h2>CCT: 21DPR0323R</h2>
        <h2>ASOCIACIÓN DE PADRES DE FAMILIA</h2>
      </section>
      <p class="header-id">Folio<br> <?php echo $id_formato ?></p>
    </header>
    <section class="section-recibo">
      <p>Recibimos de: <b> <?php echo $nameTutor . " " . $apTutor . " " . $amTutor  ?></b> </p>
      <p> La cantidad de <b>$600.00 MXN</b> (Cuatrocientos Pesos 00/100 M.N).</p>
      <p>Por concepto de <b>mantenimiento</b> del edificio escolar, para el ciclo <b>2024-2025</b>.</p>
    </section>
    <section>
      <p>De el/los alumno(s):</p>
      <article class="alumno">
        <?php
        $sqlAlumn = "select * from alumnos where id='$id_alumno1'";
        $resultAlumn = $mysqli->query($sqlAlumn);
        while ($rowAlumn = $resultAlumn->fetch_assoc()) {
        ?>
          <p class="alumno-nombre"><?php echo $rowAlumn['nombre'] . " " . $rowAlumn['apellido_paterno'] . " " . $rowAlumn['apellido_materno'] ?></p>
          <p class="alumno-grado"><?php echo $rowAlumn['grado'] ?>°</p>
          <p class="alumno-grupo"><?php echo $rowAlumn['grupo'] ?></p>
        <?php
        }
        ?>
        <article class="alumno">
          <?php
          $sqlAlumn = "select * from alumnos where id='$id_alumno2'";
          $resultAlumn = $mysqli->query($sqlAlumn);
          while ($rowAlumn = $resultAlumn->fetch_assoc()) {
          ?>
            <p class="alumno-nombre"><?php echo $rowAlumn['nombre'] . " " . $rowAlumn['apellido_paterno'] . " " . $rowAlumn['apellido_materno'] ?></p>
            <p class="alumno-grado"><?php echo $rowAlumn['grado'] ?>°</p>
            <p class="alumno-grupo"><?php echo $rowAlumn['grupo'] ?></p>
          <?php
          }
          ?>
          <article class="alumno">
            <?php
            $sqlAlumn = "select * from alumnos where id='$id_alumno3'";
            $resultAlumn = $mysqli->query($sqlAlumn);
            while ($rowAlumn = $resultAlumn->fetch_assoc()) {
            ?>
              <p class="alumno-nombre"><?php echo $rowAlumn['nombre'] . " " . $rowAlumn['apellido_paterno'] . " " . $rowAlumn['apellido_materno'] ?></p>
              <p class="alumno-grado"><?php echo $rowAlumn['grado'] ?>°</p>
              <p class="alumno-grupo"><?php echo $rowAlumn['grupo'] ?></p>
            <?php
            }
            ?>

          </article>


          <footer>
            <p class="footer-title"><span class="footer-fl">Presidente Sr. Rubén</span><span class="footer-fl">Tesorero:
                Sra. Anabell</span><span class="footer-fl">Secretaria: Sra. Diana</span>
            </p>
            <p class="footer-title" style="margin-top: 0;"> <span class="footer-sl">Vázquez De la Rosa</span><span class="footer-sl">Rodríguez
                Romero</span><span class="footer-sl">Cruz Breton Ramos.</span>
            </p>
            <p>San Miguel Xoxtla, Pue. a <?php echo $fecha ?>.</p>
            <p><b>Este comprobante solo es válido si se presenta sello y firma autorizados.</b></p>
          </footer>

    </section>
  </article>
  <article class="comprobante">
    <header>
      <figure class="header-figure"></figure>
      <section class="header-title">
        <h1>
          ESCUELA PRIMARIA AQUILES SERDÁN
        </h1>
        <h2>CCT: 21DPR0323R</h2>
        <h2>ASOCIACIÓN DE PADRES DE FAMILIA</h2>
      </section>
      <p class="header-id">Folio<br> <?php echo $id_formato ?></p>
    </header>
    <section class="section-recibo">
      <p>Recibimos de: <b> <?php echo $nameTutor . " " . $apTutor . " " . $amTutor  ?></b> </p>
      <p> La cantidad de <b>$600.00 MXN</b> (Cuatrocientos Pesos 00/100 M.N).</p>
      <p>Por concepto de <b>mantenimiento</b> del edificio escolar, para el ciclo <b>2024-2025</b>.</p>
    </section>
    <section>
      <p>De el/los alumno(s):</p>
      <article class="alumno">
        <?php
        $sqlAlumn = "select * from alumnos where id='$id_alumno1'";
        $resultAlumn = $mysqli->query($sqlAlumn);
        while ($rowAlumn = $resultAlumn->fetch_assoc()) {
        ?>
          <p class="alumno-nombre"><?php echo $rowAlumn['nombre'] . " " . $rowAlumn['apellido_paterno'] . " " . $rowAlumn['apellido_materno'] ?></p>
          <p class="alumno-grado"><?php echo $rowAlumn['grado'] ?>°</p>
          <p class="alumno-grupo"><?php echo $rowAlumn['grupo'] ?></p>
        <?php
        }
        ?>
        <article class="alumno">
          <?php
          $sqlAlumn = "select * from alumnos where id='$id_alumno2'";
          $resultAlumn = $mysqli->query($sqlAlumn);
          while ($rowAlumn = $resultAlumn->fetch_assoc()) {
          ?>
            <p class="alumno-nombre"><?php echo $rowAlumn['nombre'] . " " . $rowAlumn['apellido_paterno'] . " " . $rowAlumn['apellido_materno'] ?></p>
            <p class="alumno-grado"><?php echo $rowAlumn['grado'] ?>°</p>
            <p class="alumno-grupo"><?php echo $rowAlumn['grupo'] ?></p>
          <?php
          }
          ?>
          <article class="alumno">
            <?php
            $sqlAlumn = "select * from alumnos where id='$id_alumno3'";
            $resultAlumn = $mysqli->query($sqlAlumn);
            while ($rowAlumn = $resultAlumn->fetch_assoc()) {
            ?>
              <p class="alumno-nombre"><?php echo $rowAlumn['nombre'] . " " . $rowAlumn['apellido_paterno'] . " " . $rowAlumn['apellido_materno'] ?></p>
              <p class="alumno-grado"><?php echo $rowAlumn['grado'] ?>°</p>
              <p class="alumno-grupo"><?php echo $rowAlumn['grupo'] ?></p>
            <?php
            }
            ?>

          </article>


          <footer>
            <p class="footer-title"><span class="footer-fl">Presidente Sr. Rubén</span><span class="footer-fl">Tesorero:
                Sra. Anabell</span><span class="footer-fl">Secretaria: Sra. Diana</span>
            </p>
            <p class="footer-title" style="margin-top: 0;"> <span class="footer-sl">Vázquez De la Rosa</span><span class="footer-sl">Rodríguez
                Romero</span><span class="footer-sl">Cruz Breton Ramos.</span>
            </p>
            <p>San Miguel Xoxtla, Pue. a <?php echo $fecha ?>.</p>
            <p><b>Este comprobante solo es válido si se presenta sello y firma autorizados.</b></p>
          </footer>

    </section>
  </article>

</body>

</html>

<?php
$html = ob_get_clean();

require_once './libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$output = $dompdf->output();

file_put_contents('../documents/reportes/formato_' . $id_formato . '.pdf', $output);

//$dompdf->stream("./reporte.pdf", array("Attachment" => true));
$res = array(
  "err" => false,
  "statusText" => "Se ha creado la solicitud"
);

echo json_encode($res);
