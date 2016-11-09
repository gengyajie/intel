<?php
	function connectdb() {
		$con = mysqli_connect("localhost", "root", "08293028") or die ('Could not connect: ' . mysqli_error());
		$db_selected = mysqli_select_db($con, "media") or die ('Could not select database');
	}
	function getclm(){
		$con = mysqli_connect("localhost", "root", "08293028");
		$db_selected = mysqli_select_db( $con,"media");
		$sql = "desc encode_quality_tab";
		$result = mysqli_query($con,$sql);
		$clmresult=array();
		for($x=0; $x<5; $x++) $line=mysqli_fetch_array($result);
		while($line=mysqli_fetch_array($result)){
			//if($line['Field']!='id')
				array_push($clmresult, $line['Field']);
		}
		return $clmresult;
	}

?>
