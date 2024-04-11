<?php
include("header.php");
include("connection.php");
$select="select * from doctor where id='$_REQUEST[id]'";
//echo $select;
$res=mysqli_query($con,$select);
$row=mysqli_fetch_array($res);


	$sql=mysqli_query($con,"SELECT * FROM `specialization` WHERE `id`='$row[specialization]'");
	$cc=mysqli_fetch_array($sql);
?>


		<section id="blog" class="section page">
			<div class="container">
				<div class="row">
					<div class="col-md-12 wow fadeInUp">
						<div class="section-title">
							<h2>Our <span>Doctors</span></h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="entry-blog">
							<!-- Single Blog -->
							<div class="single-blog">
								<div class="blog-head">
									<center><img src="admin/doctor/uploads/<?php echo $row['photo'];?>" alt="" class="" style='width: 300px;  height: 300px;'></center>
								</div>
								<div class="blog-info">
									<h4>DR. <?php echo strtoupper($row['name']);?></h4>
									<div class="meta">
										<span> <b>Specialization: </b> <?php echo $cc['specialization'];?></span>
									</div>
									<p><b>Phone: </b><?php echo $row['phone'];?></p>
									<p><b>Email: </b><?php echo $row['email'];?></p>
									<p><b>Experience: </b><?php echo $row['experience'];?> years</p>
									<p><b>Place: </b><?php echo $row['place'];?> </p>
									<p><b>Meet Link: </b><a href="<?php echo $row['meet_link'];?>" target="_blank"><?php echo $row['meet_link'];?> </a> </p><br/>
								</div>
								<!-- Blog Share -->
								<div class="blog-share">
									<ul>
										<li><a href="#"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
										<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
										
									</ul>
								</div>
								<!--/ End Blog Share -->
								<!-- Blog Prev Next -->
								<!--/ End Blog Prev Next -->
								<!-- Blog Comments -->
							</div>
							<!--/ End Single Blog -->
						</div>
					</div>
					<div class="col-md-4">
						<!-- Blog Sidebar -->
						<div class="blog-sidebar">
							<!-- Single Sidebar -->
							<div class="single-sidebar latest">
							<!-- Single Sidebar -->
							<div class="single-sidebar tags">
								<h2>Chat <span>Now</span></h2>
								<ul>
									
									<li><a href="chat1.php?id=<?php echo $row['id'] ?>">Chat Now</a></li>
									
								</ul>

								
								
								
							</div>
								<!--/ End Single Sidebar -->
							</div>
							<!--/ End Blog Sidebar -->
						</div>
					</div>
					<br><br><br><br><br><br>
					<div class="col-md-4">
						<!-- Blog Sidebar -->
						<div class="blog-sidebar">
							<!-- Single Sidebar -->
							<div class="single-sidebar latest">
							<!-- Single Sidebar -->
							<div class="single-sidebar tags">
								<h2>Time <span>Slots</span></h2>
								<ul>		
									<?php
									$select="select * from slot where doc_id='$_REQUEST[id]'";
									//echo $select;
									$res=mysqli_query($con,$select);
									while($row=mysqli_fetch_array($res))
									{
									
									?>
									<li><a href="chat1.php?id=<?php echo $row['id'] ?>" style="    margin-top: 10px;"><?php echo $row['slot'];?></a></li> &nbsp;
									<?php
									}
									?>									
								</ul>

								
								
								
							</div>
								<!--/ End Single Sidebar -->
							</div>
							<!--/ End Blog Sidebar -->
						</div>
					</div>
				<div class="map"></div>
				<div id="particles-js"></div>
			</div>
		</section>
		<!-- End Blog -->	

		<?php

include("footer.php");
?>

