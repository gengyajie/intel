<?php
date_default_timezone_set('PRC');
?>

<!DOCTYPE html>
<html>
<title>Feature Encode Results</title>
<link rel="stylesheet" href="/media_driver/css/w3.css">
<link rel="stylesheet" href="/media_driver/css/w3-theme-black.css">
<link rel="stylesheet" href="/media_driver/css/font-awesome.min.css">
<head>
<body>

<?php 
	//include 'function.php';
	$inputmachinearray=array();
	if(isset($_GET["machinearray"])) $inputmachinearray=$_GET["machinearray"];
	$inputsuitearray=array();
	if(isset($_GET["suitearray"])) $inputsuitearray=$_GET["suitearray"];
	$inputstartdate=$_GET["startdate"];
	$inputenddate=$_GET["enddate"];
	if(isset($_GET["mode"])) $inputmode=$_GET["mode"];
	switch($inputmode)	{
		case "and":
			$modeflag=1;
			break;
		case "to":
			$modeflag=2;
			break;
	}
?>

<h2>Feature Encode Results</h2>

<?php
	$con = mysqli_connect("ocl", "mmm", "123456") or die ('Could not connect: ' . mysqli_error());
	$db_selected = mysqli_select_db($con, "sjtu") or die ('Could not select database');
	foreach ((array)$inputmachinearray as $mvalue) {
		echo "<h3>$mvalue</h3>";?>
		<div style="overflow-x: auto; overflow-y: hidden;scrollbar-face-color:#B3DDF7;">
		<table class='w3-table w3-striped w3-bordered w3-border w3-hoverable'>
<?php		foreach ((array)$inputsuitearray as $svalue) {	?>
			<thead>
				<tr>
					<?php echo "<th colspan=\"8\">Testsuite: $svalue</th>";?>
				</tr>
			</thead>
<?php			$datevalue=$inputstartdate;
			while($datevalue != date("Y-m-d", strtotime("$inputenddate +1 day")) ){
				$idsql="SELECT * from build_driver where machine_name='$mvalue' and date_time='$datevalue'";
				$idresult=mysqli_query($con, $idsql);
				while($line=mysqli_fetch_array($idresult)){
					$machinecnum=$line['commit_num'];
					//echo "$machinecnum";
					$outputsql="SELECT * FROM feature_encode_tab WHERE machine_name='$mvalue' and date_time='$datevalue' and testsuites='$svalue'";
					$outputresult=mysqli_query($con, $outputsql);
					//$bitratearray=array();
					if($outputline=mysqli_fetch_array($outputresult)) {?>
						<thead>
							<tr class='w3-theme-light'>
								<?php echo "<th colspan=\"8\">commit number:$machinecnum</th>";?>
							</tr>
				      		<tr class='w3-theme-dark'>
						        <?php echo "<th>$datevalue</th>"; ?>
						        <?php echo "<th>Result</th>"; ?>
						        <?php echo "<th>Ypsnr</th>"; ?>
						        <?php echo "<th>Upsnr</th>"; ?>
						        <?php echo "<th>Vpsnr</th>"; ?>
						        <?php echo "<th>ref_Ypsnr</th>"; ?>
						        <?php echo "<th>Db</th>"; ?>
						        <?php echo "<th>Bitrate Range</th>"; ?>
						    </tr>
		    			</thead>
		    			<tr>
							<?php
								$temp=$outputline['casename'];
							    echo "<td>$temp</td>";  
								$temp=$outputline['result'];
							    echo "<td>$temp</td>";  
								$temp=$outputline['y_psnr'];
							    echo "<td>$temp</td>";
								$temp=$outputline['u_psnr'];
							    echo "<td>$temp</td>";
								$temp=$outputline['v_psnr'];
							    echo "<td>$temp</td>";
								$temp=$outputline['y_psnr_ref'];
							    echo "<td>$temp</td>";
							    $temp=$outputline['y_psnr']-$outputline['y_psnr_ref'];
							    echo "<td>$temp</td>";
							    if($outputline['rc_mode']=='CQP') echo "<td>N/A</td>";
							    else{  
								$temp=sprintf("%.8f",($outputline['bitrate_output']-$outputline['qp_bitrate'])/$outputline['qp_bitrate']);
							    echo "<td>$temp</td>";} 
							?>
						</tr>

<?php				}
					while ($outputline=mysqli_fetch_array($outputresult)){
						$temp=$outputline['casename'];
					    echo "<td>$temp</td>";  
						$temp=$outputline['result'];
					    echo "<td>$temp</td>";  
						$temp=$outputline['y_psnr'];
					    echo "<td>$temp</td>";
						$temp=$outputline['u_psnr'];
					    echo "<td>$temp</td>";
						$temp=$outputline['v_psnr'];
					    echo "<td>$temp</td>";
						$temp=$outputline['y_psnr_ref'];
					    echo "<td>$temp</td>";
					    $temp=$outputline['y_psnr']-$outputline['y_psnr_ref'];
					    echo "<td>$temp</td>";
					    if($outputline['rc_mode']=='CQP') echo "<td>N/A</td>";
					    else{  
						$temp=sprintf("%.8f",($outputline['bitrate_output']-$outputline['qp_bitrate'])/$outputline['qp_bitrate']);
					    echo "<td>$temp</td>";} 
					}

				}
				if($datevalue=$inputenddate) $datevalue=date("Y-m-d", strtotime("$datevalue +1 day"));
				else if($modeflag==1) $datevalue=$inputenddate;
				else $datevalue=date("Y-m-d", strtotime("$datevalue +1 day"));
			}
		}
	}
?>

</body>
</html>
