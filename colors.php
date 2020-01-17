<!-- /*
   ____       ___      ______   _____   ___    _
  / /\ \     / /\ \   | |_]_]  ||   || | |\\  | |
 / /__\ \   / /__\ \  | |\ \   ||   || | | \\ | |
/_/    \_\ /_/    \_\ | | \ \  ||___|| | |  \\|_|


Author: Aaron Harold C.
	Bachelor of Science in Information Technology, 
	Technological University of the Philippines Taguig Campus -->  

<!-- i want to create a program that makes 
a color palette for me because color picking is my weakness -->
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
  Select your dominant color: <input type="color" name="domcolor" value="#ff0000"><br><br>
  <input type="submit">
</form>
<?php
	if($_SERVER['REQUEST_METHOD'] == "GET") {
		if(isset($_GET['domcolor'])){
			$hexstring = $_GET['domcolor'];
			echo "$hexstring<br>";
		}else {
			$hexstring = "#ff0000";
		}
	}else {
		$hexstring = "#ff0000";
	}
	$trimhexstring = ltrim($hexstring, '#');

function hextorgb($trimhexstring){
	global $rgb;
	if(strlen($trimhexstring) === 3){
        list($iRed, $iGreen, $iBlue) = array_map(
            function($sColor){ 
                return hexdec(str_repeat($sColor, 2)); 	
            }, 
            str_split($trimhexstring, 1));
    }else {
    	list($iRed, $iGreen, $iBlue) = array_map(
    		function($sColor){
    			return hexdec($sColor);
    		}, 
    		str_split($trimhexstring, 2));
    }

   	echo 'rgb(' . $iRed . ', ' . $iGreen . ', ' . $iBlue . ')'; 
    return $rgb = array("r" => $iRed , "g" => $iGreen, "b" => $iBlue);
}

function rgb(){
	global $rgb;
	echo 'rgb(' . $rgb["r"] . ', ' . $rgb["g"] . ', ' . $rgb["b"] . ')';
}

hextorgb($trimhexstring);
echo "<br>";
$rgbrange = array();

$rgbrange["rr"] = round($rgb["r"] / 255 , 2);
$rgbrange["gr"] = round($rgb["g"] / 255 , 2);
$rgbrange["br"] = round($rgb["b"] / 255 ,2);


function luminance(){
	global $rgbrange, $saturation , $luminance;
	$luminance = (max($rgbrange)+ min($rgbrange))/2;
	$luminance = round($luminance * 100, 0);
	 
	$temp_luminance = ($luminance / 100);

	if($temp_luminance < 0.5){
		$saturation = (max($rgbrange)-min($rgbrange))/(max($rgbrange)+min($rgbrange));
		$saturation = $saturation * 100;
	} else {
		$saturation = (max($rgbrange)-min($rgbrange))/(2.0-max($rgbrange)-min($rgbrange));
		$saturation = $saturation * 100;
	}
}

luminance();


if($rgbrange["rr"] == max($rgbrange)){
	$hue = ($rgbrange["gr"]- $rgbrange["br"])/(max($rgbrange)- min($rgbrange));
	$hue = round($hue * 60 , 1); 
}else if ($rgbrange["gr"] == max($rgbrange)) {
	$hue = 2.0 + ($rgbrange["br"]-$rgbrange["rr"])/(max($rgbrange)-min($rgbrange));
	$hue = round($hue * 60 , 1);
}else if ($rgbrange["br"] == max($rgbrange)) {
	$hue = 4.0 + ($rgbrange["rr"]- $rgbrange["gr"])/(max($rgbrange)-min($rgbrange));
	$hue = round($hue * 60 , 1);
}
function dethue($hue){
	if ($hue > 180) {
		// this is when hue is greater than 180^degrees
		$hue = round($hue - 180, 0);
	} else {
		$hue = round($hue + 180, 0);
	}
	return $hue;
}

$hsl = array();

function hsl(){
	global $hsl;
	echo "hsl(".$hsl["h"] ."," .$hsl["s"] . "%," . $hsl["l"] ."%)";
}
function mutecompliment() {
	 // find the compliment of the original color and decrease saturation and luminance
	 global $hsl,$hue, $saturation, $luminance;
	 $hsl["h"] = dethue($hue);
	 $hsl["s"] = round($saturation/2 , 0);
	 $hsl["l"] = round($luminance/2 , 0);
}
function originaltint(){
	// make the color brighter and less saturation
	global $hue, $luminance, $saturation;
	$temp_satu = round($saturation/2 ,0);
	if ($luminance < 50) {
		$luminance += 50;
	}elseif($luminance > 60) {
		$luminance = ($luminance/2) + 50;
	}
	echo "hsl(". round($hue, 0). "," . $temp_satu . "%," . $luminance."%)"; 
}

function complimenttint(){
	//make the color brighter and less saturation
	global $hue, $luminance, $saturation;
	$temp_satu = round($saturation/2 ,0);
	if ($luminance < 50) {
		$luminance += 50;
	}elseif($luminance > 50) {
		$luminance = ($luminance/2) + 50;

	}
	echo "hsl(". dethue($hue) . "," . $temp_satu . "%," . $luminance."%)"; 
}

function originalshadow(){
	// lower brightness to its half and move hue closer to blue(200) and increase little in saturation
	global $hue, $luminance, $saturation;
	$half_lumi = $luminance/2;
	$temp_satu = $saturation + $half_lumi;
	echo "hsl(". rand(1,250) . "," . $temp_satu . "%," . $half_lumi ."%)"; 
}

function complimentshadow() {
	// lower brightness to its half and move hue closer to blue(200) and increase little in saturation
	global $hue, $luminance, $saturation;
	$half_lumi = $luminance/2;
	$temp_satu = $saturation + $half_lumi;
	echo "hsl(". dethue($hue) . "," . $temp_satu . "%," . $half_lumi ."%)"; 
}

function balancer(){
	global $hue, $luminance, $saturation;
	echo 'hsl(' . 0 . ', ' . 0 . '%, ' . $luminance . '%)';
}
mutecompliment();

function colorPalettes(){
	echo rgb(). " => main<br>";
	echo originaltint(). " => tint<br>";
	echo originalshadow(). " => shadow<br>";
	echo hsl(). " => compliment<br>";
	echo complimenttint(). " => compliment tint<br>";
	echo complimentshadow(). " => compliment shadow<br>";
	echo balancer(). " => balancer<br>";
}

colorPalettes();
?>

<!DOCTYPE html>
<html>
<head>
	<title>COLOR PALETTE MAKER</title>
<style>
#attrib {
	height: 40px;
	width: 100px;
}
.square1 {
	background-color: <?php rgb(); ?>;
}
 .mutecompliment{
 	background-color: <?php hsl(); ?>;
 }
 .tint1 {
 	background-color: <?php originaltint() ?>;
 }
 .tint2 {
 	background-color: <?php complimenttint() ?>;
 }
 .shadow1 {
 	background-color: <?php originalshadow()  ?>;
 }
 .shadow2{
 	background-color: <?php complimentshadow()  ?>;
 }
 .balancer {
 	background-color: <?php balancer() ?>;
 }
 body {
 	margin: auto;
 	width: 100%;
 }

 .wrapper {
 	margin: auto;
 	width: 10%;
 }

</style>
</head>
<body>
<div class="wrapper">
<div class="square1" id="attrib"></div>
<div class="tint1" id="attrib"></div>
<div class="shadow1" id="attrib"></div>
<div class="mutecompliment" id="attrib"></div>
<div class="tint2" id="attrib"></div>
<div class="shadow2" id="attrib"></div>
<div class="balancer" id="attrib"></div>
</div>
</body>
</html>