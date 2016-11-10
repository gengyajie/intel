<?php
date_default_timezone_set('PRC');
?>
<!DOCTYPE html>
<html>
<title>Feature Decode</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/media_driver/css/w3.css">
<link rel="stylesheet" href="/media_driver/css/w3-theme-black.css">
<link rel="stylesheet" href="/media_driver/css/font-awesome.min.css">
<head>
<style>
.center {
    margin: auto;
    width: 60%;
}
</style>
</head>
<body>

<form action="decoderesult.php" method=GET>
<!-- Header -->
<header class="w3-container w3-theme w3-padding" id="myHeader">
  <li class="w3-dropdown-hover">
    NAVIGATION
    <div class="w3-dropdown-content w3-card-4">
      <a class="w3-padding-16" href="/media_driver">HOME</a>
      <a class="w3-padding-16" href="/media_driver/encode_quality">Encode Quality</a>
      <a class="w3-padding-16" href="/media_driver/feature_encode">Feature Encode</a>
      <a class="w3-padding-16" href="/media_driver/feature_decode">Feature Decode</a>
      <a class="w3-padding-16" href="/media_driver/performance">Performance</a>
    </div>
  </li>
  <div class="w3-center">
  <h1 class="w3-xxxlarge w3-animate-bottom">Media Driver Web Portal: Feature Decode</h1>
  </div>
</header>

<div class="w3-row-padding w3-margin-top">
<div class="w3-third">
  <div class="w3-card-2 w3-padding-top" style="min-height:460px">
  <div class="w3-center"><h4>Machine</h4></div>
	<div class="center">
 	<?php

$con = mysqli_connect("ocl", "mmm", "123456");
$db_selected = mysqli_select_db($con, "sjtu");
$msql = "SELECT DISTINCT machine_name from build_driver";
$ssql = "SELECT DISTINCT testsuites from feature_decode_tab";
$mresult = mysqli_query($con, $msql);
$sresult = mysqli_query($con, $ssql);

$machine=array();
$suites=array();
while($line=mysqli_fetch_array($mresult)){
   array_push($machine, $line['machine_name']);
}
while($line=mysqli_fetch_array($sresult)){
   array_push($suites, $line['testsuites']);
}

	   //$machine=array("x-bxtcl1_unstable","x-sklcl1_unstable","x-bswcl1_unstable","x-bdwcl1_unstable","x-hswcl1_unstable","xbytcl1_unstable","x-ivbcl2_unstable","x-e3cl1_unstable");
		 foreach ($machine as $activemachinename) {
			echo "<p><input id=$activemachinename name=machinearray[] value=$activemachinename class=w3-check type=checkbox><label class=w3-validate>$activemachinename</label></p>";
		  }
  ?>
  </div>
  </div>
</div>

<div class="w3-third">
  <div class="w3-card-2 w3-padding-top" style="min-height:460px">
  <div class="w3-center"><h4>Suites</h4></div>
  <div class="center">
    <?php
      //$suites=array("encode_hevc_cqp","OpenCL_utests","OpenCL_misc","OpenCL_piglit","OpenCV_test","OpenCL_conformance_1_2");
      foreach ($suites as $activesuitname) {
      echo "<input id=$activesuitname name=suitearray[] value=$activesuitname class=w3-check type=checkbox><label class=w3-validate>$activesuitname</label><br>";
      }
mysqli_close($con);
		?>
	</div>  
  </div>
</div>

<div class="w3-third">
  <div class="w3-card-2 w3-padding-top" style="min-height:460px">
  <div class="w3-center"><h4>Date</h4></div>
	<div class="center">
      <p>Please input the date in format: Year-Month-Day</p>
      <p><label>Start Date</label></p>
      <?php 
        $date=date("Y-m-d");
        echo "<p><input class='w3-input w3-border' name=startdate type=text value=$date></p>";
      ?>

      <?php 
        echo "<input id=and class='w3-radio' type=radio name=mode value=and><label class=w3-validate>AND</label>";
      ?>
      <pre></pre>
      <?php
        echo "<input id=to class='w3-radio' type=radio name=mode value=to><label class=w3-validate>TO</label>";
      ?>

      <p><label>End Date</label>
      <?php
        echo "<p><input class='w3-input w3-border' name=enddate type=text value=$date></p>";
      ?>
	</div>  
  </div>
  </div>
</div>

<div class="w3-center">
      <button class="w3-btn w3-xxlarge w3-dark" style="font-weight:500;">Submit</button>
</div>
</div>
</form>

</body>
</html>
