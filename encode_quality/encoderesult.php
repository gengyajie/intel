<!DOCTYPE html>
<html>
<title>Encode Quality Results</title>
<link rel="stylesheet" href="./w3.css">
<link rel="stylesheet" href="./w3-theme-black.css">
<link rel="stylesheet" href="./font-awesome.min.css">
<body>

<?php 
	include 'function.php';
	$modeflag=0;
	$inputmachinearray=array();
	if(isset($_GET["machinearray"])) $inputmachinearray=$_GET["machinearray"];
	if(isset($_GET["frame"])) $inputframe=$_GET["frame"];
	if(isset($_GET["rc_mode"])) $inputrc_mode=$_GET["rc_mode"];
	if(isset($_GET["dec_mode"])) $inputdec_mode=$_GET["dec_mode"];
	if(isset($_GET["resolution"])) $inputresolution=$_GET["resolution"];
	switch ($inputresolution) {
		case "4kp":
			$inputresolution=4000;
			break;
		case "1080p":
			$inputresolution=1080;
			break;
		case "720p":
			$inputresolution=720;
			break;
		case "480p":
			$inputresolution=480;
			break;
	}
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

<h2>Encode Quality Results</h2>

<?php
	$con = mysqli_connect("localhost", "root", "vera15") or die ('Could not connect: ' . mysqli_error());
	$db_selected = mysqli_select_db($con, "media") or die ('Could not select database');
	foreach ((array)$inputmachinearray as $mvalue) {
		echo "<h3>$mvalue</h3>"; 
		if($modeflag==2){?>
			<table class='w3-table w3-striped w3-bordered w3-border w3-hoverable'>
	<?php		$datevalue=$inputstartdate;
			while($datevalue != date("Y-m-d", strtotime("$inputenddate +1 day")) ){
				$idsql="SELECT * from build_driver where machine_name='$mvalue' and date_time='$datevalue'";
				$idresult=mysqli_query($con, $idsql);
				while($line=mysqli_fetch_array($idresult)){
					$outid=$line['id'];
					$machinecnum=$line['commit_num'];
					$outputsql="SELECT * FROM encode_quality_tab WHERE driver_id='$outid' and rc_mode='$inputrc_mode' and frame_mode='$inputframe' and resolution_h='$inputresolution'";
					$outputresult=mysqli_query($con, $outputsql);
					//$bitratearray=array();
					if($outputline=mysqli_fetch_array($outputresult)) {?>
						<thead>
							<tr>
								<?php echo "<th>commit number:$machinecnum</th>";?>
							</tr>
				      		<tr class='w3-theme-dark'>
						        <?php echo "<th>$datevalue</th>"; ?>
						        <?php echo "<th>output bitrate</th>"; ?>
						        <?php echo "<th>YPSNR</th>"; ?>
						        <?php echo "<th>RATE</th>"; ?>
						    </tr>
		    			</thead>
		    			<tr>
							<?php if(strpos($outputline['testsuites'], $inputdec_mode)){
								$temp=$outputline['casename'];
							    echo "<td>$temp</td>";  
								$temp=$outputline['bitrate_output'];
							    echo "<td>$temp</td>";  
								$temp=$outputline['y_psnr'];
							    echo "<td>$temp</td>";  
								$temp=($outputline['bitrate_output']-$outputline['qp_bitrate'])/$outputline['qp_bitrate'];
							    echo "<td>$temp</td>"; 
							}?>
						</tr>
	<?php				}
					while ($outputline=mysqli_fetch_array($outputresult)){
						if(strpos($outputline['testsuites'], $inputdec_mode)){ ?>
							<tr>
								<?php $temp=$outputline['casename'];
							    echo "<td>$temp</td>";  
								$temp=$outputline['bitrate_output'];
							    echo "<td>$temp</td>";  
								$temp=$outputline['y_psnr'];
							    echo "<td>$temp</td>";  
								$temp=($outputline['bitrate_output']-$outputline['qp_bitrate'])/$outputline['qp_bitrate'];
							    echo "<td>$temp</td>"; ?>
							</tr>
	<?php					}
					} 
				}
			$datevalue=date("Y-m-d", strtotime("$datevalue +1 day"));
			}?>
			</table>
<?php 	}	

		if($modeflag==1){
			$idsql="SELECT * from build_driver where machine_name='$mvalue' and date_time='$inputstartdate'";
			$idresult=mysqli_query($con, $idsql);
			$plotdata1=array();
			if($line=mysqli_fetch_array($idresult)){
				$outid=$line['id'];
				$machinecnum=$line['commit_num'];
				$outputsql="SELECT * FROM encode_quality_tab WHERE driver_id='$outid' and rc_mode='$inputrc_mode' and frame_mode='$inputframe' and resolution_h='$inputresolution'";
				$outputresult=mysqli_query($con, $outputsql);
				while($outputline=mysqli_fetch_array($outputresult)){
					if(strpos($outputline['testsuites'], $inputdec_mode)){
						$bitratedata=$outputline['bitrate_output'];
						$ypsnrdata=$outputline['y_psnr'];
						$plotdata1=array($bitratedata=>$ypsnrdata)+$plotdata1;
					}
				}
			}
			$idsql="SELECT * from build_driver where machine_name='$mvalue' and date_time='$inputenddate'";
			$idresult=mysqli_query($con, $idsql);
			$plotdata2=array();
			if($line=mysqli_fetch_array($idresult)){
				$outid=$line['id'];
				$machinecnum=$line['commit_num'];
				$outputsql="SELECT * FROM encode_quality_tab WHERE driver_id='$outid' and rc_mode='$inputrc_mode' and frame_mode='$inputframe' and resolution_h='$inputresolution'";
				$outputresult=mysqli_query($con, $outputsql);
				while($outputline=mysqli_fetch_array($outputresult)){
					if(strpos($outputline['testsuites'], $inputdec_mode)){
						$bitratedata=$outputline['bitrate_output'];
						$ypsnrdata=$outputline['y_psnr'];
						$plotdata2=array($bitratedata=>$ypsnrdata)+$plotdata2;
					}
				}
			}
			ksort($plotdata1);
			ksort($plotdata2);
			$length1=count($plotdata1);
			$length2=count($plotdata2);
			if(($length1+$length2)!=0){
				if(!$length1) echo "No information at $inputstartdate";
				if(!$length2) echo "No information at $inputenddate";
			}
			$plotkey1=array_keys($plotdata1); 
			$plotkey2=array_keys($plotdata2); 
			if(($length1+$length2)!=0){?>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<script src="public/jquery-1.8.2.min.js" type="text/javascript"></script>
	    		<script src="public/highcharts.js"></script>
				<script type="text/javascript">
					var chart; 
					$(function() { 
					    chart = new Highcharts.Chart({ 
					        chart: { 
					            renderTo: <?php echo "$mvalue";?>, //图表放置的容器，DIV 
					            defaultSeriesType: 'line', //图表类型为曲线图 
					        }, 
					        title: { 
					            text: 'media comparison'  //图表标题 
					        }, 
					        xAxis: { //设置X轴 
					            //type: 'datetime',  //X轴为日期时间类型 
					            tickPixelInterval: 150  //X轴标签间隔 
					        }, 
					        yAxis: { //设置Y轴 
					            title: '', 
					          //  max: 2000, //Y轴最大值 
					          //  min: 0  //Y轴最小值 
					        }, 
					        tooltip: {//当鼠标悬置数据点时的提示框 
					           /*formatter: function() { //格式化提示信息 
					                return 'CPU使用率'+ 
					                Highcharts.dateFormat('%H:%M:%S', this.x) +''+  
					                Highcharts.numberFormat(this.y, 2)+'%'; 
					            } */
					            enabled: false
					        }, 
					        legend: { 
					            layout: 'horizontal',
	            				align: 'middle',
					           	verticalAlign: 'bottom',
					            borderWidth: 0
					        }, 
					        exporting: { 
					            enabled: false  //设置导出按钮不可用 
					        }, 
					        credits: { 
					            //text: 'Helloweba.com', //设置LOGO区文字 .3"
					            //url: 'http://www.helloweba.com' //设置LOGO链接地址 
					            enabled: false
					        }, 
					        series: [{ 
					        	<?php echo "name: '$inputstartdate'," ;?>
					            data: (function() { //设置默认数据， 
					                var outputkey=<?php echo json_encode($plotkey1); ?>;
					                var outputdata=<?php echo json_encode($plotdata1); ?>;
					                //document.write(outputdata[18454.3]);
					                var data = [], 
					                //time = (new Date()).getTime(), 
					                i,s;
					                var dd=<?php echo $length1;?>;
					                if(dd != 0){
						                for (i = 0; i < dd; i++) { 
						                    data.push({ 
						                    	//x: i,  
		                        				//y: i+5 
						                    	x: outputkey[i],
						                    	y: Number(outputdata[outputkey[i]])
						                    }); 
						                }
						            } 
					                return data; 
					            })() 
					        }, 
					        { 
					        	<?php echo "name: '$inputenddate'," ;?>
					            data: (function() { //设置默认数据， 
					                var outputkey2=<?php echo json_encode($plotkey2); ?>;
					                var outputdata2=<?php echo json_encode($plotdata2); ?>;
					                //document.write(outputdata[18454.3]);
					                var data = [], 
					                //time = (new Date()).getTime(), 
					                i,s;
					                var dd=<?php echo $length2;?>;
					                if(dd != 0){
						                for (i = 0; i < dd; i++) { 
						                    data.push({ 
						                    	//x: i,  
		                        				//y: i+5 
						                    	x: outputkey2[i],
						                    	y: Number(outputdata2[outputkey2[i]])
						                    }); 
						                } 
						            }
					                return data; 
					            })() 
					        }] 
					    }); 
					}); 
					</script>
					<div id=<?php echo "$mvalue";?> style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php		}
		}
	} ?>

</body>
</html>