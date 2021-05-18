<?php 
require_once(__DIR__ . "/../actions/discord_functions.php");
require_once(__DIR__ . "/../config.php");
session_start();
checkBan();
$user_discordid = $_SESSION['user_discordid'];
$_SESSION['redirect'] = "/fireems/fireemsDashboard.php";
$_SESSION['nameq'] = "";

if ($_SESSION['fireemsperms'] != 1)
{
	header('Location: ../index.php?notAuthorisedDepartment');
}

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$setdept = $pdo->query("UPDATE users SET currdept='FIREEMS' WHERE discordid='$user_discordid'");

$result = $pdo->query("SELECT * FROM users WHERE discordid='$user_discordid'");

$divisions = $pdo->query("SELECT * FROM divisions WHERE type='FIREEMS'");

foreach ($result as $row)
{
	$fireems_identifier = $row['identifier'];
	$fireems_currstatus = $row['currstatus'];
	$fireems_currpanic = $row['currpanic'];
	$fireems_currdivision = $row['currdivision'];
  $fireems_currapparatus = $row['currapparatus'];
	$fireems_notepad = $row['notepad'];
  $showsupervisor = $row['showsupervisor'];
}

// ACTION NOTIFICATIONS
if(isset($_GET['notActive']))
{
  $actionMessage = '<div class="alert alert-danger alert-dismissible fade show" style="text-align: center;" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> You cannot do this as you are currently 10-7!</div>';
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo SERVER_SHORT_NAME; ?> CAD | FIRE & EMS</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/vendors/quill/quill.snow.css">
    <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
  </head>
  <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/timer.js"></script>
  <script src="../assets/js/sweetalert.js"></script>
  <body id="modalCheck">
    <div class="container-scroller">
      <div class="horizontal-menu">
        <!-- HEADER -->
        <?php include "../includes/header.inc.php"; ?>
        <!-- NAVBAR -->
        <?php include "../includes/navbar.inc.php"; ?>
      </div>

      <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
          <div class="content-wrapper">
        <!-- ACTION DISPLAY -->
        <?php if($actionMessage){echo $actionMessage;} ?>
          	<div class="row">
				  <div class="col-xl-9">
	                <div class="card">
                  <script>
                    fetch('../actions/getzulu.php').then(function (resp) {
                        resp.text().then(function (text) {
                      $('#getzulu').html(text);
                        });
                    });
                    var auto_refresh = setInterval( function () {
                        fetch('../actions/getzulu.php').then(function (resp) {
                            resp.text().then(function (text) {
                          $('#getzulu').html(text);
                            });
                        });
                    }, 1000);
                  </script>
                  <div style="font-family: 'Orbitron'; font-size: 30px; z-index: 2; position: absolute; padding: 30px 0px 0px 50px;" class="text-muted" id="getzulu"></div>
	                  <div class="card-body text-center" style="padding-top: 55px; padding-bottom: 66px;">
	                    <h3 class="mb-2 text-center">FIRE & EMS Control Panel</h3><br>
                    		<h5 class="text-center">Identifier: <span class="text-muted"><?php echo $fireems_identifier; ?> <?php if ($_SESSION['supervisor'] == 1 & $showsupervisor == 1) {echo "(Supervisor)";} ?></span></h5><br>
                    		<h5 class="text-center">Status: <span class="text-muted" id="changestatus"><?php echo $fireems_currstatus; ?></span></h5>
                    		<?php
                    			if ($fireems_currdivision != "None")
                    			{
                    		?>
                    		<br><h5 class="text-center">Subdivision: <span class="text-muted"><?php echo $fireems_currdivision; ?></span></h5>
                    		<?php
                    			}

                          if ($fireems_currapparatus != "None")
                          {
                        ?>
                        <br><h5 class="text-center">Apparatus: <span class="text-muted"><?php echo $fireems_currapparatus; ?></span></h5>
                        <?php
                          }
                        ?>
								          <div id="getpanic">
	                          <script type="text/javascript">
	                          	$('#getpanic').load('../actions/getpanic.php');
								          		var auto_refresh = setInterval( function () {
								          			$('#getpanic').load('../actions/getpanic.php');
								          		}, 5000);
								          	</script>
                    			</div>
                          <div id="getsignal">
                            <script type="text/javascript">
                              $('#getsignal').load('../actions/getsignal.php');
                              var auto_refresh = setInterval( function () {
                                $('#getsignal').load('../actions/getsignal.php');
                              }, 5000);
                            </script>             
                          </div>
                          <div id="getfiretone">
                            <script type="text/javascript">
                              $('#getfiretone').load('../actions/getfiretone.php');
                              var auto_refresh = setInterval( function () {
                                $('#getfiretone').load('../actions/getfiretone.php');
                              }, 5000);
                            </script>          
                          </div>
                          <div id="getping">
                              <script type="text/javascript">
                                $('#getping').load('../actions/getping.php');
                                var auto_refresh = setInterval( function () {
                                  $('#getping').load('../actions/getping.php');
                                }, 5000);
                              </script>             
                          </div>
                    		<br>
                        <button type="button" onclick="changeStatus('10-8')" class="btn btn-outline-success btn-rounded btn-fw p-3 m-2">10-8 | In Service</button>
                        <button type="button" onclick="changeStatus('10-7')" class="btn btn-outline-danger btn-rounded btn-fw p-3 m-2">10-7 | Out of Service</button>
                        <?php 
                          if ($fireems_currpanic == 0)
                          {
                            echo '<p data-toggle="modal" data-target="#panicModal" class="btn btn-outline-danger btn-rounded btn-fw p-3 m-2">Panic Button</p>';
                          }
                          else
                          {
                        ?>
                            <button type="button" onclick="disablePanic()" class="btn btn-outline-danger btn-rounded btn-fw p-3 m-2">Disable Panic</button>
                        <?php
                          }
                        ?>
		                    <br>
                        <button type="button" onclick="changeStatus('10-6')" class="btn btn-outline-info btn-rounded btn-fw p-3 m-2">10-6 | Busy</button>
                        <button type="button" onclick="changeStatus('10-15')" class="btn btn-outline-info btn-rounded btn-fw p-3 m-2">10-15 | En-Route to Station</button>
                        <button type="button" onclick="changeStatus('10-23')" class="btn btn-outline-info btn-rounded btn-fw p-3 m-2">10-23 | Arrived on Scene</button>
                        <button type="button" onclick="changeStatus('10-97')" class="btn btn-outline-info btn-rounded btn-fw p-3 m-2">10-97 | In Route</button>
                        <button type="button" onclick="changeStatus('10-99')" class="btn btn-outline-info btn-rounded btn-fw p-3 m-2">10-99 | In Distress</button>
		                    <br>
                        <p data-toggle="modal" data-target="#Code10" class="btn btn-outline-warning btn-rounded btn-fw p-3 m-2">10 Codes</p>
                        <?php
                        if (PENAL_CODE_LINK != "#")
                        {
                        ?>
                        <a href="<?php echo PENAL_CODE_LINK; ?>" target="_blank" class="btn btn-outline-warning btn-rounded btn-fw p-3 m-2">Penal Code</a>
                        <?php 
                        }
                        ?>
		                    <p data-toggle="modal" data-target="#noteModal" class="btn btn-outline-warning btn-rounded btn-fw p-3 m-2">Notepad</p>
		                    <p data-toggle="modal" data-target="#subdivisionModal" class="btn btn-outline-warning btn-rounded btn-fw p-3 m-2">Select Subdivision</p>
                        <p data-toggle="modal" data-target="#apparatusModal" class="btn btn-outline-warning btn-rounded btn-fw p-3 m-2">Select Apparatus</p>
	                  </div>
	                </div>
	            </div>
            <div class="col-xl-3">
                  <div class="card">
                    <div class="card-body text-center">
                      <h3 class="mt-3 text-center">Livechat</h3><br>
                        <link rel="stylesheet" href="../assets/css/livechat.css">
                  <div id="wrapper">
                      <div id="chatbox" style="margin-left: 20px;">
                      <?php
                      if(file_exists("../actions/log.html") && filesize("../actions/log.html") > 0){
                          $contents = file_get_contents("../actions/log.html");          
                          echo $contents;
                      }
                      ?>
                      </div>
                      <form action="">
                          <input placeholder="Start typing..." name="usermsg" class="livechat-input p-input" type="text" id="usermsg" style="width: 20em;" />
                          <input name="submitmsg" type="submit" id="submitmsg" value="Send" class="btn btn-outline-info"/>
                          <br><br>
                      </form>
                  </div>
                  <script type="text/javascript">
                      $(document).ready(function () {
                          $("#submitmsg").click(function () {
                              var clientmsg = $("#usermsg").val();
                              $.post("../actions/sendchat.php", { text: clientmsg });
                              $("#usermsg").val("");
                              return false;
                          });
           
                          function loadLog() {
                              var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                              $.ajax({
                                  url: "../actions/log.html",
                                  cache: false,
                                  success: function (html) {
                                      $("#chatbox").html(html);
                                             
                                      var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                                      if(newscrollHeight > oldscrollHeight){
                                          $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
                                      }   
                                  }
                              });
                          }
                          setInterval (loadLog, 1000);
                      });
                  </script>
                    </div>
                  </div>
              </div>
          	</div>
          	<br>
          	<br>
            <div class="row">
              <div class="col-xl-6">
                  <div class="card">
                    <div class="card-body text-center" style="margin-bottom: 12px;">
                      <h3 class="mt-2 mb-2 text-center">Your Calls</h3><br>
                      <div id="getcalls">
                        <script type="text/javascript">
                          var modalChecking;
                          $('#getcalls').load('../actions/getcalls.php');
                          var auto_refresh = setInterval( function () {
                            modalChecking = $('#modalCheck').hasClass('modal-open');
                            if (modalChecking == false)
                            {
                              $('#getcalls').load('../actions/getcalls.php');
                            }
                          }, 3000);
                        </script>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="col-xl-6">
                  <div class="card" id="searched">
                    <div class="card-body text-center">
                      <h3 class="mt-2 mb-1 text-center">Name Check</h3><br>
                      <form class="forms-sample" autocomplete="off" enctype="multipart/form-data">
                          <div class="form-group">
                              <input type="text" class="form-control p-input" placeholder="Search Name or SSN" onkeyup="showResult(this.value, 'NAME')" style="color: white;">
                          </div>
                          <div id="namesearch"></div>
                      </form>
                    </div>
                  </div>
                  <br>
                  <?php
                    if (isset($_GET['nameq']))
                    {
                        $nameq = htmlspecialchars($_GET['nameq']);
                        $_SESSION['nameq'] = $nameq;
                        $character = $pdo->query("SELECT * FROM characters WHERE ID='$nameq'");

                        foreach ($character as $row)
                        {
                          $character_id = $row['ID'];
                          $character_name = $row['name'];
                          $character_dob = $row['dob'];
                          $character_ssn = $row['ssn'];
                          $character_haircolor = $row['haircolor'];
                          $character_address = $row['address'];
                          $character_gender = $row['gender'];
                          $character_race = $row['race'];
                          $character_build = $row['build'];
                          $character_occupation = $row['occupation'];
                          $character_image = $row['image'];
                          $dead = $row['dead'];
                          if ($dead == 1)
                          {
                            $deadstatus = "Dead";
                            $deadcolor = "red";
                          } else {
                            $deadstatus = "Alive";
                            $deadcolor = "green";
                          }

                          $character_ssn = $row['ssn'];
                        
                          $bloodtype = $row['bloodtype'];
                          $emergcontact = $row['emergcontact'];
                          $allergies = $row['allergies'];
                          $medication = $row['medication'];
                          $organdonor = $row['organdonor'];

                        }

                        $medicalrecords = $pdo->query("SELECT * FROM medicalrecords WHERE civid='$character_id' ORDER BY ID DESC");

                  ?>
                  <div class="card">
                    <div class="card-body">
                      <div style="font-size: 20px; text-align: right;">
                        <a style="text-decoration: none; color: white;" href="fireemsDashboard.php"><span>&times;</span></a>
                      </div><br>
                      <div class="text-center">
                        <img src="<?php echo $character_image; ?>" alt="Image" style="border-radius: 100px; width: 20%;">
                      </div>
                      <h5>Name: <span class="text-muted"><?php echo $character_name; ?></span></h5>
                      <h5>DOB: <span class="text-muted"><?php echo $character_dob; ?></span></h5>
                      <h5>SSN: <span class="text-muted"><?php echo $character_ssn; ?></span></h5>
                      <h5>Hair Color: <span class="text-muted"><?php echo $character_haircolor; ?></span></h5>
                      <h5>Address: <span class="text-muted"><?php echo $character_address; ?></span></h5>
                      <h5>Gender: <span class="text-muted"><?php echo $character_gender; ?></span></h5>
                      <h5>Race: <span class="text-muted"><?php echo $character_race; ?></span></h5>
                      <h5>Build: <span class="text-muted"><?php echo $character_build; ?></span></h5>
                      <h5>Occupation: <span class="text-muted"><?php echo $character_occupation; ?></span></h5>
                      <h5 style="color: <?php echo $deadcolor; ?>;"><?php echo $deadstatus; ?></h5>
                      <br>
                      <h5>Medical Details:</h5>
                      <span>Blood Type: <span class="text-muted"><?php echo $bloodtype; ?></span></span><br>
                      <span>Emergency Contact: <span class="text-muted"><?php echo $emergcontact; ?></span></span><br>
                      <span>Allergies: <span class="text-muted"><?php echo $allergies; ?></span></span><br>
                      <span>Medication: <span class="text-muted"><?php echo $medication; ?></span></span><br>
                      <?php
                      if ($organdonor == "1")
                      {
                      ?>
                      <h5 style="color: #f55f82;">DONOR</h5>
                      <?php
                      }
                      ?>
                      <br>
                      <h5>Medical Record: <p data-toggle="modal" data-target="#addMedicalModal" class="btn btn-outline-info m-2">Add</p><span class="text-success pl-3" id="medicalsuccess"></span></h5>
                      <span class="text-muted">=====================================</span><br>
                      <?php
                      foreach ($medicalrecords as $row)
                      {
                        $medicalrecord_unit_identifier = $row['unitidentifier'];
                        $medicalrecord_date = $row['date'];
                        $medicalrecord_time = $row['time'];
                        $medicalrecord_details = $row['details'];
                              ?>
                      <div class="row">
                      <div class="col-md-6">
                      <span class="text-muted"><b>Details:</b> <?php echo $medicalrecord_details; ?></span><br>
                      <span class="text-muted"><b>Date & Time:</b> <?php echo $medicalrecord_date . " | " . $medicalrecord_time; ?></span><br>
                      <span class="text-muted"><b>Signing Unit:</b> <?php echo $medicalrecord_unit_identifier; ?></span><br>
                      </div>
                      <div class="col-md-6" style="top: 15px;">
                      <button type="button" onclick="deleteMedical(<?php echo $row['ID']; ?>)" class="btn btn-outline-danger">Delete</button><br>
                      </div>
                      </div>
                      <span class="text-muted">=====================================</span><br>
                      <?php
                      }
                      if ($medicalrecord_unit_identifier == "")
                      {
                        echo '<span class="text-muted">None</span><br><span class="text-muted">=====================================</span><br>';
                      }
                      ?>
                    </div>
                  </div>

<!-- Medical Modal -->
<div class="modal fade" id="addMedicalModal" tabindex="-1" role="dialog" aria-labelledby="addMedicalModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMedicalModal">Add Medical Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="forms-sample" action="../actions/department_functions.php" method="POST">
      <div class="form-group">
        <label for="medical_name">Subjects Name</label>
        <input type="text" class="form-control p-input" id="medical_name" name="medical_name" value="<?php echo $character_name; ?>" readonly>
        <input type="text" class="form-control p-input" id="medical_id" name="medical_id" value="<?php echo $character_id; ?>" hidden>
      </div>
      <div class="form-group">
        <label>Subjects DOB</label>
        <input type="text" class="form-control p-input" value="<?php echo $character_dob; ?>" readonly>
      </div>
      <div class="form-group">
        <label>Subjects Address</label>
        <input type="text" class="form-control p-input" value="<?php echo $character_address; ?>" readonly>
      </div>
      <div class="form-group">
        <label for="medical_details">Details</label>
        <textarea type="text" class="form-control p-input" id="medical_details" name="medical_details" required></textarea>
      </div>
      <div class="form-group">
        <label>Date & Time</label>
        <input type="text" class="form-control p-input" value="<?php echo date('Y-m-d | H:i:s'); ?>" readonly>
      </div>
      <div class="form-group">
        <label>Signing Off</label>
        <input type="text" class="form-control p-input" value="<?php echo $fireems_identifier; ?>" readonly>
            </div>
            <button type="submit" name="add_medical_record_btn" class="btn btn-outline-info">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

          <?php
            }
          ?>
              </div>
              </div>
            </div>
            <br>
            <br>
          <!-- FOOTER -->
          <?php include "../includes/footer.inc.php"; ?>
          </div>
        </div>
      </div>
    </div>

<!-- Subdivision Modal -->
<div class="modal fade" id="subdivisionModal" tabindex="-1" role="dialog" aria-labelledby="subdivisionModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subdivisionModal">Subdivision</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="forms-sample" action="../actions/department_functions.php" method="POST">
            <div class="form-group">
                <label for="update_subdivision">Update Subdivision</label>
	            <select class="form-control p-input" id="update_subdivision" name="update_subdivision" style="color: white;" required>
	            	<option value="None">None</option>
                <?php
                  foreach ($divisions as $row)
                  {
                ?>
	            	  <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
	            	<?php
                  }
                ?>
	            </select>
            </div>
            <button type="submit" name="update_subdivision_btn" class="btn btn-outline-info">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Apparatus Modal -->
<div class="modal fade" id="apparatusModal" tabindex="-1" role="dialog" aria-labelledby="apparatusModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="apparatusModal">Apparatus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="forms-sample" action="../actions/department_functions.php" method="POST">
            <div class="form-group">
                <label for="update_apparatus">Update Apparatus</label>
              <select class="form-control p-input" id="update_apparatus" name="update_apparatus" style="color: white;" required>
                <option value="None">None</option>
                <?php
                  foreach ($FIREEMS_APPARATUS as $name)
                  {
                ?>
                  <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                <?php
                  }
                ?>
              </select>
            </div>
            <button type="submit" name="update_apparatus_btn" class="btn btn-outline-info">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Panic Modal -->
<div class="modal fade" id="panicModal" tabindex="-1" role="dialog" aria-labelledby="panicModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="panicModal">Press Panic Button</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <span id="panicsuccess"></span>
            <div class="form-group">
                <label>Your Location</label>
                <input type="text" class="form-control p-input" id="panic_location">
            </div>
            <button type="submit" onclick="startPanic()" class="btn btn-outline-danger">Press</button>
      </div>
    </div>
  </div>
</div>

<!-- 10 Codes Modal -->
<div class="modal fade" id="Code10" tabindex="-1" role="dialog" aria-labelledby="Code10" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="margin-top: 50px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Code10">10 Codes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px;">
          <?php
              foreach ($CODES10 as $code => $color) {
                echo '<div style="color: '.$color.'">'.$code.'</div>';
              }
          ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noteModal">Your Notepad</h5><span class="text-success pl-3" id="notesuccess"></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="forms-sample" action="../actions/department_functions.php" method="POST">
            <div class="form-group">
              <div class="row grid-margin">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <div style="color: white !important;" id="quillExample1" class="quill-container">
                        <?php echo $fireems_notepad; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="text" id="note_content" name="note_content" hidden>
            <div>
              <button type="submit" name="update_notepad_btn" class="btn btn-outline-info btn-fw">Close</button>
          </div>
        </form>
        <div class="pt-2">
            <button type="submit" onclick="saveNote()" class="btn btn-outline-info btn-fw">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>

  <script>
    function showResult(str, type) {
    if (str.length==0) {
      if (type == 'NAME')
      {
        document.getElementById("namesearch").innerHTML="";
      }
      return;
    }
    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
    } else {  // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
      if (this.readyState == 4 && this.status == 200) {
        if (type == 'NAME')
        {
          document.getElementById("namesearch").innerHTML = this.responseText;
        }
      }
    }

    if (type == 'NAME')
    {
      xmlhttp.open("GET", "../actions/department_functions.php?nc=" + str, true);
      xmlhttp.send();
    }

    }

    function saveNote () {
      var myEditor = document.querySelector('#quillExample1');
      var html = myEditor.children[0].innerHTML;
      document.getElementById("note_content").value = html;
      document.getElementById("notesuccess").innerHTML = "SUCCESS";
    }

    function changeStatus(status) {
      console.log(status);
      $('#changestatus').load('../actions/department_functions.php?fireemsstatus='+status);
    }

    function deleteMedical(id) {
      $('#medicalsuccess').load('../actions/department_functions.php?deletemedical='+id);
      setTimeout(function(){location.reload()}, 100);
    }

    function startPanic() {
      var panic_location = document.getElementById("panic_location");
      var paniclocation = panic_location.value;
      paniclocationnospace = paniclocation.replace(/\s+/g, '-');
      $('#panicsuccess').load('../actions/department_functions.php?panic=1&paniclocation='+paniclocationnospace);
      sleep(2);
      location.reload();
    }

    function disablePanic() {
      var paniclocation = "";
      $('#panicsuccess').load('../actions/department_functions.php?panic=0&paniclocation='+paniclocation);
      location.reload();
    } 

    var dutyTime = localStorage.getItem("dutyTimeMinutes");
    if (dutyTime != "0")
    {
      if (dutyTime != "1")
      {
        restart3();
      }
    }

  </script>

    <!-- JS -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="../assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="../assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/vendors/quill/quill.min.js"></script>
    <script src="../assets/js/editorDemo.js"></script>
  </body>
</html>