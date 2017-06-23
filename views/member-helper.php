<?php

// membership type
$type["single"]["label-en"] = "Single Membership";
$type["single"]["label-de"] = "Einzelmitgliedschaft";
$type["single"]["price"] = "60";
$type["partner"]["label-en"] = "Partner Membership";
$type["partner"]["label-de"] = "Partnermitgliedschaft";
$type["partner"]["price"] = "90";
$type["student"]["label-en"] = "Student";
$type["student"]["label-de"] = "Sch&#252;ler";
$type["student"]["price"] = "20";
$type["artist"]["label-en"] = "Artist";
$type["artist"]["label-de"] = "K&#252;nstler";
$type["artist"]["price"] = "20";
$type["unemployed"]["label-en"] = "Unemployed";
$type["unemployed"]["label-de"] = "Arbeitslose";
$type["unemployed"]["price"] = "20";
// $type["reduced"]["label-en"] = "Reduced Membership";
// $type["reduced"]["label-de"] = "Ermäßigte Mitgliedschaft";
// $type["reduced"]["price"] = "20";
$type["well"]["label-en"] = "Well-wisher";
$type["well"]["label-de"] = "F&#246;rdermitgliedschaft Wohlt&#228;ter";
$type["well"]["price"] = "200";
$type["benefactor"]["label-en"] = "Benefactor";
$type["benefactor"]["label-de"] = "F&#246;rdermitgliedschaft F&#246;rderer";
$type["benefactor"]["price"] = "1.000";
$type["sponsor"]["label-en"] = "Sponsor";
$type["sponsor"]["label-de"] = "F&#246;rdermitgliedschaft G&#246;nner";
$type["sponsor"]["price"] = "5.000";

// mmember address (billing address)
$addr["name"]["label-en"] = "Surname, Name";
$addr["name"]["label-de"] = "Vorname, Name";
$addr["partnername"]["label-en"] = "Surname, Name (Partner)";
$addr["partnername"]["label-de"] = "Vorname, Name (Partner)";
$addr["streetaddress"]["label-en"] = "Address";
$addr["streetaddress"]["label-de"] = "Strasse, Hausnummer";
$addr["city"]["label-en"] = "Postcode, City";
$addr["city"]["label-de"] = "PLZ, Ort";
$addr["country"]["label-en"] = "Country";
$addr["country"]["label-de"] = "Land";
$addr["email"]["label-en"] = "Email address";
$addr["email"]["label-de"] = "E-Mail";
$addr["mobile"]["label-en"] = "Phone";
$addr["mobile"]["label-de"] = "Telefon";
$addr["birthday"]["label-en"] = "Birthday (dd/mm/yy)";
$addr["birthday"]["label-de"] = "geboren am (TT/MM/JJJJ)";

// bank information
$bank["holder"]["label-de"] = "Kontoinhaber";
$bank["holder"]["label-en"] = "Account Holder";
$bank["bank"]["label-de"] = "Kreditinstitut";
$bank["bank"]["label-en"] = "Credit Institute";
$bank["account"]["label-en"] = "Account number";
$bank["iban"]["label-de"] = "IBAN";
$bank["iban"]["label-en"] = "IBAN";
// german-only
$bank["bic"]["label-de"] = "BIC";
// english-only
$bank["swift"]["label-en"] = "SWIFT";

// CHANGE THIS WHEN GOING LIVE
$km_email = "mitglieder@kunstverein-muenchen.de";
// $km_email = "mitglieder@kunstverein-muenchen.de";
// $km_email = "phoebe@kunstverein-muenchen.de";
// $km_email = "reinfurt@o-r-g.com";
// $km_email = "clara@kunstverein-muenchen.de";
// $km_email = "lilyhealey1@gmail.com";
// $km_email = "sarah@kunstverein-muenchen.de";

// build email to be sent to k.m

function email_to_km2()
{
	global $type;
	global $addr;
	global $bank;
	global $t;
	global $b;
	global $r;
	global $gr;
	global $lang;
	global $donation;
	global $recipient_email;
	
	$mail = file_get_contents("static/txt/email_to_km.txt");
	
	// purchased / gifted
	if ($r == "gift")
		$mail = str_replace("[p/g]", "gifted", $mail);
	else
	{
		$mail = str_replace("[p/g]", "purchased", $mail);
		$mail = str_replace("RECIPIENT", "MEMBER", $mail);
		$mail = str_replace("SENDER ADDRESS<br />", "", $mail);
	}
	
	// membership type
	$mail = str_replace("[type]", $type[$t]["label-".$lang], $mail);

	// membership fee
	$mail = str_replace("[donation]", $donation, $mail);
	
	// recipient address
	$keys = array_keys($addr);
	for($i = 0; $i < count($keys); $i++)
	{
		if($addr[$keys[$i]]["r"])
			$mail = str_replace("[r$keys[$i]]", $addr[$keys[$i]]["label-".$lang].":<br />".$addr[$keys[$i]]["r"]."<br />", $mail);
		else
			$mail = str_replace("[r$keys[$i]]<br />", "", $mail);
	}
	
	// sender address
	for($i = 0; $i < count($keys); $i++)
	{
		if($addr[$keys[$i]]["b"])
			$mail = str_replace("[b$keys[$i]]", $addr[$keys[$i]]["label-".$lang].":<br />".$addr[$keys[$i]]["b"]."<br />", $mail);
		else
			$mail = str_replace("[b$keys[$i]]<br />", "", $mail);
	}
	
	// bank information
	if($b == "deposit")
	{
		$keys = array_keys($bank);
		for($i = 0; $i < count($keys); $i++)
		{
			if($bank[$keys[$i]]["v"])
				$mail = str_replace("[$keys[$i]]", $bank[$keys[$i]]["v"], $mail);
			else
				$mail = str_replace("[$keys[$i]]<br />", "", $mail);
		}
	}
	else
	{
		$mail = explode("BANK", $mail)[0];
		if($lang == "en")
			$mail.= "The member has elected to send the payment via bank transfer.";
		else
			$mail.= "The member has elected to send the payment via bank transfer."; // CHANGE THIS TO GERMAN
	}
	
	if($r == "gift")
	{
		if($recipient_email == "yes")
			$mail.= "<br /><br />Please send the membership card to the recipient.";
		else
			$mail.= "<br /><br />Please send the membership card to the sender.";
	}
	
	return $mail;
}
?>
