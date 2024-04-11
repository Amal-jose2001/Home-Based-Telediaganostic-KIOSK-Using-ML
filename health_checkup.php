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
		
				<section id="blog" class="section page">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="entry-blog">
							<!-- Single Blog -->
							<div class="single-blog">
								
								<div class="comments-form">
									<table class="table">
										<tr>
											<th>#</th>
											<th>O2</th>
											<th>bpm</th>
											<th>temperature</th>
										</tr>
										<?php
										$sel=mysqli_query($con,"SELECT * FROM `health_tb`");
										$i=1;
										while($row=mysqli_fetch_array($sel))
										{
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['O2']?></td>
											<td><?php echo $row['bpm']?></td>
											<td><?php echo $row['temperature']?></td>
										</tr>
										<?php
										$i++;
										}
										?>
									</table>
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
	