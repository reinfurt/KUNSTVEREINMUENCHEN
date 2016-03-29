<?php

// membership type
$type["single"]["label-en"] = "Single Membership";
$type["single"]["label-de"] = "Einzelmitgliedschaft";
$type["single"]["price"] = "60";
$type["partner"]["label-en"] = "Partner Membership";
$type["partner"]["label-de"] = "Partnermitgliedschaft";
$type["partner"]["price"] = "90";
$type["reduced"]["label-en"] = "Reduced Membership";
$type["reduced"]["label-de"] = "Ermäßigte Mitgliedschaft";
$type["reduced"]["price"] = "20";
$type["well"]["label-en"] = "Well-wisher";
$type["well"]["label-de"] = "Fördermitgliedschaft Wohltäter";
$type["well"]["price"] = "200";
$type["benefactor"]["label-en"] = "Benefactor";
$type["benefactor"]["label-de"] = "Fördermitgliedschaft Förderer";
$type["benefactor"]["price"] = "1.000";
$type["sponsor"]["label-en"] = "Sponsor";
$type["sponsor"]["label-de"] = "Fördermitgliedschaft Gönner";
$type["sponsor"]["price"] = "5.000";

// mmember address (billing address)
$addr["name"]["label-en"] = "Surname, Name";
$addr["name"]["label-de"] = "Vorname, Name";
$addr["partnername"]["label-en"] = "Surname, Name (Partner)";
$addr["partnername"]["label-de"] = "Vorname, Name (Partner)";
$addr["streetaddress"]["label-en"] = "Address";
$addr["streetaddress"]["label-de"] = "Straße, Hausnummer";
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
$bank["account"]["label-de"] = "Kontonummer";
$bank["account"]["label-en"] = "Account number";
// german-only
$bank["blz"]["label-de"] = "BLZ";
// english-only
$bank["iban"]["label-en"] = "IBAN";
$bank["swift"]["label-en"] = "SWIFT";

// CHANGE THIS WHEN GOING LIVE
$km_email = "clara@kunstverein-muenchen.de";
// $km_email = "lilyhealey1@gmail.com";
// $km_email = "sarah@kunstverein-muenchen.de";

// build email to be sent to k.m
function email_to_km()
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
	
	$mail = "Hallo!\n\n";
	$mail.= "Soeben ist die folgende Mitgliedsanmeldung eingegangen:\n\n";
	
	if($r == "gift")
	{
		if($lang == "de")
		{
			$mail.= "Ich möchte eine Jahresmitgliedschaft des Kunstverein München e.V.";
			$mail.= "verschenken.\n\n";
		}
		else
		{
			$mail.= "I would like to give an annual membership at Kunstverein München e.V. ";
			$mail.= "as a present to.\n\n";
		}
	}
	else
	{
		if($lang == "de")
			$mail.= "Ich möchte Mitglied im Kunstverein München e.V. werden.\n";
		else
			$mail.= "I would like to become a member of Kunstverein München e.V.\n";
	}
	if($lang == "de")
		$mail.= "Mitgliedschaft: ";
	else
		$mail.= "Membership Type: ";
	$mail.= $type[$t]["label-".$lang];
	$mail.= "\n";
	
	$mail.= "Membership Fee: ";
	$mail.= $donation;
	$mail.= "\n";
	
	$keys = array_keys($addr);
	for($i = 0; $i < count($keys); $i++)
	{
		$mail.= $addr[$keys[$i]]["label-".$lang].": ";
		$mail.= $addr[$keys[$i]]["r"]."\n";
	}
	$mail.= "\n";
	
	$mail.= "Rechnungsadresse (falls abweichend von der Mitgliedsadresse):\n";
	for($i = 0; $i < count($keys); $i++)
	{
		if($addr[$keys[$i]]["b"])
		{
			$mail.= $addr[$keys[$i]]["label-".$lang].": ";
			$mail.= $addr[$keys[$i]]["b"]."\n";
		}
	}
	$mail .= "\n";
	
	if($b == "deposit")
	{
		if($lang == "de")
			$mail.= "Bezahlung: Bankeinzug.\n";
		else
			$mail.= "Payment: Direct debit.\n";
		$keys = array_keys($bank);
		for($i = 0; $i < count($keys); $i++)
		{
			if($bank[$keys[$i]]["label-".$lang])
			{
				$mail.= $bank[$keys[$i]]["label-".$lang].": ";
				$mail.= $bank[$keys[$i]]["v"]."\n";
			}
		}
	}
	return $mail;
}

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
	
	$mail = file_get_contents("static/txt/email_to_km_".$lang.".txt");
	
	// purchased / gifted
	if ($r == "gift")
		$mail = str_replace("[p/g]", "gifted", $mail);
	else
	{
		$mail = str_replace("[p/g]", "purchased", $mail);
		$mail = str_replace("RECIPIENT", "MEMBER", $mail);
		$mail = str_replace("SENDER ADDRESS\n", "", $mail);
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
			$mail = str_replace("[r$keys[$i]]", $addr[$keys[$i]]["label-".$lang].":\n".$addr[$keys[$i]]["r"]."\n", $mail);
		else
			$mail = str_replace("[r$keys[$i]]\n", "", $mail);
	}
	
	// sender address
	for($i = 0; $i < count($keys); $i++)
	{
		if($addr[$keys[$i]]["b"])
			$mail = str_replace("[b$keys[$i]]", $addr[$keys[$i]]["label-".$lang].":\n".$addr[$keys[$i]]["b"]."\n", $mail);
		else
			$mail = str_replace("[b$keys[$i]]\n", "", $mail);
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
				$mail = str_replace("[$keys[$i]]\n", "", $mail);
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
			$mail.= "\n\nPlease send the membership card to the recipient.";
		else
			$mail.= "\n\nPlease send the membership card to the sender.";
	}
	
	return $mail;
}

?>