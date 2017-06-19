<?php
require_once('member-helper.php');
require_once("lib/lib.php");

$action = $_REQUEST['action'];
$errors = NULL;
$dev = false;
if ($dev)
	$errors["dev"] = "** Please try again in one hour. **<br>";

$r = $_POST["recipient"];
$gr = $_POST["giftrecipient"];
$t = $_POST["type"];
$recipient_email = $_POST["recipient_email"];

$keys = array_keys($addr);
for($i = 0; $i < count($keys); $i++)
{
	$k = $keys[$i];
	$addr[$k]["r"] = $_POST["r".$k];
	$addr[$k]["b"] = $_POST["b".$k];
}

$b = $_POST["payment"];
$keys = array_keys($bank);
for($i = 0; $i < count($keys); $i++)
{
	$k = $keys[$i];
	$bank[$k]["v"] = $_POST[$k];
}
$donation = $_POST["donation".$t];

$form["de"] = "/de/mitgliedschaft/mitglied-werden";
$form['en'] = "/en/membership/apply";

// catch email errors
if($action == "process")
{
	if($recipient_email == "yes" || $r == "own")
	{
		if(empty($addr["email"]["r"]))
		{
			if($lang == "de")
				$errors["email"] = "E-Mail darf nicht leer sein.<br>";
			else
				$errors["email"] = "Email address must not be empty.<br>";
		}
		elseif(!is_valid_email($addr["email"]["r"]))
		{
			if($lang == "de")
				$errors["email"] = "Keine gültige E-Mail-Adresse.<br>";
			else
				$errors["email"] = "Not a valid email address.<br>";
		}
	}
	else
	{
		if(empty($addr["email"]["b"]))
		{
			if($lang == "de")
				$errors["email"] = "E-Mail darf nicht leer sein.<br>";
			else
				$errors["email"] = "Email address must not be empty.<br>";
		}
		elseif(!is_valid_email($addr["email"]["b"]))
		{
			if($lang == "de")
				$errors["email"] = "Keine gültige E-Mail-Adresse<br>";
			else
				$errors["email"] = "Not a valid email address.<br>";
		}
	}
	
	if($t == "well" || $t == "benefactor" || $t == "sponsor")
	{
		if(empty($donation))
			$errors["donation"] = "Please enter a donation amount.<br>";
	}

	// validate direct deposit authorized
	if($b == "deposit")
	{
                if(empty($bank["iban"]))
                {
                        if($lang == "de")
                                $errors["deposit"] = "Nicht gültig ohne IBAN.<br>";
                        else
                                $errors["deposit"] = "Not valid without IBAN.<br>";
                }
		if(!isset($_POST['directdebitauthorize']))
			if($lang == "de")
				$errors["directdebitauthorize"] = "Nicht g&uuml;ltig ohne Einzugserm&auml;chtigung. <br>";
			else
				$errors["directdebitauthorize"] = "Not valid without direct debit authorization. <br>";
	}
}

