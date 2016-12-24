<div class="kolona cetri red glavni-sadrzaj">
<div class="red">
	<div class="kolona jedan centrirano">
		<input type="button" value="Prethodno kolo">
	</div>
	<div class="kolona dva centrirano">
		<span>14. kolo</span>
	</div>
	<div class="kolona jedan centrirano">
		<input type="button" value="Naredno kolo">
	</div>
</div>
<div class="red">
<div class="kolona cetri centrirano">
	<table>
	<tr>
		<td>Široki Brijeg</td>
		<td>-</td>
		<td>Željezničar</td>
		<td>1:0</td>
	</tr>
	<tr>
		<td>Sarajevo</td>
		<td>-</td>
		<td>Čelik</td>
		<td>4:0</td>
	</tr>
	<tr>
		<td>Radnik</td>
		<td>-</td>
		<td>Krupa</td>
		<td>3:1</td>
	</tr>
	<tr>
		<td>Vitez</td>
		<td>-</td>
		<td>Sloboda</td>
		<td>0:0</td>
	</tr>
	<tr>
		<td>Olimpic</td>
		<td>-</td>
		<td>Zrinjski</td>
		<td>0:5</td>
	</tr>
	<tr>
		<td>Metalleghe</td>
		<td>-</td>
		<td>Mladost</td>
		<td>2:0</td>
	</tr>
	</table>
</div>
</div>
<div class="red">

<?php
function idcmp($t1, $t2) {
	return ($t1->attributes()["id"]) < ((int)$t2->attributes()["id"]);
}

function cmp($t1, $t2) {
	return ((int) $t1->bodovi) < ((int)$t2->bodovi);
}

function cmp_strijelci($t1, $t2) {
	return ((int) $t1->golovi) < ((int)$t2->golovi);
}

session_start();
$xml= simplexml_load_file("podaci.xml");

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["edit-tabela"]))
	{
		$nadjeni;
		$trazeni = $_REQUEST["edit-tabela"];

		foreach($xml->tabela->klub as $klub)
		{
			if ($klub->attributes()['id'] == $trazeni)
			{
				$klub->naziv = $_REQUEST["naziv"];
				$klub->bodovi = $_REQUEST["bodovi"];
				$xml->asXml("podaci.xml");

				break;
			}
		}
	}

	if (isset($_REQUEST["edit-strijelci"]))
	{
		$nadjeni;
		$trazeni = $_REQUEST["edit-strijelci"];

		foreach($xml->strijelci->igrac as $igrac)
		{
			if ($igrac->attributes()['id'] == $trazeni)
			{
				$igrac->ime = $_REQUEST["ime"];
				$igrac->tim = $_REQUEST["tim"];
				$igrac->golovi = $_REQUEST["golovi"];
				$xml->asXml("podaci.xml");

				break;
			}
		}
	}

	if (isset($_REQUEST["brisi-tabela"]))
	{
		$tabela=$xml->xpath('//klub[@id="'.$_REQUEST["brisi-tabela"].'"]');

		unset($tabela[0][0]);
		$xml->asXml("podaci.xml");
	}

	if (isset($_REQUEST["brisi-strijelci"]))
	{
		$strijelci=$xml->xpath('//igrac[@id="'.$_REQUEST["brisi-strijelci"].'"]');

		unset($strijelci[0][0]);
		$xml->asXml("podaci.xml");
	}

	if (isset($_REQUEST["dodaj-tabela"]))
	{
		$idtabela = $xml->xpath('/podaci/tabela/klub');
		usort($idtabela, "idcmp");
		$idtabela[0];
		$novi = $xml->tabela->addChild("klub", "");
		$novi->addAttribute("id", intval($idtabela[0]->attributes()["id"]) + 1);
		$novi->addChild("naziv", $_REQUEST["naziv"]);
		$novi->addChild("bodovi", $_REQUEST["bodovi"]);

		$xml->asXml("podaci.xml");
	}

	if (isset($_REQUEST["dodaj-strijelci"]))
	{
		$idtabela = $xml->xpath('/podaci/strijelci/igrac');
		usort($idtabela, "idcmp");
		$idtabela[0];
		$novi = $xml->strijelci->addChild("igrac", "");
		$novi->addAttribute("id", intval($idtabela[0]->attributes()["id"]) + 1);
		$novi->addChild("ime", $_REQUEST["ime"]);
		$novi->addChild("tim", $_REQUEST["tim"]);
		$novi->addChild("golovi", $_REQUEST["golovi"]);

		$xml->asXml("podaci.xml");
	}
}

