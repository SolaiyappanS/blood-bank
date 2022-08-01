<? ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="cities.js"></script>
    <script src="backend.js"></script>
    <script>
	function statisticsPage() {
	location.href='statistics.php';
	}
    </script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <title>Blood Bank Management System</title>
  </head>
  <body onload="initialSetup();">
    <div class="d-flex h-100 text-center text-white bg-dark">
      <div class="cover-container d-flex p-3 mx-auto">
        <main class="px-3">
	<div id="summaryButton" class="btn btn-danger" onclick="statisticsPage()"
	style="position: fixed; top: 10%; right: 10%;">Summary</div>
          <h3 class="px-5 py-3 mt-5 border-3px border-danger rounded">
            Blood Bank Management System
          </h3>
          <h6 class="mb-5">
            Save lives by getting blood faster from Cities all over India
          </h6>
          <form method="post">
            <div class="mb-3">
              <label for="inputName" class="form-label"> Patient Name </label>
              <input type="text" class="form-control" id="inputName" name="inputName" required />
            </div>
            <div class="mb-3">
              <label for="inputAge" class="form-label"> Age </label>
              <input
                type="number"
                min="0"
                class="form-control"
                id="inputAge" name="inputAge"
                required
              />
            </div>
            <div class="mb-3">
              <label for="inputBloodGroup" class="form-label">
                Blood Group
              </label>
              <input
                class="form-control"
                list="bloodGroupOptions"
                id="inputBloodGroup" name="inputBloodGroup"
                placeholder="Enter Blood Group"
              />
              <datalist id="bloodGroupOptions">
                <option value="O+ve"></option>
                <option value="O-ve"></option>
                <option value="A+ve"></option>
                <option value="A-ve"></option>
                <option value="B+ve"></option>
                <option value="B-ve"></option>
                <option value="AB+ve"></option>
                <option value="AB-ve"></option>
                <option value="A1B+ve"></option>
                <option value="A1B-ve"></option>
              </datalist>
            </div>
            <div class="mb-3">
              <label for="inputNoOfUnits" class="form-label">
                No. of units needed
              </label>
              <input
                type="number"
                min="1"
                class="form-control"
                id="inputNoOfUnits" name="inputNoOfUnits"
                required
              />
            </div>
            <div class="mb-3">
              <label for="inputState" class="form-label"> State </label>
              <input
                class="form-control"
                list="stateOptions"
                id="inputState" name="inputState"
                onchange="selectCities()"
                placeholder="Enter State"
              />
              <datalist id="stateOptions"> </datalist>
            </div>
            <div class="mb-3">
              <label for="inputCity" class="form-label"> District </label>
              <input
                class="form-control"
                list="cityOptions"
                id="inputCity" name="inputCity"
                placeholder="Enter District"
              />
              <datalist id="cityOptions"> </datalist>
            </div>
            <div class="mb-3">
              <label for="inputContactNo" class="form-label">
                Contact Number
              </label>
              <input
                type="tel"
                class="form-control"
                id="inputContactNo" name="inputContactNo"
                required
              />
            </div>
            <button
              type="button"
              onclick="showResult()"
              class="btn btn-danger mb-5"
            >
              Submit
            </button>
            <div id="result">
              <div
                class="modal modal-sheet position-static d-block py-5"
                tabindex="-1"
                role="dialog"
                id="modalSheet"
              >
                <div class="modal-dialog" role="document">
                  <div class="modal-content rounded-3 shadow" style="background-color: #505050;">
                    <div class="modal-header border-bottom-0">
                      <h3 class="modal-title">Blood Bank Details</h3>
                      <button
                        type="button"
                        class="btn-close bg-light"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        onclick="hideResult()"
                      ></button>
                    </div>
                    <div class="modal-body pb-0 pt-3 mx-3">
                      <p id="details" style="font-size:17px"></p>
                    </div>
                    <div class="modal-footer flex-column border-top-0">
                      <button
                        type="submit"
                        class="btn btn-lg btn-danger w-100 mx-0 mb-2"
                      >
                        Confirm Booking
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </main>
      </div>
    </div>
    <div style="display:none">
    <?php
    function getBloodGroup($parameter) {
      switch ($parameter) {
        case 'O+ve':
          return 'O_Pos';
        case 'O-ve':
          return 'O_Neg';
        case 'A+ve':
          return 'A_Pos';
        case 'A-ve':
          return 'A_Neg';
        case 'B+ve':
          return 'B_Pos';
        case 'B-ve':
          return 'B_Neg';
        case 'AB+ve':
          return 'AB_Pos';
        case 'AB-ve':
          return 'AB_Neg';
        case 'A1B+ve':
          return 'A1B_Pos';
        case 'A1B-ve':
          return 'A1B_Neg';
      }
    }

    function alert($msg) {
        echo "<script type='text/javascript'>if(confirm('$msg' + '. Click OK to download details.'))".
	"download('blood_bank_details.txt','Blood Bank Management System:\\n' + '$msg');".
	"function download(filename, text) {".
	"var element = document.createElement('a');".
	"element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));".
	"element.setAttribute('download', filename);".
	"element.style.display = 'none';document.body.appendChild(element);".
	"element.click();document.body.removeChild(element);}</script>";
    }
      $servername = "localhost";
      $username = "root";
      $password = "solai2701";
      $dbname = "bloodbankdb";

      $patient_name = $_POST['inputName'];
      $age = $_POST['inputAge'];
      $blood_group = getBloodGroup($_POST['inputBloodGroup']);
      $no_of_units = $_POST['inputNoOfUnits'];
      $state = $_POST['inputState'];
      $city = $_POST['inputCity'];
      $contact_number = $_POST['inputContactNo'];

      $conn = new mysqli($servername, $username, $password, $dbname);
      
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      
      $sql = "select h.hospital_name as Hospital, h.contact_number as Contact, o.officer_name as Officer, o.officer_id as OfficerID, o.contact_number as Mobile, b.".
      $blood_group." as 'Available Units' from hospital_details h join officer_details o on h.hospital_id = o.hospital_id ".
      "join blood_details b on h.hospital_id = b.hospital_id where h.city = '".
      $city
      ."';";
      $officer_id = "A000";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          alert("The Application for ".$no_of_units." units of ".
		$_POST['inputBloodGroup']." blood needed for ".$patient_name.
		", Age ".$age." from ".$city.", ".$state." is received. ".
		$row['Officer']." from ".$row['Hospital'].
		" will contact you for further details. Contact number: ".$row['Contact']." / ".$row['Mobile']);
		$officer_id = $row['OfficerID'];
        }
      }
      $sql = "update blood_details b join hospital_details h on h.hospital_id = b.hospital_id set ".
	     $blood_group." = ".$blood_group." - ".$no_of_units." where h.city = '".$city."';";
      $result = $conn->query($sql);
      $sql = "select (count(*) + 1) as Count from patient_details;";
      $result = $conn->query($sql);
	$countP = "P0";
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
	    $countP = "P".$row['Count'];
        }
      }
      $sql = "insert into patient_details values('".$countP."','".$patient_name.
	"',".$age.",'".$_POST['inputBloodGroup']."',".$no_of_units.",'".$city."','".$officer_id."',".$contact_number.");";
      $result = $conn->query($sql);
      $conn->close();
    ?>
    </div>
  </body>
</html>
<? ob_flush(); ?>