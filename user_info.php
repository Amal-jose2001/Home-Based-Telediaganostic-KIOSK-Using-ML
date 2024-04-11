<?php include("header.php");?>

		<!-- Start Breadcrumbs -->
		<section id="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2>User Info</h2>
						<ul>
							<li><a href="index1.php">Home</a></li>
							<li class="active"><a href="blog-single.html">User Info</a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!--/ End Breadcrumbs -->
		
				<section id="blog" class="section page">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="entry-blog">
							<!-- Single Blog -->
							<div class="single-blog">
								
								<div class="comments-form">
									<div class="row">
										<h2>User Information</h2>
										<!-- Comments Form -->
										<?php
										include('connection.php');

										$sql = "SELECT * FROM user_info WHERE user_id = '".$_SESSION['uid']."'";
										$result = mysqli_query($con, $sql);
										$row = mysqli_fetch_assoc($result);

										if(mysqli_num_rows($result) > 0) {
											$submitBtnText = "Update"; 
										} else {
											$submitBtnText = "Submit"; 
										}

										?>

										<form class="form" method="POST" enctype="multipart/form-data">
											<div class="col-md-3">
												<div class="form-group">
													<label for="imageUpload" class="custom-file-upload">
														<img id="previewImage" src="<?php echo isset($row['image']) ? 'images/users/'.$row['image'] : 'images/users/profile.jpg'; ?>" alt="Dummy Image">
														<input type="file" name="file" id="imageUpload" accept="image/*">
													</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>User Name</label>
													<input type="text" name="name" placeholder="Full Name" value="<?php echo $_SESSION['uname']; ?>" required="required" readonly>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Age</label>
													<input type="text" name="age" placeholder="Age" value="<?php echo isset($row['age']) ? $row['age'] : ''; ?>" required="required">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Gender</label>
													<select name="gender" class="form-control">
														<option <?php echo isset($row['gender']) && $row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
														<option <?php echo isset($row['gender']) && $row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Blood Group</label>
													<input type="text" name="blood" placeholder="Blood Group" value="<?php echo isset($row['blood_group']) ? $row['blood_group'] : ''; ?>" required="required">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label>Problems / Medical History</label>
													<textarea rows="6" placeholder="Problems / Medical History" name="history"><?php echo isset($row['history']) ? $row['history'] : ''; ?></textarea>
												</div>
											</div>

											<div class="col-md-12">
												<div class="form-group submit">
													<button class="button" name="submit" type="submit"><?php echo $submitBtnText; ?></button>
												</div>
											</div>
										</form>

										<script>
											document.getElementById("imageUpload").addEventListener("change", function() {
												var preview = document.getElementById("previewImage");
												var file = this.files[0];
												var reader = new FileReader();

												reader.onloadend = function() {
													preview.src = reader.result;
												}

												if (file) {
													reader.readAsDataURL(file);
												} else {
													preview.src = "";
												}
											});
										</script>

										<!--/ End Comments Form -->
									</div>
								</div>
							</div>
							<!--/ End Single Blog -->
						</div>
					</div>
				</div>
				<div class="map"></div>
				<div id="particles-js"></div>
			</div>
		</section>
		<!-- End Blog -->	
<?php
include('connection.php');

if(isset($_POST['submit'])) {
    $name = $_SESSION['uid'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood = $_POST['blood'];
    $history = $_POST['history'];
    $img = ''; 

    if(isset($_FILES['file'])) {
        $target_path = "images/users/" . basename($_FILES['file']['name']);

        // Move uploaded file to target path
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
            
            $img = basename($_FILES['file']['name']);
        } else {
            echo "File upload failed!";
        }
    }

    // Check if data exists for the current user
    $sql = "SELECT * FROM user_info WHERE user_id = '$name'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0) {
        
        $updateSql = "UPDATE user_info SET age='$age', gender='$gender', blood_group='$blood', history='$history'";
        // Append image update if an image was uploaded
        if(!empty($img)) {
            $updateSql .= ", image='$img'";
        }
        $updateSql .= " WHERE user_id='$name'";
        
        $updateRes = mysqli_query($con, $updateSql);
        if($updateRes) {
            echo '<script>alert("Data updated!"); 
						  window.location="user_info.php";
				  </script>';
        } else {
            echo '<script>
						alert("Error updating data!");
				  </script>';
        }
    } else {
        $insertSql = "INSERT INTO user_info (user_id, age, gender, blood_group, history, image) VALUES ('$name','$age','$gender','$blood','$history','$img')";
        $insertRes = mysqli_query($con, $insertSql);
        if($insertRes) {
            echo '<script>alert("Data inserted!"); window.location="user_info.php";</script>';
        } else {
            echo '<script>alert("Error inserting data!");</script>';
        }
    }
}
?>

	
		

				
		
		<?php include("footer.php");?>
	