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
							<h2>View Time<span> Slots</span></h2>
						</div>
					</div>
				</div>
				<div class="row fadeInUp"> 
					<!-- About Main -->
					<div class="col-md-12 col-sm-12  wow fadeInUp">
						<div class="about-main">
							<a href="add_slot.php" class="btn btn-danger">Add Slot</a> <br> <br>
							<table class='table'>
								<tr>
									<th>#</th>
									<th>Slot</th>
								</tr>
								<?php
								$sel=mysqli_query($con,"SELECT * FROM `slot` where doc_id='$_SESSION[did]'");
								//echo "SELECT * FROM `result` where user_id='$_SESSION[uid]'";
								$i=1;
								while($row=mysqli_fetch_array($sel))
								{
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['slot']; ?></td>
								</tr>
								<?php
								$i++;
								}
								?>
								
							</table>
							
						</div>
					</div>
					<!--/ End About Main -->
					
				</div>
			</div>
		</section>
		<!--/ End About -->
		
		
		
		
		
		
		
		
		<?php include("footer.php");?>
	