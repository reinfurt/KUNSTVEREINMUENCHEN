<?php
$pageName = basename(__FILE__, ".php");
$showMenu = TRUE;
require_once('GLOBAL/head.php');
require_once('GLOBAL/member-helper.php');
require_once('_Library/systemEmail.php');


$action = $_REQUEST['action'];
$errors = NULL;

$r = $_POST["recipient"];
$gr = $_POST["giftrecipient"];
$t = $_POST["type"];

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

if($action == "process")
{
	if(empty($addr["email"]["r"]))
	{
		if($lang == "de")
			$errors["email"] = "E-Mail darf nicht leer sein.";
		else
			$errors["email"] = "Email address must not be empty.";
	}
	elseif(!isValidEmail($addr["email"]["r"]))
	{
		if($lang == "de")
			$errors["email"] = "Keine gültige E-Mail-Adresse";
		else
			$errors["email"] = "Not a valid email address.";
	}
}

if($action == 'process' && empty($errors))
{
	$to_km["sender"] = $addr["email"]["r"];
	$to_km["recipient"] = $km_email;
	$to_km["subject"] = "Neuer Mitgliedschaftsantrag via Website";
	$to_km["body"] = email_to_km();
	
	$to_member["sender"] = $km_email;
	$to_member["recipient"] = $addr["email"]["r"];
	if($lang == "de")
		$to_member["subject"] = "Ihr Mitgliedsantrag";
	else
		$to_member["subject"] = "Your Membership Application";
	$to_member["body"] = file_get_contents("GLOBAL/email_to_member_".$lang.".txt");
	
	$status = "";
	try
	{
		// email to kunstverein	
		systemEmail($to_km["sender"], 
					$to_km["recipient"], 
					$to_km["subject"], 
					$to_km["body"]);
		// email to applicant
		systemEmail($to_member["sender"], 
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
?><form id="membership" method="post" action="member.php" accept-charset="utf-8" class="small">
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
			?>Ich möchte eine Jahresmitgliedschaft des Kunstverein München e.V. zu schenken.</span><?
			} else {
			?>I would like to give an annual membership at Kunstverein München e.V. </span>
			<span>as a gift.</span><? } ?>
		</div>
	</div>
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
		?><div>
			<input type="radio" id="type_<? echo $k; ?>" name="type" value="<? echo $k; ?>">
			<label for="type_<? echo $p; ?>"><? echo $l; ?></label>
			<span id="price<? echo $k; ?>">(from € <? echo $p; ?>)</span>
		</div><? 
		}
	?></div>
	<div><?
		if($lang == "de") {
		?><p>Mitgliedsadresse</p><? 
		} else {
		?><p>Member Address</p><?
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
	</div>
	<div><?
		if($lang == "de")
		{
		?><p>Rechnungsadresse (falls abweichend von der Mitgliedsadresse)</p><?
		}
		else
		{
		?><p>Billing Address (if different from above)</p><?
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
	</div>
	<div><?
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
		?></table>
	</div>
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
			BIC: HYVEDEMMXXX</br>
		</div>
	</div><?
	if($lang == "de") {
	?><input type="submit" value="Antrag auf Mitgliedschaft abschicken"><?
	} else {
	?><input type="submit" value="Send application for membership"><?
	}
	?><input name='action' type='hidden' value='process'>
</form>
</div><?
}
require_once('GLOBAL/foot.php');
?>