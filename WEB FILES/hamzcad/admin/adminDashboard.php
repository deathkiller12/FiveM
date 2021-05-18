<?php 
require_once(__DIR__ . "/../actions/discord_functions.php");
require_once(__DIR__ . "/../config.php");
session_start();

$user_discordid = $_SESSION['user_discordid'];

if ($_SESSION['adminperms'] != 1)
{
	header('Location: ../index.php?notAuthorisedDepartment');
}

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$resetstatus = $pdo->query("UPDATE users SET currstatus='10-7', currdivision='None', currdept='None' WHERE discordid='$user_discordid'");

$result = $pdo->query("SELECT * FROM permissions");

$result2 = $pdo->query("SELECT * FROM divisions");

$result3 = $pdo->query("SELECT * FROM bans");

$result4 = $pdo->query("SELECT * FROM gallery");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo SERVER_SHORT_NAME; ?> CAD | Admin</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
  </head>
  <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
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
				<div class="col-xl-6">
	                <div class="card" id="searched">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Add Permissions</h3><br>
	                    	<div class="pb-3"><span class="text-success" id="permissionsuccess"></span></div>
								<div class="form-group">
									<label>Role ID</label>
									<input type="text" class="form-control p-input" id="role_id" style="color: white;" required>
								</div>
								<div class="form-group">
									<label>Permission Type</label>
						            <select class="form-control p-input" id="permission_type"style="color: white;" required>
						            	<option value="Civilian">Civilian</option>
						            	<option value="LEO">LEO</option>
						            	<option value="FIREEMS">FIREEMS</option>
						            	<option value="DISPATCH">DISPATCH</option>
						            	<option value="COURT">COURT</option>
						            	<option value="SUPERVISOR">SUPERVISOR</option>
						            	<option value="DMV">DMV</option>
						            	<option value="DOT">DOT</option>
						            </select>
								</div>
					            <button type="submit" onclick="addPermission()" class="btn btn-outline-info">Add</button>
	                  </div>
	                </div>
	            </div>
	            <div class="col-xl-6">
	                <div class="card" id="searched">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Current Permissions</h3><br>
	                    <div class="pb-3"><span class="text-success" id="permissiondeletesuccess"></span></div>
		                    <div class="table-responsive">
		                      <table class="table">
		                        <thead>
		                          <tr>
		                            <th>Role ID</th>
		                            <th>Permission</th>
		                            <th></th>
		                          </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        	foreach ($result as $row)
		                        	{
		                        ?>
		                          <tr>
		                            <td><?php echo $row['roleid']; ?></td>
		                            <td><?php echo $row['permission']; ?></td>
		                            <td><button type="submit" onclick="deletePermission(<?php echo $row['ID']; ?>)" class="btn btn-outline-danger">Delete</button></td>
		                          </tr>
		                        <?php
		                        	}
		                        ?>
		                        </tbody>
		                      </table>
		                    </div>
	                  </div>
	                </div>
	            </div>
	        </div>
	        <br>
	        <br>
          	<div class="row">
				<div class="col-xl-6">
	                <div class="card" id="searched">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Add Subdivisions</h3><br>
	                    	<div class="pb-3"><span class="text-success" id="divisionsuccess"></span></div>
								<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control p-input" id="division_name" style="color: white;" required>
								</div>
								<div class="form-group">
									<label>For Department</label>
						            <select class="form-control p-input" id="division_type"style="color: white;" required>
						            	<option value="LEO">LEO</option>
						            	<option value="FIREEMS">FIREEMS</option>
						            </select>
								</div>
					            <button type="submit" onclick="addSubdivision()" class="btn btn-outline-info">Add</button>
	                  </div>
	                </div>
	            </div>
	            <div class="col-xl-6">
	                <div class="card" id="searched">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Current Subdivisions</h3><br>
	                    <div class="pb-3"><span class="text-success" id="divisiondeletesuccess"></span></div>
		                    <div class="table-responsive">
		                      <table class="table">
		                        <thead>
		                          <tr>
		                            <th>Name</th>
		                            <th>Type</th>
		                            <th></th>
		                          </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        	foreach ($result2 as $row)
		                        	{
		                        ?>
		                          <tr>
		                            <td><?php echo $row['name']; ?></td>
		                            <td><?php echo $row['type']; ?></td>
		                            <td><button type="submit" onclick="deleteDivision(<?php echo $row['ID']; ?>)" class="btn btn-outline-danger">Delete</button></td>
		                          </tr>
		                        <?php
		                        	}
		                        ?>
		                        </tbody>
		                      </table>
		                    </div>
	                  </div>
	                </div>
	            </div>
	        </div>
	        <br>
	        <br>
          	<div class="row">
				<div class="col-xl-6">
	                <div class="card" id="searched">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Ban Users</h3><br>
	                    	<div class="pb-3"><span class="text-success" id="divisionsuccess"></span></div>
	                    	<form class="forms-sample" action="../actions/department_functions.php"  method="post">
								<div class="form-group">
									<label for="ban_discordid">Discord ID</label>
									<input type="text" class="form-control p-input" name="ban_discordid" style="color: white;" required>
								</div>
								<div class="form-group">
									<label for="ban_name">Name Known By</label>
									<input type="text" class="form-control p-input" name="ban_name" style="color: white;" required>
								</div>
					            <button type="submit" name="ban_user_btn" class="btn btn-outline-info">Add</button>
					        </form>
	                  </div>
	                </div>
	            </div>
	            <div class="col-xl-6">
	                <div class="card" id="searched">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Current Banned Users</h3><br>
	                    <div class="pb-3"><span class="text-success" id="bandeletesuccess"></span></div>
		                    <div class="table-responsive">
		                      <table class="table">
		                        <thead>
		                          <tr>
		                            <th>Name</th>
		                            <th>Discord ID</th>
		                            <th></th>
		                          </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        	foreach ($result3 as $row)
		                        	{
		                        ?>
		                          <tr>
		                            <td><?php echo $row['name']; ?></td>
		                            <td><?php echo $row['discordid']; ?></td>
		                            <td><button type="submit" onclick="deleteBan(<?php echo $row['ID']; ?>)" class="btn btn-outline-danger">Delete</button></td>
		                          </tr>
		                        <?php
		                        	}
		                        ?>
		                        </tbody>
		                      </table>
		                    </div>
	                  </div>
	                </div>
	            </div>
	        </div>
	        <br>
	        <br>
	        <?php
	        if (GALLERY == 1)
	        {
	        ?>
          	<div class="row">
				<div class="col-xl-6">
	                <div class="card" id="gallery">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Add Gallery Image</h3><br>
	                    	<div class="pb-3"><span class="text-success" id="divisionsuccess"></span></div>
	                    	<form class="forms-sample" action="../actions/department_functions.php"  method="post">
								<div class="form-group">
									<label for="gallery_link">Link</label>
									<input type="text" class="form-control p-input" name="gallery_link" style="color: white;" placeholder="Imgur/Discord Link" required>
								</div>
					            <button type="submit" name="add_gallery_image" class="btn btn-outline-info">Add</button>
					        </form>
	                  </div>
	                </div>
	            </div>
	            <div class="col-xl-6">
	                <div class="card">
	                  <div class="card-body text-center">
	                    <h3 class="mt-2 mb-0 text-center">Current Gallery Images</h3><br>
	                    <div class="pb-3"><span class="text-success" id="imagedeletesuccess"></span></div>
		                    <div class="table-responsive">
		                      <table class="table">
		                        <thead>
		                          <tr>
		                            <th>Link</th>
		                            <th>Image</th>
		                            <th></th>
		                          </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        	foreach ($result4 as $row)
		                        	{
		                        ?>
		                          <tr>
		                            <td><a href="<?php echo $row['link']; ?>" target="_blank">Link</a></td>
		                            <td><img src="<?php echo $row['link']; ?>" alt="Gallery"></td>
		                            <td><button type="submit" onclick="deleteImage(<?php echo $row['ID']; ?>)" class="btn btn-outline-danger">Delete</button></td>
		                          </tr>
		                        <?php
		                        	}
		                        ?>
		                        </tbody>
		                      </table>
		                    </div>
	                  </div>
	                </div>
	            </div>
	        </div>

	        <br>
	        <br>
	        <?php
	    	}
	        ?>
          <!-- FOOTER -->
          <?php include "../includes/footer.inc.php"; ?>
          </div>
        </div>
      </div>
    </div>

	<script>
		function addPermission() {
			var role_id = document.getElementById("role_id");
			var roleid = role_id.value;
			var permission_type = document.getElementById("permission_type");
			var permissiontype = permission_type.value;
			if (roleid != "") { 
			$('#permissionsuccess').load('../actions/department_functions.php?roleid='+roleid+'&permissiontype='+permissiontype);
			setTimeout(function(){location.reload()}, 100);
			}
		}

		function deletePermission(id) {
			$('#permissiondeletesuccess').load('../actions/department_functions.php?deletepermission='+id);
			setTimeout(function(){location.reload()}, 100);
		}

		function addSubdivision() {
			var division_name = document.getElementById("division_name");
			var divisionname = division_name.value;
			divisionnamenospace = divisionname.replace(/\s+/g, '-');
			var division_type = document.getElementById("division_type");
			var divisiontype = division_type.value;
			if (divisionname != "") { 
			$('#divisionsuccess').load('../actions/department_functions.php?divisionname='+divisionnamenospace+'&divisiontype='+divisiontype);
			location.reload();
			  }
		}

		function deleteDivision(id) {
			$('#divisiondeletesuccess').load('../actions/department_functions.php?deletedivision='+id);
			setInterval(function(){ location.reload(); }, 200);
		}	

		function deleteBan(id) {
			$('#bandeletesuccess').load('../actions/department_functions.php?deleteban='+id);
			setInterval(function(){ location.reload(); }, 200);
		}	

		function deleteImage(id) {
			$('#imagedeletesuccess').load('../actions/department_functions.php?deleteimage='+id);
			setInterval(function(){ location.reload(); }, 200);
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
  </body>
</html>