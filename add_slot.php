<?php 
include("header.php");
include("connection.php");
?>
		

		
		<!-- Start About -->
		<section id="about-us" class="section">
			<div class="container">
				<div class="row">
					<div class="col-md-12 wow fadeInUp">
						<div class="section-title">
							<h2>Add Time<span> Slots</span></h2>
						</div>
					</div>
				</div>
				<div class="row fadeInUp"> 
					<!-- About Main -->
					<div class="col-md-12 col-sm-12  wow fadeInUp">
						<div class="about-main comments-form">
                        <form class="form" method="POST" enctype="multipart/form-data">
											
											<div class="col-md-6">
												<div class="form-group">
													<label>User Name</label>
													<input type="hidden" name="did" placeholder="Slot" class="form-control" value="<?php echo $_SESSION['did']; ?>" required="required" readonly>
													<input type="text" name="slot" placeholder="Slot" required="required" >
												</div>
											</div>
											

											<div class="col-md-12">
												<div class="form-group submit">
													<button class="button" name="submit" type="submit">Add</button>
												</div>
											</div>
										</form>
							
						</div>
					</div>
					<!--/ End About Main -->
					<?php
				  include("connection.php");
				  if(isset($_POST['submit']))
				  {
					  $did=$_POST['did'];
					  $slot=$_POST['slot'];
					  
					  $ins="insert into slot(doc_id,slot) values('$did','$slot')";
					  $res=mysqli_query($con,$ins);
					  echo '<script>alert("Slot Added!")
						  window.location="slot.php";
						  </script>';
				  }
				  ?>
				</div>
			</div>
		</section>
		<!--/ End About -->
		
		
		
		
		
		
		
		
		<?php include("footer.php");?>
	