$tabela = $xml->xpath('/podaci/tabela/klub');
$strijelci = $xml->xpath('/podaci/strijelci/igrac');

usort($tabela, 'cmp');
usort($strijelci, 'cmp_strijelci');

print "<div class=\"kolona dva glavni-sadrzaj\">";
print "<p>Trenutna tabela lige:</p>";

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	foreach ($tabela as $mjesto=>$klub) {
		print "<div class='red'>
		<form method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='text' name='naziv' value='$klub->naziv'>
		<input type='text' name='bodovi' value='$klub->bodovi'>
		<input type='hidden' name='edit-tabela' value='".$klub->attributes()["id"]."'>
		<input type='submit' value='Sačuvaj'>
		</form>

		<form method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='hidden' name='brisi-tabela' value='".$klub->attributes()["id"]."'>
		<input type='submit' value='Izbriši'>
		</form>
		
		</div>";
	}

	print "<div class='red'>
	<form method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
	<input type='text' name='naziv'>
	<input type='text' name='bodovi'>
	<input type='hidden' name='dodaj-tabela'>
	<input type='submit' value='Dodaj klub'>
	</form>
	</div>";
}

print "<table>";
print "<tr>";
print "<th>#</th>";
print "<th>Klub</th>";
print "<th>B</th>";
print "</tr>";

foreach ($tabela as $mjesto=>$klub) {
	print "<tr>";
	print "<td>". ($mjesto + 1) ."</td>";
	print "<td>$klub->naziv</td>";
	print "<td>$klub->bodovi</td>";
	print "</tr>";
}

print "</table>";
print "</div>";

print "<div class=\"kolona dva glavni-sadrzaj\">";
print "<p>Lista najboljih strijelaca:</p>";

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	foreach ($strijelci as $mjesto=>$igrac) {
		print "<div class='red'>
		<form method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='text' name='ime' value='$igrac->ime'>
		<input type='text' name='tim' value='$igrac->tim'>
		<input type='text' name='golovi' value='$igrac->golovi'>
		<input type='hidden' name='edit-strijelci' value='".$igrac->attributes()["id"]."'>
		<input type='submit' value='Sačuvaj'>
		</form>

		<form method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='hidden' name='brisi-strijelci' value='".$igrac->attributes()["id"]."'>
		<input type='submit' value='Izbriši'>
		</form>
		
		</div>";
	}

	print "<div class='red'>
	<form method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
	<input type='text' name='ime'>
	<input type='text' name='tim'>
	<input type='text' name='golovi'>
	<input type='hidden' name='dodaj-strijelci'>
	<input type='submit' value='Dodaj igrača'>
	</form>
	</div>";
}


print "<table>";
print "<tr>";
print "<th>#</th>";
print "<th>Igrač</th>";
print "<th>Tim</th>";
print "<th>Golovi</th>";
print "</tr>";

foreach ($strijelci as $mjesto=>$strijelac) {
	print "<tr>";
	print "<td>".($mjesto + 1)."</td>";
	print "<td>$strijelac->ime</td>";
	print "<td>$strijelac->tim</td>";
	print "<td>$strijelac->golovi</td>";
	print "</tr>";
}
print "</table>";
print "</div>";
?>
</div>
</div>