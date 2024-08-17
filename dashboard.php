<?php
  include("inc/header.php");
  include("inc/sidebar.php");
?>



<!-- Main -->
<main class="main-container">


  <!-- <div class="main-title">
    <p class="font-weight-bold">DASHBOARD</p>
  </div> -->

  <div class="main-cards">
    <!-- Cards -->
    <div class="card">
      <div class="card-inner">
        <p class="text-primary font-weight-bold">Total Clients</p>
        <span class="material-icons-outlined text-blue">group</span>
      </div>
      <span class="font-weight-bold text-blue">
        <strong>
          <?php
            // display number of registered clients
            echo htmlspecialchars($clientCount); 
          ?>
        </strong>
      </span>
    </div>

    <div class="card">
      <div class="card-inner">
        <p class="text-primary font-weight-bold">Reports</p>
        <span class="material-icons-outlined text-orange">receipt_long</span>
      </div>
      <span class="text-orange font-weight-bold">
        <strong>0</strong>
      </span>
    </div>

    <div class="card">
      <div class="card-inner">
        <p class="text-primary font-weight-bold">Total Revenue</p>
        <span class="material-icons-outlined text-success">payments</span>
      </div>
      <span class="text-green font-weight-bold">
        <strong>Kes.&nbsp;</strong><?php echo htmlspecialchars(number_format($total_revenue, 2)); ?>
      </span>
    </div>

    <div class="card">
      <div class="card-inner">
        <p class="text-primary font-weight-bold">Pending Bills</p>
        <span class="material-icons-outlined text-red">notification_important</span>
      </div>
      <span class="text-red font-weight-bold">
        <strong>
          <?php echo $pendingBillsCount; ?>
        </strong>
      </span>
    </div>

  </div>

  <!-- Analysis charts -->
  <div class="charts">

    <div class="charts-card">
      <p class="chart-title">Revenue</p>
      <div id="bar-chart"></div>
    </div>

    <div class="charts-card">
      <p class="chart-title">Total Billed and Pending Bills</p>
      <div id="area-chart"></div>
    </div>

  </div>

  <?php include("inc/footer.php"); ?>