<?php
require_once("../../connection/auth.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Contracts Schedule
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../../assets/demo/demo.css" rel="stylesheet" />
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <style>
    .vencido {
      background-color: #ff333f;
      /* Rojo claro */
      color: #721c24;
      /* Rojo oscuro */
    }

    .por-vencer {
      background-color: #ffe135;
      /* Amarillo más brillante */
      color: #333300;
      /* Texto más oscuro */
    }
  </style>


</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo" style="display: flex; align-items: center;">
        <a class="simple-text logo-normal" href="#">
          <img src="../../assets/image/WHATSAPP (002).png" alt="Logo" style="height: 50px; margin-right: 10px;">
          Pre-Aprobaciones 
        </a>
      </div>
      <div class="sidebar-wrapper">
      <ul class="nav">
          <li>
            <a href="../dashboard.php">
              <i class="fas fa-tachometer-alt text-primary"></i>
              <p>STATUS DE PERMISOS</p>
            </a>
          </li>
          <li>
            <a href="../barcazas.php">
              <i class="fas fa-ship text-warning"></i>
              <p>Barcazas</p>
            </a>
          </li>
          <li>
            <a href="../capitanes_barcazas.php">
              <i class="fas fa-user-shield text-success"></i>
              <p>Capitanes de Barcazas</p>
            </a>
          </li>
          <li>
            <a href="../inspectores.php">
              <i class="fas fa-search text-primary"></i>
              <p>Inspectores</p>
            </a>
          </li>
          <li>
            <a href="../cia_inspeccion.php">
              <i class="fas fa-building text-primary"></i>
              <p>Cia de Inspección</p>
            </a>
          </li>
          <li>
            <a href="../remolcadores.php">
              <i class="fas fa-anchor text-info"></i>
              <p>Remolcadores</p>
            </a>
          </li>
          <li>
            <a href="../pilotos.php">
              <i class="fas fa-user-group text-info"></i>
              <p>Pilotos</p>
            </a>
          </li>
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador') { ?>
            <li>
              <a href="../Contracts_Schedule.php">
                <i class="fa fa-book text-info"></i>
                <p>Contracts Schedule</p>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:;">Contracts Schedule - <script>
                document.write(new Date().getFullYear())
              </script></a>

          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>

      </nav>
      <!-- End Navbar -->


      <!-- PHP  CODE  -->
      <?php

      require_once "../../connection/db_connection.php";

      if ($con->connect_error) {
        die("Conexión fallida: " . $con->connect_error);
      }

      //captura el  id de la URL para mostrar los datos de la barcaza
      $id = isset($_GET['id']) ? intval($_GET['id']) : 0;


      if ($id > 0) {
        $sql = "SELECT id, tank, producto,
        cliente,volumen,amendment,expiration,SNE
        FROM contracts_schedule WHERE id = ?";
        $stmt = $con->prepare($sql);

        if ($stmt === false) {
          // Mostrar el error de la consulta
          die("Error en la preparación de la consulta: " . $con->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
        } else {
          echo "No se encontró elD especificado.";
          exit();
        }

        $stmt->close();
      } else {
        echo "ID inválido.";
        exit();
      }



      function obtenerClaseFecha($fechaVencimiento)
      {
        $fechaActual = date("Y-m-d");
        if (empty($fechaVencimiento)) {
          return ''; // Sin clase
        }
        $diferenciaDias = (strtotime($fechaVencimiento) - strtotime($fechaActual)) / (60 * 60 * 24);

        if ($diferenciaDias < 0) {
          return 'vencido';
        } elseif ($diferenciaDias <= 30) {
          return 'por-vencer';
        }
        return '';
      }
      $con->close();
      ?>








      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Editar Contracts Schedule </h5>
              </div>
              <div class="card-body">
                <form>
                  <div class="row">


                    <input type="hidden" id="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>tank</label>
                        <input type="text" class="form-control" id="tank" value="<?php echo htmlspecialchars($row['tank']); ?>">
                      </div>
                    </div>

                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>producto</label>
                        <input type="text" class="form-control" id="producto" value="<?php echo htmlspecialchars($row['producto']); ?>">
                      </div>
                    </div>

                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>cliente</label>
                        <input type="text" class="form-control" id="cliente" value="<?php echo htmlspecialchars($row['cliente']); ?>">
                      </div>
                    </div>

                  </div>


                  <div class="row">

                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label>volumen</label>
                        <input type="text" class="form-control" id="volumen" value="<?php echo htmlspecialchars($row['volumen']); ?>">
                      </div>
                    </div>

                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label>amendment</label>
                        <input type="text" class="form-control" id="amendment" value="<?php echo htmlspecialchars($row['amendment']); ?>">
                      </div>
                    </div>

                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">expiration</label>
                        <input type="date" class="form-control <?php echo obtenerClaseFecha($row['expiration']); ?>" id="expiration" value="<?php echo htmlspecialchars($row['expiration']); ?>">
                      </div>
                    </div>

                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">SNE</label>
                        <input type="date" class="form-control <?php echo obtenerClaseFecha($row['SNE']); ?>" id="SNE" value="<?php echo htmlspecialchars($row['SNE']); ?>">
                      </div>
                    </div>


                  </div>


              </div>
              </form>

              <button type="button" class="btn btn-primary" onclick="guardarCambiosContracts_Schedule()">Guardar Cambios</button>

            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer footer-black  footer-white ">

    </footer>
  </div>
  </div>



  <!--   Core JS Files   -->
  <script src="../../assets/js/jquery.min.js"></script>
  <script src="../../assets/js/popper.min.js"></script>
  <script src="../../assets/js/bootstrap.min.js"></script>
  <!--   Core JS Files   -->
  <script src="../../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../../assets/js/ajax.js"></script>
  <!-- Chart JS -->
  <script src="../../assets/js/plugins/chartjs.min.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="../../assets/demo/demo.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>