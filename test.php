<html>
<body>
<?php 	//NAME
	if(isset($_POST["name"])){
	$name = $_POST["name"];
	} else {
	$name = false;
	}

	//AGE
	if (isset($_POST["age"])){
	$age = $_POST["age"];
	} else {
	$age = false;
	}

	//GCS
	if (isset($_POST["eye"]) && isset($_POST["verbal"]) && isset($_POST["motor"])){
	$gcs = $_POST["eye"] + $_POST["verbal"] + $_POST["motor"];
	} else{
	$gcs = false;
	}


	//GENDER
	if(isset($_POST["gender"])){

		if ($_POST["gender"] == 'Male') {

		$gender = 'Male';

		} else {

		$gender = 'Female';
		}

	} else {
	$gender = false;
	}

	
	//SYMPTOMS
	if (isset($_POST["symp"])){
		$symptoms = implode(',',$_POST['symp']);
	} else {
		$symptoms = false;
	} 
	
	//Diagnosis
	if (isset($_POST["diagnosis"])){
		$diagnosis = implode(',',$_POST['diagnosis']);
	} else {
		$diagnosis = false;
	} 
	//INJURY CAUSE
	if (isset($_POST["injurycause"])){
		$injurycause = implode(',',$_POST['injurycause']);
	} else {
		$injurycause = false;
	} 
	//ISS
	//head
	if(isset($_POST["head"])){
		$head = $_POST["head"];
	} else {
		$head = 0;
	}
	//face
	if(isset($_POST["face"])){
		$face = $_POST["face"];
	} else {
		$face = 0;
	}
	//chest
	if(isset($_POST["chest"])){
		$chest = $_POST["chest"];
	} else {
		$chest = 0;
	}
	//external
	if(isset($_POST["external"])){
		$external = $_POST["external"];
	} else {
		$external = 0;
	}	
	//extremities
	if(isset($_POST["extremities"])){
		$extremities = $_POST["extremities"];
	} else {
		$extremities = 0;
	}	
	//abdomen
	if(isset($_POST["abdomen"])){
		$abdomen = $_POST["abdomen"];
	} else {
		$abdomen = 0;
	}
	$ais = array($head, $face, $chest, $external, $extremities, $abdomen);
	if (array_sum($ais) == 0){
		$iss = "Undefined";
	} else if (max($ais) == 6){
		$iss = 75;
	} else if (max($ais) == 0){
		$iss = 0;
	} else {
		rsort($ais);
		$largest = array_slice($ais, 0, 3);
		$iss = 0;
		for ($i = 0; $i <= 2; $i = $i+1){
			$iss += pow($largest[$i], 2);
		}
	}
	
	//Respiratory
	if(isset($_POST["resp"])){
		if ($_POST["resp"] == "u"){
			$resp = false;
		} else{
			$resp = $_POST["resp"];
		}
	} else {
		$resp = false;
	}
	if ($resp == false){
		$respval = false;
	} else if ($resp == 0){
		$respval = "0";
	} else if ($resp == 1){
		$respval = "1-5";
	} else if ($resp == 2){
		$respval = "6-9";
	} else if ($resp == 4){
		$respval = "10-29";
	} else if ($resp == 3){
		$respval = "30 or More";
	}
	
	//BP
	if(isset($_POST["bp"])){
		if ($_POST["bp"] == "u"){
			$bp = false;
		} else{
			$bp = $_POST["bp"];
		}
	} else {
		$bp = false;
	}
	if ($bp == false){
		$bpval = false;
	} else if ($bp == 0){
		$bpval = "0";
	} else if ($bp == 1){
		$bpval = "1-49";
	} else if ($bp == 2){
		$bpval = "50-75";
	} else if ($bp == 3){
		$bpval = "76-89";
	} else if ($bp == 4){
		$bpval = "90 or More";
	}
	
	//Temperature
	if(isset($_POST["temp"])){
		$temp = $_POST["temp"];
	} else {
		$temp = false;
	}
	
	//Pulse
	if(isset($_POST["pulse"])){
		$pulse = $_POST["pulse"];
	} else {
		$pulse = false;
	}
	
	//RTS
	if ($gcs == false){
		$gcspoints = false;
	}else if($gcs <= 3){
		$gcspoints = 0;
	}else if (($gcs > 3)&&($gcs <= 5)){
		$gcspoints = 1;
	}else if (($gcs > 5)&&($gcs <= 8)){
		$gcspoints = 2;
	}else if (($gcs > 8)&&($gcs <= 12)){
		$gcspoints = 3;
	}else if ($gcs > 12){
		$gcspoints = 4;
	}
 
	if($gcspoints && $bp && $resp){
		$rts = ($resp*0.2908) +($bp*0.7326) + ($gcspoints*0.9368);
	} else {
		$rts = false;
	}
	//agepoints
	if ($age == false){
		$agepoints = false;
	} else if($age === "55-65"){ 
		$agepoints = 1;
	} else if ($age === "More than 65"){
		$agepoints = 1;
	}else {
		$agepoints = 0;
	}
	
	
	if ($rts && $iss && $age){
		////TRISS(BLUNT)
		$logitblunt = (-0.4499 + ($rts*0.8085) + ($iss*-0.0835) + ($agepoints*-1.7430));
		$trissblunt = (1/(1 + exp($logitblunt)))*100;
		//TRISS(PENETRATING)
		$logitpenetrating = (-2.5355 + ($rts*0.9934) + ($iss*-0.0651) + ($agepoints*-1.1360));
		$trisspenetrating = (1/(1 + exp($logitpenetrating)))*100;
	} else {
		$trisspenetrating = false;
		$trissblunt = false;
	}
	
	//survivalblunt & survivalpenetrating
	if ($trisspenetrating){
		$survivalblunt = 1 - ($trissblunt/100);
		$survivalpenetrating = 1 - ($trisspenetrating/100);
	} else {
		$survivalblunt = false;
		$survivalpenetrating = false;
	}
