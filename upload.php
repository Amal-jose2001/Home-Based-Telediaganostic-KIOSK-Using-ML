<?php 
include("header.php");
include('connection.php');	
?>


		<!-- Start Breadcrumbs -->
		<section id="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2>Pneumonia Detection</h2>
						<ul>
							<li><a href="index1.php">Home</a></li>
							<li class="active"><a href="checkup.php">Health Checkup</a></li>
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
										<h2>Upload Image</h2>
										<!-- Comments Form -->
									
										<form class="form" method="POST" enctype="multipart/form-data">
											<div class="col-md-12">
													<label for="imageUpload" class="custom-file-upload">
														<img id="previewImage" >
														<input type="file" name="uploadedfile" id="imageUpload" accept="image/*">
													</label>
												
											</div>
									

											<div class="col-md-12">
												<div class="form-group submit">
													<button class="button" name="submit" type="submit">Predict</button>
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
										
										
										<?php
										if(isset($_POST['submit']))
										{
											$target_path = "test/test/";

											$target_path = $target_path . 'test.png'; 

											if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
												
												
												 $python1 = "C:\\ProgramData\\Anaconda3\\python.exe";
											     $file = "C:\\xampp\\htdocs\\Medical\\test.py";
											     $python=exec($python1 . " " . $file);
												 
												 $date=date("Y-m-d H:i:s"); 
											
												   echo "<div class='col-lg-12 col-md-12 well' style='background-color:#aad6ff' >
															<center><h1>OUTPUT</h1><hr></center>
														 <pre><h3>".$python."</h3></pre>";
														 
														 if($python=='PNEUMONIA')
														 {
															 echo "<a href='viewdoctor.php' class='btn btn-danger'>Consult Doctor</a>";
														 }
														 
												   echo  "</div>";		 
														 
												 $ins=mysqli_query($con,"INSERT INTO `result`(`result`, `user_id`, `date`) VALUES ('$python','$_SESSION[uid]','$date')");
												
												
											} else{
												echo "There was an error uploading the file, please try again!";
											}
										
										}
										
										
										
										
										?>
										
										
										

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
		<br><br><br><br><br><br><br>
		
		<?php include("footer.php");?>
	