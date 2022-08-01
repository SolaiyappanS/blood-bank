<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
      Chart.defaults.global.defaultFontColor = "#fff";
      function toggleSummaryView() {
        if(document.getElementById("summaryButton").innerHTML == "Show Detailed Summary") {
          document.getElementById("phpBox").style.display = "contents";
          document.getElementById("summaryButton").innerHTML = "Hide Detailed Summary";
        }
        else {
          document.getElementById("phpBox").style.display = "none";
          document.getElementById("summaryButton").innerHTML = "Show Detailed Summary";
        }
      }
      function randomColorArray(length) {
        var result = new Array(length);
        for (var i = 0; i < length; i++) {
          var rgb = new Array(3);
          rgb[0] = Math.floor(Math.random() * 255);
          rgb[1] = Math.floor(Math.random() * 255);
          rgb[2] = Math.floor(Math.random() * 255);
          result[i] = "rgb(" + rgb.join(",") + ")";
        }
        return result;
      }
    </script>
    <?php
      $servername = "localhost";
      $username = "root";
      $password = "solai2701";
      $dbname = "bloodbankdb";

      $conn = new mysqli($servername, $username, $password, $dbname);
      
      $cityNames = array();
      $cityValues = array();
      $stateNames = array();
      $stateValues = array();
      $bloodGroups = array();
      $bloodValues = array();
      $bloodCount = array();

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "select City, sum(units) as Units from patient_details group by City;";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          array_push($cityNames,$row['City']);
          array_push($cityValues,$row['Units']);
        }
      }

      $sql = "select h.state_union_territory as State, sum(p.units) as Units from patient_details p ".
              "join officer_details o on p.officer_id = o.officer_id join hospital_details ".
              "h on o.hospital_id = h.hospital_id group by State;";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          array_push($stateNames,$row['State']);
          array_push($stateValues,$row['Units']);
        }
      }

      $sql = "select Blood_Group as BG, sum(units) as Units from patient_details group by BG;";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          array_push($bloodGroups,$row['BG']);
          array_push($bloodValues,$row['Units']);
        }
      }

      $sql = "select sum(O_Pos) as OPos, sum(O_Neg) as ONeg, sum(A_Pos) as APos, ".
              "sum(A_Neg) as ANeg, sum(B_Pos) as BPos, sum(B_Neg) as BNeg, ".
              "sum(AB_Pos) as ABPos, sum(AB_Neg) as ABNeg, sum(A1B_Pos) as A1BPos, ".
              "sum(A1B_Neg) as A1BNeg from blood_details;";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          array_push($bloodCount,$row['OPos'],$row['ONeg'],$row['APos'],$row['ANeg']);
          array_push($bloodCount,$row['BPos'],$row['BNeg'],$row['ABPos'],$row['ABNeg']);
          array_push($bloodCount,$row['A1BPos'],$row['A1BNeg']);
        }
      }
      $conn->close();
    ?>
    <title>Blood Bank Statistics</title>
  </head>
  <body class="text-white bg-dark">
    <div
      class="text-center"
      style="min-height: 100vh;"
    >
      <div class="d-flex">
        <div class="cover-container d-flex p-3 mx-auto">
          <main class="px-3 overflow-auto bg-dark">
            <h1>Summary</h1>

            <h5 class="mt-3">Blood Group Stock Details</h5>
            <canvas id="barChart" class="p-4" width="1000"></canvas>
            <script>
              var barXValues = [
                "O+ve",
                "O-ve",
                "A+ve",
                "A-ve",
                "B+ve",
                "B-ve",
                "AB+ve",
                "AB-ve",
                "A1B+ve",
                "A1B-ve",
              ];
              var barYValues = <?php echo json_encode($bloodCount); ?>;

              new Chart("barChart", {
                type: "bar",
                data: {
                  labels: barXValues,
                  datasets: [
                    {
                      backgroundColor: "#dc3545",
                      borderColor: "rgba(0,0,0,0)",
                      data: barYValues,
                    },
                  ],
                },
                options: {
                  legend: { display: false },
                  title: { display: false },
                  scales: {
                    yAxes: [ {
                      display: true,
                      scaleLabel: {
                        display: true,
                        labelString: 'Units of Blood'
                      }
                    } ]
                  }
                },
              });
            </script>
            <h5 class="mt-5">City Wise Blood donated Details</h5>
            <canvas id="pieChart" class="p-4" width="1000"></canvas>
            <script>
              var pieXValues = <?php echo json_encode($cityNames); ?>;
              var pieYValues = <?php echo json_encode($cityValues); ?>;
              var barColors = randomColorArray(<?php echo count($cityValues); ?>);

              new Chart("pieChart", {
                type: "doughnut",
                data: {
                  labels: pieXValues,
                  datasets: [
                    {
                      backgroundColor: barColors,
                      borderColor: "rgba(0,0,0,0)",
                      data: pieYValues,
                    },
                  ],
                },
                options: {
                  title: { display: false },
                },
              });
            </script>
            <h5 class="mt-5">State Wise Blood donated Details</h5>
            <canvas id="stateChart" class="p-4" width="1000"></canvas>
            <script>
              var stateXValues = <?php echo json_encode($stateNames); ?>;
              var stateYValues = <?php echo json_encode($stateValues); ?>;
              var stateColors = randomColorArray(<?php echo count($stateValues); ?>);

              new Chart("stateChart", {
                type: "doughnut",
                data: {
                  labels: stateXValues,
                  datasets: [
                    {
                      backgroundColor: stateColors,
                      borderColor: "rgba(0,0,0,0)",
                      data: stateYValues,
                    },
                  ],
                },
                options: {
                  title: { display: false },
                },
              });
            </script>
            <h5 class="mt-5">Total Units of Blood donated in each Blood Group</h5>
            <canvas id="bloodChart" class="p-4" width="1000"></canvas>
            <script>
              var bloodXValues = <?php echo json_encode($bloodGroups); ?>;
              var bloodYValues = <?php echo json_encode($bloodValues); ?>;
              var bloodColors = randomColorArray(<?php echo count($bloodGroups); ?>);

              new Chart("bloodChart", {
                type: "doughnut",
                data: {
                  labels: bloodXValues,
                  datasets: [
                    {
                      backgroundColor: bloodColors,
                      borderColor: "rgba(0,0,0,0)",
                      data: bloodYValues,
                    },
                  ],
                },
                options: {
                  title: { display: false },
                },
              });
            </script>
            <div id="summaryButton" class="btn btn-danger m-3" onclick="toggleSummaryView()">Show Detailed Summary</div>
          </main>
        </div>
      </div>
      <div id="phpBox" style="display:none">
        <?php
          $servername = "localhost";
          $username = "root";
          $password = "solai2701";
          $dbname = "bloodbankdb";

          $conn = new mysqli($servername, $username, $password, $dbname);

          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $sql = "SELECT p.Patient_Name as Patient, p.Age as Age, p.Blood_Group as BG, p.Units as Units, h.City as District, ".
                "h.State_Union_Territory as State, h.Hospital_Name as Hospital, o.Officer_Name as Officer ".
                "FROM patient_details p join officer_details o on p.Officer_ID = o.Officer_ID ".
                "join hospital_details h on h.Hospital_ID = o.Hospital_ID";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            echo "<div class='p-5'><table class='table table-bordered table-dark'><tr><th>Patient Name</th><th>Age</th><th>Blood Group</th><th>No. of Units</th>".
                "<th>District</th><th>State</th><th>Blood Bank</th><th>Officer</th></tr>";
            while($row = $result->fetch_assoc()) {
              echo "<tr><td>".$row["Patient"]."</td><td>".$row["Age"]."</td><td>".$row["BG"]."</td><td>".$row["Units"].
                  "</td><td>".$row["District"]."</td><td>".$row["State"]."</td><td>".$row["Hospital"]."</td><td>".$row["Officer"]."</td></tr>";
            }
            echo "</table></div>";
          } else {
            echo "0 results";
          }
          $conn->close();
        ?>
      </div>
    </div>
  </body>
</html>
