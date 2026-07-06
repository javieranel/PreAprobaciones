<?php
require_once("../../connection/auth.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Control de Permisos
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>

      .vencido {
          background-color: #ff333f; /* Rojo claro */
          color: #721c24; /* Rojo oscuro */
      }

      .por-vencer {
          background-color: #ffe135; /* Amarillo más brillante */
          color: #333300; /* Texto más oscuro */
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
          <li class="active">
            <a href="../tanqueros.php">
              <i class="fas fa-ship text-danger"></i>
              <p>Tanqueros</p>
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
          <li>
            <a href="../Contracts_Schedule.php">
              <i class="fa fa-book text-info"></i>
              <p>Contracts Schedule</p>
            </a>
          </li>
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
            <a class="navbar-brand" href="javascript:;">Tanqueros </a>
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
        $sql = "SELECT id, nombre, tipo_barcaza, nombre_empresa, permiso_SNE,
        exencion_ACP,licencia_AMP,patente_de_navegacion,ITC,PYI,CLC,CLBC,COC,
        safety_equipment,safety_radio,safety_construction,loadline,SMC,DOC,
        ISPPC,IAPP,IOPP,issc
        FROM documentos WHERE id = ?";
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
          echo "No se encontró la Tanquero con el ID especificado.";
          exit();
        }

        $stmt->close();
      } else {
        echo "ID inválido.";
        exit();
      }



      // Incluye la función en el archivo edit-barcaza.php o en un archivo separado
      function obtenerClaseFecha($fechaVencimiento) {
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
                <h5 class="card-title">Editar Tanqueros</h5>
              </div>
              <div class="card-body">
                <form>
                  <div class="row">

                      <input type="hidden" id="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                      <div class="col-md-6 pr-1">
                        <div class="form-group">
                          <label>Nombre de Tanquero</label>
                          <input type="text" class="form-control"  id="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>">
                        </div>
                      </div>

                      <div class="col-md-6 pr-1">
                        <div class="form-group">
                          <label>Compañia</label>
                          <input type="text" class="form-control" id="nombre_empresa" value="<?php echo htmlspecialchars($row['nombre_empresa']); ?>">
                        </div>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label>Certificate of Class (COC) </label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['COC']); ?>" placeholder="" id="COC" value="<?php echo htmlspecialchars($row['COC']); ?>">
                        </div>
                      </div>

                      <div class="col-md-3 px-1">
                        <div class="form-group">
                          <label>Certificado de Seguro P&I </label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['PYI']); ?>" placeholder="" id="PYI" value="<?php echo htmlspecialchars($row['PYI']); ?>">
                        </div>
                      </div>

                      <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label>CLC</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['CLC']); ?>" placeholder="" id="CLC" value="<?php echo htmlspecialchars($row['CLC']); ?>">
                        </div>
                      </div>
                    

                    <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label>CLBC</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['CLBC']); ?>" placeholder="" id="CLBC" value="<?php echo htmlspecialchars($row['CLBC']); ?>">
                        </div>
                    </div>
                  </div>

                    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
                    <div class="row">

                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label> Safety Equipment </label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['safety_equipment']); ?>" placeholder="" id="safety_equipment" value="<?php echo htmlspecialchars($row['safety_equipment']); ?>">
                        </div>
                      </div>

                      <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label> Safety Radio </label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['safety_radio']); ?>" placeholder="" id="safety_radio" value="<?php echo htmlspecialchars($row['safety_radio']); ?>">
                        </div>
                      </div>

                      <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label> Safety Construction</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['safety_construction']); ?> " placeholder="" id="safety_construction" value="<?php echo htmlspecialchars($row['safety_construction']); ?>">
                        </div>
                      </div>

                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label>Loadline</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['loadline']); ?>" placeholder="" id="loadline" value="<?php echo htmlspecialchars($row['loadline']); ?>">
                        </div>
                      </div>

                    </div>
                    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
                    <div class="row">
                      

                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label>IOPP</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['IOPP']); ?>" placeholder="" id="IOPP" value="<?php echo htmlspecialchars($row['IOPP']); ?>">
                        </div>
                      </div>
                      <div class="col-md-3 px-1">
                        <div class="form-group">
                          <label> SMC </label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['SMC']); ?>" placeholder="" id="SMC" value="<?php echo htmlspecialchars($row['SMC']); ?>">
                        </div>
                      </div>
                      <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label>DOC</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['DOC']); ?>" placeholder="" id="DOC" value="<?php echo htmlspecialchars($row['DOC']); ?>">
                        </div>
                      </div>
                      <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label>ISPPC</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['ISPPC']); ?>" placeholder="" id="ISPPC" value="<?php echo htmlspecialchars($row['ISPPC']); ?>">
                        </div>
                      </div>
                    </div>
                    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
                    <div class="row">
                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label>ISSC</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['issc']); ?>" placeholder="" id="issc" value="<?php echo htmlspecialchars($row['issc']); ?>">
                        </div>
                      </div>
                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label>IAPP</label>
                          <input type="date" class="form-control <?php echo obtenerClaseFecha($row['IAPP']); ?>" placeholder="" id="IAPP" value="<?php echo htmlspecialchars($row['IAPP']); ?>">
                        </div>
                      </div>

                      
                    </div>
                </form>

                <button type="button" class="btn btn-primary" onclick="guardarCambiosTanqueros()">Guardar Cambios</button>

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
  <!--  Notifications Plugin    -->
  <script src="../../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="../../assets/demo/demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>