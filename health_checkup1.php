<?php 
include("header.php");
include('connection.php');	
?>


		<!-- Start Breadcrumbs -->
		<section id="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2>Health Check Up</h2>
						<ul>
							<li><a href="index1.php">Home</a></li>
							<li class="active"><a href="checkup.php">Health Checkup</a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!--/ End Breadcrumbs -->
		
				<section id="blog" class="section page" style="min-height:450px;">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="entry-blog">
							<!-- Single Blog -->
							<div class="single-blog">
								<div class="comments-form">
									<div style="background-color:red; padding:40px; border-radius:20px;">
									<a href="health_checkup.php">
									<h3 style="color:white;">
									To check your spo2, BPM, Temp. Please place your finger on the sensor.
									</h3>
									</a>
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
		<br><br><br><br><br><br><br><br>		
		
		<?php include("footer.php");?>
	