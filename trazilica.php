<?php
	session_start();
	$xml = simplexml_load_file("fan-klub.xml");

	echo "<div class='table'>";
	/*echo "<div class='tr'>
		<div class='th'>Ime</div>
		<div class='th'>Prezime</div>
		<div class='th'>Email</div>
		<div class='th'>Broj telefona</div>
		</div>";*/


	if (!isset($_GET["pretraga"]) || ($_GET["pretraga"] == ""))
	{
		echo "";
	}
	else
	{
		$clanovi = $xml->record;
		$pretraga = strtolower($_GET["pretraga"]);
		$rezultat = "";
		$broj_pronadjenih = 0;

		print "<span>Rezultati: ime, prezime, email, telefon:</span><br>";

		if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
		{
			foreach ($clanovi as $clan)
			{
				if ((substr_count(strtolower($clan->ime), $pretraga) != 0) || (substr_count(strtolower($clan->prezime), $pretraga) != 0))
				{
					$rezultat = $rezultat . "<div class='tr'>
												<form method='POST' action='fanklub.php' onsubmit=\"return submitForm(this);\">
													<input type='hidden' name='edit' value='$clan->id'>
													<div class='td'><input type='text' name='ime' value='".$clan->ime."'></div>
													<div class='td'><input type='text' name='prezime' value='".$clan->prezime."'></div>
													<div class='td'><input type='email' name='email' value='".$clan->email."'></div>
													<div class='td'><input type='text' name='telefon' value='".$clan->telefon."'></div>
													<div class='td'><input type='submit' value='Sačuvaj'></div>
												</form>
												<div class='td'>
												<form method='POST' action='fanklub.php' onsubmit=\"return submitForm(this);\">
													<input type='hidden' name='brisi' value='$clan->id'>
													<input type='submit' value='Briši'>

												</form>
												</div>
											</div>";

					$broj_pronadjenih++;

					if (!isset($_REQUEST["sve"]) && $broj_pronadjenih == 10)
					{
						break;
					}
				}
			}
		}
		else
		{
			foreach ($clanovi as $clan)
			{
				if ((substr_count(strtolower($clan->ime), $pretraga) != 0) || (substr_count(strtolower($clan->prezime), $pretraga) != 0))
				{
					$rezultat = $rezultat . "<div class='tr'>
												<div class='td'>$clan->ime</div>
												<div class='td'>$clan->prezime</div>
												<div class='td'>$clan->email</div>
												<div class='td'>$clan->telefon</div>
											</div>";

					$broj_pronadjenih++;

					if (!isset($_REQUEST["sve"]) && $broj_pronadjenih == 10)
					{
						break;
					}
				}
			}
		}

	
	echo $rezultat;
	echo "</div>";
	}
?>