<?php
require_once("../connection/auth.php");
require_once "../php_functions/Update_Expiration_Date.php";
require_once "../connection/db_connection.php";


$sql = "SELECT categoria, 
               COUNT(CASE WHEN status = 'Aprobado' THEN 1 END) AS cantidad 
        FROM documentos 
        GROUP BY categoria";

// Ejecutar la consulta
$resultado = $con->query($sql);

// Validar la consulta
if (!$resultado) {
  die("Error en la consulta: " . $con->error);
}

// Inicializar una variable para almacenar los datos
$datos_aprobados = [];

if ($resultado->num_rows > 0) {
  // Recorrer los resultados y almacenarlos en el array
  while ($fila = $resultado->fetch_assoc()) {
    $datos_aprobados[$fila['categoria']] = $fila['cantidad'];
  }
} else {
  echo "No hay datos disponibles.";
}




// Paso 1: Obtener casas de inspección vencidas
$sql_cias_vencidas = "SELECT DISTINCT nombre_empresa 
                      FROM documentos 
                      WHERE categoria = 'Cia_Inspeccion' AND status = 'Desaprobado'";
$result = $con->query($sql_cias_vencidas);

$cias_vencidas = [];
while ($row = $result->fetch_assoc()) {
  $cias_vencidas[] = $row['nombre_empresa'];
}

// Paso 2: Actualizar inspectores a 'Desaprobado' si su casa está vencida
foreach ($cias_vencidas as $empresa) {
  // Escapar nombre de empresa para prevenir SQL injection
  $empresa_escapada = $con->real_escape_string($empresa);

  $sql_update = "UPDATE documentos 
                   SET status = 'Desaprobado' 
                   WHERE categoria = 'Inspectores' AND nombre_empresa = '$empresa_escapada'";

  $con->query($sql_update);
}





// Cerrar conexión
$con->close();



?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Control de Permisos
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
    name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <style>
    .badge-danger {
      color: white;
      font-size: 12px;
      padding: 2px 6px;
      border-radius: 100%;
      position: absolute;
      top: 10px;
      right: 5px;
    }

    .close-btn {
      background: none;
      border: none;
      color: red;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      margin-left: 10px;
    }

    .logo a {
      font-size: 5px;
      /* Ajusta el tamaño aquí */
    }

    .btn-outline-danger {
      border-width: 2px;
      /* Cambiar grosor del borde */
    }

    .btn-outline-danger:hover {
      background-color: #dc3545;
      /* Rojo de fondo al pasar el mouse */
      color: white;
    }

    .navbar-toggler-icon {
      background-color: rgba(0, 0, 0, 0.6);
      /* Cambia el color si es necesario */
      border-radius: 3px;
      width: 30px;
      height: 3px;
      display: block;
      margin: 5px 0;
    }
  </style>


</head>

