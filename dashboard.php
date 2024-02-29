<?php

session_start();

include '../../conn.php';

if(!isset($_SESSION["loggedinasadmin"]) || $_SESSION["loggedinasadmin"] !== true){
    header("location: ../../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php include 'components/icon.php'; ?>
  
  <title><?php echo $title; ?> - Dashboard</title>

  

  <!-- Main Template -->
  <link rel="stylesheet" href="../../assets/css/styles.min.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
<?php include '../admin/components/navigation.php'; ?>

  <!--  Main wrapper -->
  <div class="body-wrapper">

  <?php include '../admin/components/header.php'; ?>

  <?php
      $stmt = $conn->prepare("SELECT SUM(stocks) FROM ims_products WHERE status = ?");
      $stmt->bind_param("s", $status);
      
      $status = 'Usable';
      $stmt->execute();
      $stmt->bind_result($usable_products);
      $stmt->fetch();
      
      $status = 'Defective';
      $stmt->execute();
      $stmt->bind_result($defective_products);
      $stmt->fetch();
      
      $status = 'Inactive';
      $stmt->execute();
      $stmt->bind_result($inactive_products);
      $stmt->fetch();
      
      $stmt->close();
  ?>
  <div class="container-fluid">
        <div class="sample-count-boxes">
          <div class="sample-count-box">
            <h1>Usable Products</h1>
            <span id="sample-count"><?php echo $usable_products !== null && $usable_products !== '' ? $usable_products : 0 ; ?></span>
          </div>
          <div class="sample-count-box">
            <h1>Defective Products</h1>
            <span id="sample-count"><?php echo $defective_products !== null && $defective_products !== '' ? $defective_products : 0 ; ?></span>
          </div>
          <div class="sample-count-box">
            <h1>Inactive Products</h1>
            <span id="sample-count"><?php echo $inactive_products !== null && $inactive_products !== '' ? $inactive_products : 0 ; ?></span>
          </div>
        </div>
        <div class="card w-100">
          <div class="card-body p-4">
            <div class="d-flex">
              <div class="flex-grow-1 "><h5 class="card-title fw-semibold mb-4 text-center">Logs</h5></div>
            </div>
            <div class="table-responsive">
              <table id="myTable" class="table text-nowrap mb-0 align-middle" >
                <thead class="text-dark fs-4">
                  <tr>
                  <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0"></h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0 text-center">Subject</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0 text-center">Description</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0 text-center">Date Modified</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT * FROM ims_logs ORDER BY date_created DESC";
                    if($rs=$conn->query($sql)){
                      $i = 0;
                        while ($row=$rs->fetch_assoc()) {
                          $i++;
                    ?>
                  <tr>
                    <td class="border-bottom-0 text-center"><h6 class="fw-semibold mb-0"><?php echo $i; ?></h6></td>
                    <td class="border-bottom-0 text-center"><h6 class="fw-semibold mb-0"><?php echo $row['subject']; ?></h6></td>
                    <td class="border-bottom-0 text-wrap text-center"><h6 class="fw-semibold mb-0"><?php echo $row['description']; ?></h6></td>
                    <td class="border-bottom-0 text-center"><h6 class="fw-semibold mb-0"><?php 
                    $date = new DateTime($row['date_created']);

                    // Format the DateTime object to the desired format
                    $formattedDate = $date->format('F j, Y'.' @ '.'h:i A');

                    echo $formattedDate; ?></h6></td>
                    <?php
                            }
                            }
                          ?>
                  </tr>      
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>





  </div>
</div>
  </div>
  <script>
    // Find all elements with class 'sample-count-box'
    var sampleCountBoxes = document.querySelectorAll(".sample-count-box");

    // Loop through each box
    sampleCountBoxes.forEach(function(box) {
      var h1 = box.querySelector("h1");

      // Check the text content of the h1 element and change the background color accordingly
      if (h1.textContent === 'Usable Products') {
        box.style.backgroundColor = 'green';
      } else if (h1.textContent === 'Defective Products') {
        box.style.backgroundColor = 'orange';
      } else if (h1.textContent === 'Inactive Products') {
        box.style.backgroundColor = 'red';
      }
    });
  </script>
  <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/sidebarmenu.js"></script>
  <script src="../../assets/js/app.min.js"></script>
  <script src="../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../../assets/js/dashboard.js"></script>

</body>

</html>