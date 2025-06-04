<?php
	include("../functions.php");

  if((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])) ) 
    header("Location: login.php");

  if($_SESSION['user_level'] != "staff")
    header("Location: login.php");

  if($_SESSION['user_role'] != "Mesero"){
    echo ("<script>window.alert('Solo meseros disponibles!'); window.location.href='index.php';</script>");
    exit();
  }

?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Order - FOS Staff</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

      <a class="navbar-brand mr-1" href="index.php">Restaurante | 🅱 </a>

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
        </li>
      </ul>

    </nav>

    <div id="wrapper">

      <!------------------ Sidebar ------------------->
      <ul class="sidebar navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Panel de Control</span>
          </a>
        </li>

        
        <?php

          if ($_SESSION['user_role'] == "Mesero") {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="order.php">
                <i class="fas fa-fw fa-book"></i>
                <span>Orden</span></a>
            </li>
          ';
          }

          if ($_SESSION['user_role'] == "chef") {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="kitchen.php">
                <i class="fas fa-fw fa-utensils"></i>
                <span>Kitchen</span></a>
            </li>
            ';
          }

        ?>

        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-fw fa-power-off"></i>
            <span>Cerrar Sesión</span>
          </a>
        </li>

      </ul>

      <div id="content-wrapper">

        <div class="container-fluid">

          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="index.php">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">Orden</li>
          </ol>

          <!-- Page Content -->
          <h1>Administración de Órdenes</h1>
          <hr>
          <p>Administración de nuevas órdenes en esta página.</p>
<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="card shadow-sm rounded-4 mb-4">
      <div class="card-header bg-primary text-white fs-5 fw-bold d-flex align-items-center">
        <i class="fas fa-utensils me-2"></i> Tomar Órdenes
      </div>
      <div class="card-body">
        
        <!-- Menú de opciones -->
        <div class="row g-3">
          <?php 
            $menuQuery = "SELECT * FROM tbl_menu";
            if ($menuResult = $sqlconnection->query($menuQuery)) {
              while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
          ?>
          <div class="col-6 col-md-4 col-lg-3">
            <button 
              class="btn btn-outline-primary w-100 text-wrap py-3 shadow-sm" 
              onclick="displayItem(<?php echo $menuRow['menuID']?>)">
              <i class="fas fa-hamburger fa-lg me-2"></i> 
              <?php echo $menuRow['menuName']?>
            </button>
          </div>
          <?php 
              }
            }
          ?>
        </div>

        <hr class="my-4">

        <!-- Tabla de ítems agregados -->
        <div class="table-responsive">
          <table id="tblItem" class="table table-bordered text-center align-middle">
            <!-- Aquí se agregan dinámicamente los ítems seleccionados -->
          </table>
        </div>

        <!-- Panel de cantidad y envío -->
<div id="qtypanel" class="mt-4" hidden>
  <div class="d-flex align-items-center gap-3 flex-wrap">
    <label for="qty" class="form-label fw-semibold mb-0">Cantidad:</label>
    <input id="qty" required type="number" min="1" max="50" name="qty" value="1" class="form-control w-auto" />

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="takeawayItemSwitch">
      <label class="form-check-label" for="takeawayItemSwitch">¿Para llevar?</label>
    </div>

    <button class="btn btn-success" onclick="insertItem()">
      <i class="fas fa-check me-1"></i> Enviar
    </button>
  </div>
</div>

      </div>
    </div>
  </div>
</div>

<div class="col-lg-6">
  <div class="card shadow-sm rounded-4 mb-4">
    <div class="card-header bg-success text-white fs-5 fw-bold d-flex align-items-center">
      <i class="fas fa-chart-bar me-2"></i> Lista de Órdenes
    </div>
    <div class="card-body">
      <form action="insertorder.php" method="POST" id="orderForm">
        
        <!-- Tabla de la orden -->
        <div class="table-responsive">
          <table id="tblOrderList" class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cant</th>
                <th>Total (COP)</th>
              </tr>
            </thead>
            <tbody>
              <!-- Filas agregadas dinámicamente -->
            </tbody>
          </table>
        </div>


        <!-- Total final -->
<div class="text-end fw-bold mt-2 fs-5">
  Total: <span id="finalTotal">COP $0</span>
</div>


        <!-- Enviar orden -->
        <button type="submit" name="sentorder" class="btn btn-success mt-3">
          <i class="fas fa-paper-plane me-1"></i> Enviar Orden
        </button>
      </form>
    </div>
  </div>
</div>

        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Sistema de Restaurante 🅱 2025</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Realmente deseas cerrar sesión?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" href="logout.php">Cerrar Sesión</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
  var currentItemID = null;
  const TAKEAWAY_CHARGE = 2000;

  function displayItem(id) {
    $.ajax({
      url: "displayitem.php",
      type: 'POST',
      data: { btnMenuID: id },
      success: function (output) {
        $("#tblItem").html(output);
      }
    });
  }

  function insertItem() {
    var id = currentItemID;
    var quantity = $("#qty").val();
    var isTakeaway = $("#takeawayItemSwitch").is(":checked") ? 1 : 0;

    $.ajax({
      url: "displayitem.php",
      type: 'POST',
      data: {
        btnMenuItemID: id,
        qty: quantity,
        takeaway: isTakeaway
      },
      success: function (output) {
        $("#tblOrderList tbody").append(output);
        $("#qtypanel").prop('hidden', true);
        $("#qty").val(1);
        $("#takeawayItemSwitch").prop("checked", false);
        updateTotal();
      }
    });
  }

  function setQty(id) {
    currentItemID = id;
    $("#qtypanel").prop('hidden', false);
  }

  // Eliminar fila y actualizar total
  $(document).on('click', '.deleteBtn', function (event) {
    event.preventDefault();
    $(this).closest('tr').remove();
    updateTotal();
  });

  function updateTotal() {
    let total = 0;

    // Recorrer cada fila de ítem
    $("#tblOrderList tbody tr").each(function () {
      let itemTotalText = $(this).find("td").eq(3).text().replace(/[^\d.]/g, '');
      let itemTotal = parseFloat(itemTotalText);

      if (!isNaN(itemTotal)) {
        total += itemTotal;
      }
    });

    // Mostrar total
    $("#finalTotal").text("COP $" + total.toLocaleString());
  }

</script>


  </body>

</html>