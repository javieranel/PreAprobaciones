<?php
require_once("../connection/auth.php");
require_once "../php_functions/Update_Expiration_Date.php";

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
  <link href="https://cdn.jsdelivr.net/gh/creativetimofficial/now-ui-icons/css/now-ui-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">

  <!-- CSS Files 
  
  -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <!-- Incluye SweetAlert2 JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



</head>

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
          <li>
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
            <li class="active">
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
            <a class="navbar-brand" href="javascript:;"> Data Base Contracts Schedule</a>
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
                <h4 class="card-title text-primary"> Contracts Schedule - <script>
                    document.write(new Date().getFullYear())
                  </script>
                </h4>
              </div>

              <div class="card-body">

                <div class="row">
                  <div class="col-md-6">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarContracts">
                      Agregar Contracts
                    </button>
                  </div>
                </div>

                <?php
                // Conexión a la base de datos
                require_once "../connection/db_connection.php";

                // Obtener las compañías de la categoría 'cia_inspeccion'
                $categoria = 'cliente'; // este debe coincidir con el valor guardado en tu BDD
                $query = $con->prepare("SELECT DISTINCT compania FROM categories WHERE categoria = ?");
                $query->bind_param("s", $categoria);
                $query->execute();
                $result = $query->get_result();
                ?>

                <div class="modal fade" id="modalAgregarContracts" tabindex="-1" role="dialog" aria-labelledby="modalAgregarContractsLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarContractsLabel">Agregar Contracts Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">

                        <form id="formularioAgregarContracts">

                          <label for="tank">Tank</label>
                          <input type="text" id="tank" name="tank" class="form-control" required>

                          <label for="producto">Producto</label>
                          <input type="text" id="producto" name="producto" class="form-control" required>

                          <label for="cliente"> Cliente </label>
                          <select id="cliente" name="cliente" class="form-control" required>
                            <option value="">-- Selecciona una compañía --</option>
                            <?php while ($row = $result->fetch_assoc()): ?>
                              <option value="<?= htmlspecialchars($row['compania']) ?>">
                                <?= htmlspecialchars($row['compania']) ?>
                              </option>
                            <?php endwhile; ?>
                          </select>

                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarContracts_Schedule()">Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="table-responsive">
                  <!-- ... tu contenido HTML anterior ... -->

                  <table class="table">
                    <thead class=" text-primary">
                      <th>
                        Tank
                      </th>
                      <th>
                        Producto
                      </th>
                      <th>
                        Cliente
                      </th>
                      <th>
                        Status
                      </th>
                      <th>
                        Edit
                      </th>
                      <th>
                        Delete
                      </th>

                    </thead>
                    <tbody>
                      <?php

                      require_once "../connection/db_connection.php";

                      if ($con->connect_error) {
                        die("Conexión fallida: " . $con->connect_error);
                      }

                      // Consulta para obtener los datos
                      $sql = "SELECT id, tank, producto, cliente,expiration, SNE, status FROM contracts_schedule";

                      // Ejecutar la consulta y manejar errores
                      $result = $con->query($sql);

                      if ($result === false) {
                        // Mostrar el error de SQL si la consulta falla
                        echo "Error en la consulta SQL: " . $con->error;
                      } else {
                        if ($result->num_rows > 0) {
                          // Mostrar los datos en la tabla
                          while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['tank']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['producto']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cliente']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td><a href='../examples/Contracts_Schedule/edit-contracts_schedule.php?id=" . htmlspecialchars($row['id']) . "'><button class='btn btn-primary btn-edit' onclick='editarContracts_Schedule(" . htmlspecialchars($row['id']) . ")'>EDITAR</button></a></td>";
                            echo "<td> <button class='btn btn-danger btn-edit' onclick='Delete_Contracts_Schedule(" . htmlspecialchars($row['id']) . ")'>DELETE</button></a></td>";


                            echo "</tr>";
                          }
                        } else {
                          echo "<tr><td colspan='3'>No se encontraron barcazas</td></tr>";
                        }
                      }

                      function actualizarEstado($id)
                      {
                        global $con; // Conexión a la base de datos

                        // Obtener las fechas desde la base de datos
                        $query = "SELECT expiration, SNE FROM contracts_schedule WHERE id = ?";
                        $stmt = $con->prepare($query);

                        // Comprobar si la preparación de la consulta fue exitosa
                        if (!$stmt) {
                          die("Error en la consulta: " . $con->error);
                        }

                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $stmt->bind_result($expiration, $SNE);
                        $stmt->fetch();
                        $stmt->close();

                        // Obtener la fecha actual
                        $hoy = date("Y-m-d");

                        // Verificar si alguna fecha está vencida
                        if ($expiration < $hoy || $SNE < $hoy) {
                          $status = "Desaprobado";
                        } else {
                          $status = "Aprobado";
                        }

                        // Actualizar el estado en la base de datos
                        $updateQuery = "UPDATE contracts_schedule SET status = ? WHERE id = ?";
                        $updateStmt = $con->prepare($updateQuery);

                        // Comprobar si la preparación de la consulta de actualización fue exitosa
                        if (!$updateStmt) {
                          die("Error en la consulta de actualización: " . $con->error);
                        }

                        $updateStmt->bind_param("si", $status, $id);
                        $updateStmt->execute();
                        $updateStmt->close();
                      }


                      function actualizarEstadoDeTodos()
                      {
                        global $con; // Conexión a la base de datos

                        // Obtener todos los IDs de los registros
                        $query = "SELECT id FROM contracts_schedule";
                        $result = $con->query($query);

                        // Verificar si la consulta fue exitosa
                        if (!$result) {
                          die("Error en la consulta: " . $con->error); // Muestra el error exacto de la consulta
                        }

                        // Recorrer todos los IDs y llamar a la función para cada uno
                        while ($row = $result->fetch_assoc()) {
                          $id = $row['id'];
                          actualizarEstado($id); // Llamada a la función de actualización
                        }
                      }


                      actualizarEstadoDeTodos();





                      $con->close();

                      ?>


                    </tbody>
                  </table>

                  <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Detalles de Contracts Schedule </h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="detallesContent">

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                      </div>
                    </div>
                  </div>
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