?>
<center>
<br> <h1> Summary of Information Sent</h1></br>
<h3> PATIENT SUMMARY</h3>
NAME:<?php print $name; ?> <br>
GENDER: <?php print $gender; ?> <br>
AGE: <?php print $age; ?> <br>
GCS: <?php print $gcs; ?> <br>
SYMPTOMS <?php print $symptoms; ?> <br>
PROV. DIAGNOSIS : <?php print $diagnosis; ?><br>
CAUSE OF INJURY :<?php print $injurycause; ?><br>

<h3> VITALS </h3>
RESPIRATORY RATE: <?php print $respval; ?> <br>
SYSTOLIC BP:<?php print $bpval; ?><br>
TEMPERATURE:<?php print $temp; ?><br>
PULSE RATE :<?php print $pulse; ?><br>

<h3> SCORES </h3>
GCS: <?php print $gcs; ?> <br>
ISS <?php print $iss; ?><br>
RTS :<?php print $rts; ?><br>
TRISS BLUNT :<?php print $trissblunt; ?><br>
TRISS PENETRATING :<?php print $trisspenetrating; ?> <br>
PROBABILITY OF SURVIVAL(BLUNT) : <?php print $survivalblunt; ?><br>
PROBABILITY OF SURVIVAL(PENETRATING) :<?php print $survivalpenetrating; ?>


<h3>Please, note that this form is designed to minimize information loss while filling and submitting the form. 
In order to review/re-submit the information, return to the previous page (using browser options) and 
to renew the fields, refresh the page.</h3>

<?php
$message = "NAME:".$name.' | ' . "AGE:" . $age . ' | '."GENDER:".$gender.' | '."R/R:".$respval.' | '."SYS.B/P:".$bpval.' | '."TEMP.:".$temp.' | '
."PULSE:".$pulse.' | ' ."SYMPTOMS:".$symptoms .' | '. "PROV.DIAGNOSIS:".$diagnosis.' | '."INJURY CAUSE:".$injurycause.' | '."GCS:".$gcs.' | '
."ISS:".$iss .' | '."RTS:".$rts.' | ' . "TRISS-BLUNT:".$trissblunt.' | '. "TRISS-PENETRATING:".$trisspenetrating.' | ';

$to      = $_POST["email"];
$subject = 'Incoming Patient Alert';
$message = wordwrap($message, 70);
$headers = 'From: ali@1stest.co.uk' ;

$mail = mail($to, $subject, $message, $headers);

?> 

<?php
//$name
//$age, $agepoints
//$symptoms
//$gcs, $gcspoints
//$gender
//$resp, $respval
//$bp, $bpval
// $pulse
//$temp
//$diagnosis
//$injurycause
//$trissblunt
//$trisspenetrating
//$survivalblunt
//$survivalpenetrating

// Abdominal Pain,diarrhea,Nausea,Unconscious,Back Pain,Dizziness,Numbness,Vaginal Bleed,Bleeding,Drowsiness,Paralysis,Vomiting,
//Bloody Stool,Eye/Ear Pain,Palpitatipons,Weakness/bodyache,Shortness of Breath,Fever,Pregnancy/Childbirth,Unknown,Cardica Arrest,
//Headache,Respiratory Arrest,Others,Chest Pain,Hypertension,Seizures/Convulsions,NONE,Choking,Skin COndition,Syncope/Fainting 
?>

</body>
</html>