<body class="">

  <div class="wrapper ">
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo" style="display: flex; align-items: center;">
        <a class="simple-text logo-normal" href="#">
          <img src="../assets/image/WHATSAPP (002).png" alt="Logo" style="height: 50px; margin-bottom: 10px; ">
          Pre-Aprobaciones
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="active">
            <a href="./dashboard.php">
              <i class="fas fa-tachometer-alt text-primary"></i>
              <p>STATUS DE PERMISOS</p>
            </a>
          </li>

          <?php if (
            isset($_SESSION['rol']) &&
            in_array(
              $_SESSION['rol'],
              ["Administrador"]
            )
          ) {
          ?>
            <li>
              <a href="./barcazas.php">
                <i class="fas fa-ship text-warning"></i>
                <p>Barcazas</p>
              </a>
            </li>
            <li>
              <a href="./capitanes_barcazas.php">
                <i class="fas fa-user-shield text-success"></i>
                <p>Capitanes de Barcazas</p>
              </a>
            </li>
            <li>
              <a href="./inspectores.php">
                <i class="fas fa-search text-primary"></i>
                <p>Inspectores</p>
              </a>
            </li>
            <li>
              <a href="./cia_inspeccion.php">
                <i class="fas fa-building text-primary"></i>
                <p>Cia de Inspección</p>
              </a>
            </li>
            <li>
              <a href="./remolcadores.php">
                <i class="fas fa-anchor text-info"></i>
                <p>Remolcadores</p>
              </a>
            </li>
            <li>
              <a href="./pilotos.php">
                <i class="fas fa-user-group text-info"></i>
                <p>Pilotos</p>
              </a>
            </li>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador') { ?>
              <li>
                <a href="./Contracts_Schedule.php">
                  <i class="fa fa-book text-info"></i>
                  <p>Contracts Schedule</p>
                </a>
              </li>
            <?php } ?>
            <li>
              <a href="./categories.php">
                <i class="fa fa-tags text-info"></i>
                <p>Categorias</p>
              </a>
            </li>
          <?php
          }
          ?>
        </ul>
      </div>
    </div>
    <div class="main-panel">

      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <!-- Botón de navegación -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-between" id="navigation">
            <ul class="navbar-nav align-items-center">
              <!-- Usuario en sesión -->


              <li class="nav-item d-flex align-items-center me-4">
                <!-- Avatar circular con inicial -->
                <div
                  class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-semibold"
                  style="width: 40px; height: 40px; font-size: 1.2rem; user-select: none; margin-right: 10px;"
                  title="<?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Usuario'); ?>">
                  <?php

                  
                  
                  $nombre = $_SESSION['usuario'] ?? 'Usuario';
                  $rol = $_SESSION['rol'] ?? 'rol';
                  echo strtoupper(substr($nombre, 0, 2));
                  ?>
                </div>
                <!-- Nombre del usuario -->
                <span
                  class="text-dark fw-semibold ms-4"
                  style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem; letter-spacing: 0.03em;">
                  <?php echo htmlspecialchars($nombre); ?> - <?php echo htmlspecialchars($rol); ?>
                </span>
              </li>
            </ul>

            <!-- Botones de acción -->
            <ul class="navbar-nav d-flex align-items-center">
              <?php if (
                isset($_SESSION['rol']) &&
                in_array(
                  $_SESSION['rol'],
                  ["Administrador"]
                )
              ) {
              ?>

                <li class="nav-item me-2">
                  <button class="btn btn-primary btn-sm rounded-pill shadow-sm px-3"
                    onclick="window.location.href='../admin_users/admin_users.php'"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Administrar usuarios">
                    <i class="fa fa-user-plus me-1"></i> Administrar usuarios
                  </button>
                </li>


              <?php } ?>
              <li class="nav-item">
                <button class="btn btn-danger btn-sm rounded-pill shadow-sm px-3"
                  onclick="cerrarSesion()"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="Cerrar sesión">
                  <i class="fa fa-sign-out me-1"></i> Salir
                </button>
              </li>
            </ul>
          </div>
        </div>

      </nav>




      <!-- End Navbar -->
      <div class="content">

        <!---  GERENTES -- ADMINISTRADORES -- OPERACIONES -- HSE --  --->
        <?php if (
          isset($_SESSION['rol']) &&
          in_array(
            $_SESSION['rol'],
            ["Administrador", "Gerente General", "Operaciones", "HSE"]
          )
        ) {
        ?>


          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-ship text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">BARCAZAS</p>
                        <p class="card-title"> <?php echo $datos_aprobados['Barcazas']; ?>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Barcazas">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-user-shield text-success"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">CAPITANES</p>
                        <p class="card-title"> <?php echo $datos_aprobados['Capitanes']; ?>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Capitanes">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>
          <!---  
            <div class="col-lg-4 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-ship text-danger"></i>
                      </div>
                    </div>
                    
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">TANQUEROS</p>
                        <p class="card-title"> <?php echo $datos_aprobados['Tanqueros']; ?>
                        <p>
                      </div>
                    </div>
                    
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Tanqueros">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>
            --->
        <?php if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ["Administrador", "Gerente General", "Operaciones", "HSE", "Garita de Seguridad"])) { ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-search text-primary"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">INSPECTORES</p>
                        <p class="card-title"><?php echo $datos_aprobados['Inspectores']; ?>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Inspectores">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-building text-primary"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">CIA DE INSPECCION</p>
                        <p class="card-title"> <?php echo $datos_aprobados['Cia_Inspeccion']; ?>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Cia_Inspeccion">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>

          </div>

        <?php
          }
        ?>


        <?php
        }
        ?>


        <!---  GERENTES -- ADMINISTRADORES -- OPERACIONES -- HSE -- SEGURIDAD --->


          <!---  GERENTES -- ADMINISTRADORES -- OPERACIONES -- HSE ----->
          <?php if (isset($_SESSION['rol']) && (
            $_SESSION['rol'] == "Administrador" ||
            $_SESSION['rol'] == "Gerente General" ||
            $_SESSION['rol'] == "Operaciones" ||
            $_SESSION['rol'] == "HSE")) {
          ?>
            
          
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-anchor text-info"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">REMOLCADORES</p>
                        <p class="card-title"> <?php echo $datos_aprobados['Remolcadores']; ?>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Remolcadores">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>


            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fas fa-user-alt text-info"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">PILOTOS</p>
                        <p class="card-title"> <?php echo $datos_aprobados['Pilotos']; ?>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Approved_ToDate.php?id=Pilotos">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
          <!---  GERENTES -- ADMINISTRADORES -- OPERACIONES -- HSE ----->

          <!--- SOLO GERENTES Y ADMINISTRADORES----->

          <?php if (isset($_SESSION['rol']) && (
            $_SESSION['rol'] == "Administrador")) {
          ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fa fa-calendar-times text-info"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">STATUS DE LICENCIAS POR VENCER </p>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Expired_Expired_Dates.php">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>
          <?php
          }
          ?>

            <?php
            $rol = $_SESSION['rol'] ?? '';

            if ($rol == 'Administrador' || $rol == 'Gerente General') {
            ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="fa fa-book text-info"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Contracts Schedule - <script>
                            document.write(new Date().getFullYear())
                          </script>
                        </p>
                        <p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer ">
                  <hr>
                  <a href="../php_functions/Update_Contracts_Schedule.php">
                    <div class="stats">
                      <i class="fa fa-refresh"></i>
                      Ver detalles
                    </div>
                  </a>
                </div>
              </div>
            </div>
          <?php
          }

          ?>
          <!--- SOLO GERENTES Y ADMINISTRADORES----->
          </div>
      </div>

    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

  <!-- Chart JS -->
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
  <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <script src="../assets/js/ajax.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  


</body>

</html>