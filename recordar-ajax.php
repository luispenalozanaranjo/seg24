<?php
require_once("js/recaptcha/recaptchalib.php");
// tu clave secreta
if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']!=''){
	$secret 	=	"6LcT0Q4TAAAAAGvB0_amrsbeRBwPNFxIc8I3w17x";
	$ip 		=	$_SERVER['REMOTE_ADDR'];
	$catpcha 	=	$_POST['g-recaptcha-response'];
	echo "PASA<br>https://www.google.com/recaptcha/api/siteverify?secret=$secret&catpcha=$catpcha&remoteip=$ip";
	$resp 		=	file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&catpcha=$catpcha&remoteip=$ip");
	var_dump($resp);
}
else
echo "falla";
?>