<?php
date_default_timezone_set('PRC');
?>
<!DOCTYPE html>
<html>
<?php
  $configure = include('config.php');
?>
<title>Encode Quality</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/intel/css/w3.css">
<link rel="stylesheet" href="/intel/css/w3-theme-black.css">
<link rel="stylesheet" href="/intel/css/font-awesome.min.css">
<head>
<style>
.center {
    margin: auto;
    width: 60%;
}
</style>
</head>
<body>

<!-- <ul class="w3-navbar w3-theme"> -->
  <!-- <li><a class="w3-padding-16" href="/intel/encode_quality">Encode Quality</a></li>
  <li><a class="w3-padding-16" href="/intel/feature_encode">Feature Encode</a></li>
  <li><a class="w3-padding-16" href="/intel/encode_decode">Encode Decode</a></li>
  <li><a class="w3-padding-16" href="/intel/performance">Performance</a></li> -->
  
<!-- </ul> -->

<form action="qualityresult.php" method=GET>
<!-- Header -->
<header class="w3-container w3-theme w3-padding" id="myHeader">
  <li class="w3-dropdown-hover">
    NAVIGATION
    <div class="w3-dropdown-content w3-card-4">
      <a class="w3-padding-16" href="/intel">HOME</a>
      <a class="w3-padding-16" href="/intel/encode_quality">Encode Quality</a>
      <a class="w3-padding-16" href="/intel/feature_encode">Feature Encode</a>
      <a class="w3-padding-16" href="/intel/feature_decode">Feature Decode</a>
      <a class="w3-padding-16" href="/intel/performance">Performance</a>
    </div>
  </li>
  <div class="w3-center">
  <h1 class="w3-xxxlarge w3-animate-bottom">Meida Driver Web Portal: Encode Quality</h1>
  </div>
</header>

<div class="w3-row-padding w3-margin-top">
<div class="w3-third">
  <div class="w3-card-2 w3-padding-top" style="min-height:460px">
  <div class="w3-center"><h4>Machine</h4></div>
	<div class="center">
 	<?php

$con = mysqli_connect("localhost", "root", "08293028");
$db_selected = mysqli_select_db($con, "media");
$msql = "SELECT DISTINCT machine_name from build_driver";
$ssql = "SELECT DISTINCT testsuites from encode_quality_tab";
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
	<div class="w3-center"><h4>Suite</h4></div>
	<div class="center">
	    <?php
			//$suites=array("encode_hevc_cqp","OpenCL_utests","OpenCL_misc","OpenCL_piglit","OpenCV_test","OpenCL_conformance_1_2");
			//foreach ($suites as $activesuitname) {
			//echo "<p><input id=$activesuitname name=suitearray[] value=$activesuitname class=w3-check type=checkbox><label class=w3-validate>$activesuitname</label></p>";
			//}
			mysqli_close($con);
		?>
	</div>
   <table class="center">
      <tr>
        <td><label for="dec_mode">Decode Mode</label></td>
        <td>
          <select id="dec_mode" name="dec_mode">
            <option></option>
            <?php
              foreach ($configure['decode_mode'] as $value) {
                echo "<option value=$value>$value</option>";
              }
            ?>
          </select>
        </td>
      </tr>
      <tr></tr>
      <tr>
        <td><label for="resolution">Resolution</label></td>
        <td>
          <select id="resolution" name="resolution">
            <option></option>
            <?php
              foreach ($configure['resolution'] as $value) {
                echo "<option value=$value>$value</option>";
              }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td><label for="frame">Frame</label></td>
        <td>
          <select id="frame" name="frame">
            <option></option>
            <?php
              foreach ($configure['frame'] as $value) {
                echo "<option value=$value>$value</option>";
              }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td><label for="rc_mode">RC Mode</label></td>
        <td>
          <select id="rc_mode" name="rc_mode">
            <option></option>
            <?php
              foreach ($configure['rc_mode'] as $value) {
                echo "<option value=$value>$value</option>";
              }
            ?>
          </select>
        </td>
      </tr>
    </table>
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
        echo "<input id=and class='w3-radio' type='radio' name='mode' value='and'><label class=w3-validate>AND</label>";
      ?>
      <pre></pre>
      <?php
        echo "<input id=to class='w3-radio' type='radio' name='mode' value='to'><label class=w3-validate>TO</label>";
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