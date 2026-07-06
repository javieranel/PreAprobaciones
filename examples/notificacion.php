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
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <!--     Fonts and icons     -->
    <link href="https://cdn.jsdelivr.net/gh/creativetimofficial/now-ui-icons/css/now-ui-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- CSS Files 
  
  -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/demo/demo.css" rel="stylesheet" />
    <!-- Incluye SweetAlert2 JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">



</head>
<style>
    .notificacion {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        margin-bottom: 8px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .notificacion .mensaje {
        flex: 1 1 auto;
        /* ocupa el espacio disponible */
        overflow: hidden;
        /* corta el contenido si es muy largo */
        text-overflow: ellipsis;
        /* pone ... cuando se corta */
        white-space: nowrap;
        /* no permite que el texto baje a otra línea */
        min-width: 0;
        /* muy importante para truncar correctamente dentro de flex */
    }




    .boton-eliminar {
        background: none;
        border: none;
        cursor: pointer;
        color: #cc0000;
        font-size: 16px;
        padding: 0;
        margin-left: 10px;
        vertical-align: middle;
    }

    .boton-eliminar:hover {
        color: #ff4444;
    }



    .boton-ver:hover {
        color: #0056b3;
    }



    .cerrar {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .cerrar:hover {
        color: black;
    }
</style>




<body class="">
    <div class="wrapper ">
        <div class="sidebar" data-color="white" data-active-color="danger">
            <div class="logo" style="display: flex; align-items: center;">
                <a class="simple-text logo-normal" href="#">
                    <img src="../assets/image/WHATSAPP (002).png" alt="Logo" style="height: 50px; margin-right: 10px;">
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
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                        </div>
                        <a class="navbar-brand" href="javascript:;"> Notificaciones</a>
                    </div>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    </div>
                </div>

            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                            </div>

                            <div class="card-body">

                                <div class="container mt-4">
                                    <?php
                                    require_once "../connection/db_connection.php";

                                    $sql = "SELECT id, mensaje, visto FROM notificaciones ORDER BY id DESC";
                                    $result = $con->query($sql);

                                    if ($result->num_rows > 0): ?>
                                        <div id="lista-notificaciones" class="notificaciones-container">
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <div class="notificacion">
                                                    <span class="mensaje"><?php echo htmlspecialchars($row['mensaje']); ?></span>

                                                    <button class="boton-eliminar" onclick="eliminarNotificacion(event, <?php echo $row['id']; ?>)">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>


                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>No hay notificaciones disponibles.</p>
                                    <?php endif;

                                    $con->close();
                                    ?>
                                </div>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--   Core JS Files   -->




    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/js/ajax.js"></script>
    <!-- Chart JS -->
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="../assets/demo/demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>







</body>

</html>