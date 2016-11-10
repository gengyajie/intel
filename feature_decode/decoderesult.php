<?php
date_default_timezone_set('PRC');
?>
<!DOCTYPE html>
<html>
<title>Feature Decode Results</title>
<link rel="stylesheet" href="/media_driver/css/w3.css">
<link rel="stylesheet" href="/media_driver/css/w3-theme-black.css">
<body>

<?php 
	include 'function.php';
	$andflag=0;
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

<h2>Feature Decode Results</h2>

<?php
	$con = mysqli_connect("ocl", "mmm", "123456") or die ('Could not connect: ' . mysqli_error());
	$db_selected = mysqli_select_db($con, "sjtu") or die ('Could not select database');
	foreach ((array)$inputmachinearray as $mvalue) {
		echo "<h3>$mvalue</h3>";?>
		<div style="overflow-x: auto; overflow-y: hidden;scrollbar-face-color:#B3DDF7;">
		<table class='w3-table w3-striped w3-bordered w3-border w3-hoverable'>
<?php	foreach ((array)$inputsuitearray as $svalue) {	
			$inputdate=array($inputstartdate);
			if($modeflag==1){
				$inputdate[]=$inputenddate;
			}
			else if($modeflag==2){
				$datevalue=date("Y-m-d", strtotime("$inputstartdate +1 day"));
				while($datevalue != date("Y-m-d", strtotime("$inputenddate +1 day")) ){
					$inputdate[]=$datevalue;
					$datevalue=date("Y-m-d", strtotime("$datevalue +1 day"));
				}
			}
 ?>
			<thead>
				<tr class='w3-theme-dark'>
			        <?php echo "<th>$svalue</th>"; ?>
			        <?php foreach ((array)$inputdate as $dvalue) {
			        	echo "<th>$dvalue</th>"; 
			        }?>
			    </tr>
		    </thead>
<?php		if($modeflag==1){
				$idsql="SELECT casename from feature_decode_tab where machine_name='$mvalue' and ( date_time='$inputstartdate' OR date_time='$inputenddate' ) and testsuites='$svalue'";
			}
			else if($modeflag==2){
				$idsql="SELECT casename from feature_decode_tab where machine_name='$mvalue' and date_time>='$inputstartdate' and date_time<='$inputenddate' and testsuites='$svalue'";
			}
			$idresult=mysqli_query($con, $idsql);
			while ($line=mysqli_fetch_array($idresult)) {?>
				<tr>
					<?php $outputcn=$line['casename'];
					echo "<td>$outputcn</td>";
					foreach ((array)$inputdate as $dvalue) {
						$outputresult=mysqli_query($con, "SELECT result from feature_decode_tab where machine_name='$mvalue' and date_time='$dvalue' and casename='$outputcn'");
						if($outputline=mysqli_fetch_array($outputresult)){
							$outputrs=$outputline['result'];?>
							<td style="text-align:center;">
							<?php echo "$outputrs"; ?>
							</td>
<?php						}
						else echo "<td style=\"text-align:center;\">N/A</td>";
					}
					/*$outputresult=mysqli_query($con, "SELECT result from feature_decode_tab where machine_name='$mvalue' and date_time='$inputenddate' and casename='$outputcn'");
					if($outputline=mysqli_fetch_array($outputresult)){
						$outputrs=$outputline['result'];
						echo "<td>$outputrs</td>";
					}
					else echo "<td>N/A</td>";*/?>
				</tr>
<?php		}
		}?>
		</table>
		</div>
<?php	}
?>

</body>
</html>