// send emails
if($action == 'process' && empty($errors))
{
	if($r == "own")
		$to_km["sender"] = $addr["email"]["r"];
	else
		$to_km["sender"] = $addr["email"]["b"];
	$to_km["recipient"] = $km_email;
	$to_km["subject"] = "Neuer Mitgliedschaftsantrag via Website";
	$to_km["body"] = email_to_km2();
	
	$to_member["sender"] = $km_email;
	if($r == "own")
		$to_member["recipient"] = $addr["email"]["r"];
	else
		$to_member["recipient"] = $addr["email"]["b"];
	if($lang == "de")
		$to_member["subject"] = "Ihr Mitgliedsantrag";
	else
		$to_member["subject"] = "Your Membership Application";
	$b = file_get_contents("static/txt/email_to_member_".$lang.".txt");
	$b = str_replace("[type]", $type[$t]["label-".$lang], $b);
	$b = str_replace("[donation]", $donation, $b);
	$to_member["body"] = $b;
	$status = "";
	try
	{
		// email to kunstverein	
		send_mail(	$to_km["sender"], 
					$to_km["recipient"], 
					$to_km["subject"], 
					$to_km["body"]);
		// email to applicant
		send_mail(	$to_member["sender"], 
					$to_member["recipient"], 
					$to_member["subject"], 
					$to_member["body"]);
	}
	catch(Exception $e)
	{
		if($lang == "de")
			$status = "Fehler bei der Verarbeitung.";
		else
			$status = "Error while processing.";
	}
	if(empty($status))
	{
		if($lang == "de" && empty($status))
		{
		?><div class="text">
			<span>Vielen Dank! </span>
			<span>Ihr Mitgliedsantrag wurde entgegengenommen. </span>
			<span>Sie erhalten eine Bestätigung per E-Mail.</span>
		</div><? 
		}
		else
		{
		?><div class="text">
			<span>Thank you! </span>
			<span>Your application has been received. </span>
			<span>We will send you a confirmation through email.</span>
		</div><?
		}
	}
	else
	{
	?><div class="text"><?
		echo $status;	
	?></div><?
	}
}
elseif($action == "type-submitted" || !empty($errors))
{
?><div id="form-container" class="text"><?
if($errors)
{
?><div class="error"><? 
	foreach($errors as $e)
		echo $e; 
?></div><? 
}
if($lang == "de") {
	if($r == "own") {
	?><div>Antrag auf Mitgliedschaft</div><?
	}
	else {
	?><div>Antrag auf Mitgliedschaft</div><?
	}
}
else {
	if($r == "own") {
	?><div>Membership Application</div><?
	}
	else {
	?><div>Membership Gift Application</div><?
	}
}
?><form name="membership" 
		id="membership"
		method="post" 
		action="<? echo $form[$lang]; ?>" 
		accept-charset="utf-8" 
		class="small"
>
	<div><?
		if($lang == "de") {
		?><p>Mitgliedschaftsart</p><?
		} else {
		?><p>Membership Type</p><?
		}
		$keys = array_keys($type);
		for($i = 0; $i < count($keys); $i++)
		{
			$k = $keys[$i];
			$l = $type[$k]["label-".$lang];
			$p = $type[$k]["price"];
		?><div class="membership-type">
			<input 
				type="radio" 
				id="type_<? echo $k; ?>" 
				name="type" 
				value="<? echo $k; ?>"
			>
			<label 
				for="type_<? echo $p; ?>"
			><? echo $l; ?></label>
			<span 
				id="price<? echo $k; ?>"
			><? if($i > 4)
				{
					if($lang == "de")
						echo "(ab ";
					else
						echo "(from "; 
				}
				?>€ <? 
				echo $p; 
				if($i > 4) 
					echo") ";
			?></span><?
			if($i > 4) {
				if($lang == "de")
				{
				?>€<input type="text" name="donation<? echo $k; ?>" placeholder="F&ouml;rdersumme"; ?><?
				}
				else
				{
				?>€<input type="text" name="donation<? echo $k; ?>" placeholder="amount of support"; ?><?
				}
			}
			else {
			?><input type="hidden" name="donation<? echo $k; ?>" value="<? echo $p; ?>"><?
			}
		?></div><?
		}
	?></div>
	<div><?
		if($lang == "de") {
		?><p>Mitgliedsadresse</p><? 
		} else {
		?><p><? if($r == "gift") echo "Recipient "; ?>Address</p><?
		}
		if($r == "gift")
		{
			if($lang == "de") {
				?><input type="checkbox" name="recipient_email" value="yes">Mitgliedsausweis direkt an Empfänger des Geschenks senden.<?
			} else {
				?><input type="checkbox" name="recipient_email" value="yes">Send membership card directly to recipient.<?
			}
		}
		?><table><?
			$keys = array_keys($addr);
			for($i = 0; $i < count($keys); $i++)
			{
				$k = $keys[$i];
				$l = $addr[$k]["label-".$lang];
			?><tr>
				<td><? echo $l; ?></td>
				<td><input type="text" name="r<? echo $k; ?>"></td>
			</tr><?
			}
		?>
		</table>
	</div><?
	if($r == "gift")
	{
	?><div><?
		if($lang == "de")
		{
		?><p>Rechnungsadresse</p><?
		}
		else
		{
		?><p>Your Billing Address</p><?
		}
		?><table><?
			$keys = array_keys($addr);
			for($i = 0; $i < count($keys); $i++)
			{
				$k = $keys[$i];
				$l = $addr[$k]["label-".$lang];
			?><tr>
				<td><? echo $l; ?></td>
				<td><input type="text" name="b<? echo $k; ?>"></td>
			</tr><?
			}
		?></table>
	</div><?
	}
	?><div><?
		if($lang == "de")
		{
		?><p><input type="radio" name="payment" value="deposit"> Bankeinzug</p><?
		}
		else
		{
		?><p><input type="radio" name="payment" value="deposit"> Direct debit</p><?
		}
		?><table><?
			$keys = array_keys($bank);
			for($i = 0; $i < count($keys); $i++)
			{
				$k = $keys[$i];
				$l = $bank[$k]["label-".$lang];
				if($l)
				{
				?><tr>
					<td><? echo $l; ?></td>
					<td><input type="text" name="<? echo $k; ?>" value=""></td>
				</tr><?
				}
			}
		?></table><?
		if($lang == "de")
		{
			?><p><input type="checkbox" name="directdebitauthorize" value="authorize"> Ermächtigung zum Bankeinzug</p>
			<p>Ich ermächtige den Kunstverein München e.V. den oben genannten Jahresmitgliedsbeitrag von meinem Konto mittels SEPA-Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, diese Lastschrift einzulösen.</p><?
		}
		else
		{
			?><p><input type="checkbox" name="directdebitauthorize" value="authorize"> Direct debit authorization</p>
			<p> I hereby authorize Kunstverein München e.V. to transfer the chosen annual membership amount from my bank account by SEPA direct debit.</p><?
		}

	?></div>
	<div><?
		if($lange == "de")
		{
		?><p><input type="radio" name="payment" value="wire"> Überweisung</p><?
		}
		else
		{
		?><p><input type="radio" name="payment" value="wire"> Bank transfer</p><?
		}
		?><div>
			<p><? 
			if($lang == "de")
			{
				?><span>Bitte überweisen Sie den Mitgliedsbeitrag</span>
				<span>unter Angabe des Namens des Mitglieds auf folgendes Konto:</span><? 
			}
			else
			{
				?><span>Please remit the membership fee </span>
				<span>to the following bank account </span>
				<span>(please indicate the name of the member):</span><?
			}		
			?></p>
			<p>HypoVereinsbank München</br>
			Kunstverein München e.V.</br>
			Account number: 580 470 1313</br>
			Bank code: 700 202 70</br>
			IBAN: DE 6470 0202 7058 0470 1313</br>
			BIC: HYVEDEMMXXX</br><br><?
			if($lang == "en")
			{
			?>Please note that the membership will be renewed annually.<?
			}
			else
			{
			?>Bitte beachten Sie, dass sich die Mitgliedschaft jedes Jahr automatisch erneuert.<?
			}
		?></div>
	</div><?
	if($lang == "de") {
	?><input type="submit" value="Antrag auf Mitgliedschaft abschicken"><?
	} else {
	?><input type="submit" value="Send application for membership"><?
	}
	?>
	<input name='recipient' type='hidden' value='<? echo $r; ?>'>
	<input name='action' type='hidden' value='process'>
</form>
</div><?
}
else
{
?><div id="form-container" class="text"><?
if($errors)
{
?><div class="error"><? 
	foreach($errors as $e)
		echo $e; 
?></div><? 
}
if($lang == "de")
{ 
?><div>Antrag auf Mitgliedschaft</div><?
}
else
{ 
?><div>Membership Application</div><?
}
?><form id="membership" method="post" action="<? echo $form[$lang]; ?>" accept-charset="utf-8" class="small">
	<div>
		<div>
			<p><input type="radio" name="recipient" value="own"><?
			if($lang == "de") {
			?>Ich möchte Mitglied im Kunstverein München e.V. werden.</p><?
			} else {
			?>I would like to become a member of Kunstverein München e.V.</p><?
			} 
		?></div>
		<div>
			<p><input type="radio" name="recipient" value="gift"><?
			if($lang == "de") {
			?>Ich möchte eine Jahresmitgliedschaft des Kunstverein München e.V. verschenken.</span><?
			} else {
			?>I would like to give an annual membership at Kunstverein München e.V. </span>
			<span>as a gift.</span><? } ?>
		</div>
	</div><?
	if($lang == "de")
	{
	?><input type="submit" value="Weiter"><?
	}
	else
	{
	?><input type="submit" value="Continue"><?
	}
	?><input name='action' type='hidden' value='type-submitted'>
</form>
</div><?
}
?>
