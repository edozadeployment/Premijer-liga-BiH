<?php
	$xml = simplexml_load_file("fan-klub.xml");

	echo "<table>";
	echo "<tr>
		<th>Id</th>
		<th>Ime</th>
		<th>Prezime</th>
		<th>Email</th>
		<th>Broj telefona</th>
		</tr>";


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

		foreach ($clanovi as $clan)
		{
			if ((substr_count(strtolower($clan->ime), $pretraga) != 0) || (substr_count(strtolower($clan->prezime), $pretraga) != 0))
			{
				$rezultat = $rezultat . "<tr>
											<td>$clan->id</td>
											<td>$clan->ime</td>
											<td>$clan->prezime</td>
											<td>$clan->email</td>
											<td>$clan->telefon</td>
										</tr>";

				$broj_pronadjenih++;

				if ($broj_pronadjenih == 10)
				{
					break;
				}
			}
		}
	
	echo $rezultat;
	echo "</table>";
	}
?>