<!DOCTYPE html>
<html>
<title>Encode Quality Results</title>
<link rel="stylesheet" href="/intel/css/w3.css">
<link rel="stylesheet" href="/intel/css/w3-theme-black.css">
<body>

<?php 
	include 'function.php';
	$inputmachinearray=array();
	if(isset($_GET["machinearray"])) $inputmachinearray=$_GET["machinearray"];
	$inputsuitearray=array();
	if(isset($_GET["suitearray"])) $inputsuitearray=$_GET["suitearray"];
	$inputstartdate=$_GET["startdate"];
	if(isset($_GET["and"])) {
		$inputand=$_GET["and"];
		$inputenddate=$_GET["enddate"];
	}
	$inputsuffix=$_GET["suffix"];
	echo $inputsuffix;
?>

<h2>Encode Quality Results</h2>

<div style="overflow: auto">
<?php
		$con = mysqli_connect("localhost", "root", "08293028") or die ('Could not connect: ' . mysqli_error());
		$db_selected = mysqli_select_db($con, "media") or die ('Could not select database');
	foreach ((array)$inputmachinearray as $mvalue) {
		echo "<h3>$mvalue</h3>";
		$idsql="SELECT id from build_driver where machine_name='$mvalue' and date_time='$inputstartdate'";
		$idresult=mysqli_query($con, $idsql);
		while ($line=mysqli_fetch_array($idresult)) {
			$machineid=$line['id'];
			//echo $machineid;
			?>	<table class='w3-table w3-striped w3-bordered w3-border w3-hoverable'>
			<?php foreach ((array)$inputsuitearray as $svalue) {
				$outputresult=mysqli_query($con, "SELECT * from encode_quality_tab where driver_id='$machineid' and testsuites='$svalue'");
				if(mysqli_num_rows($outputresult)>0){
				?>
	    			<thead>
			      		<tr class='w3-theme-dark'>
					        <?php echo "<th>$svalue</th>"; ?>
					        <?php echo "<th>bitrate output</th>"; ?>
					        <?php echo "<th>YPSNR</th>"; ?>
					        <?php echo "<th>RATE</th>"; ?>
					    </tr>
	    			</thead>
	    			<?php while ($outputline=mysqli_fetch_array($outputresult)){?>
					    <tr>
					    	<?php
					    	$outputcn=$outputline['casename'];
					    	echo "<td>$outputcn</td>";
					    	$outputcn=$outputline['bitrate_output'];
					    	echo "<td>$outputcn</td>";
					    	$outputcn=$outputline['y_psnr'];
					    	echo "<td>$outputcn</td>";
					    	$outputcn=($outputline['bitrate_output']-$outputline['qp_bitrate'])/$outputline['qp_bitrate'];
					    	echo "<td>$outputcn</td>";?>
					    </tr>
					<?php } ?>
<?php
				}
			}?>
	    		</table>
<?php		}
	}
?>
</div>

</body>
</html>
