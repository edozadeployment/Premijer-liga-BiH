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
$greska = 0;
$greska_string = "";

function idcmp($t1, $t2) {
	return intval($t1->attributes()["id"]) < intval($t2->attributes()["id"]);
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
		if(!preg_match('/^[a-zA-Z0-9. ]{2,20}$/', $_REQUEST["naziv"]))
		{
			$greska = 1;
			$greska_string = "Naziv kluba može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0|0)$/', $_REQUEST["bodovi"])) {
			$greska = 1;
			$greska_string = "Bodovi moraju biti broj 0-99.";
		}
		else
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
	}

	elseif (isset($_REQUEST["edit-strijelci"]))
	{
		if(!isset($_REQUEST["ime"]) || !preg_match('/^[a-zA-Z ]{2,25}$/', $_REQUEST["ime"]))
		{
			$greska = 2;
			$greska_string = "Ime mora biti niz slova (+ razmaci) u dužini od 2 do 25 karaktera.";
		}
		elseif(!isset($_REQUEST["tim"]) || !preg_match('/^[a-zA-Z0-9. ]{2,20}$/', $_REQUEST["ime"]))
		{
			$greska = 2;
			$greska_string = "Naziv kluba može sadržavati samo slova i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0|0)$/', $_REQUEST["golovi"])) {
			$greska = 2;
			$greska_string = "Golovi moraju biti broj 0-99.";
		}
		else
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
	}

	elseif (isset($_REQUEST["brisi-tabela"]))
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
		if(!preg_match('/^[a-zA-Z. ]{2,20}$/', $_REQUEST["naziv"]))
		{
			$greska = 1;
			$greska_string = "Naziv kluba može sadržavati samo slova i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["bodovi"])) {
			$greska = 1;
			$greska_string = "Bodovi moraju biti broj 0-99.";
		}
		else
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
	}

	if (isset($_REQUEST["dodaj-strijelci"]))
	{

		if(!isset($_REQUEST["ime"]) || !preg_match('/^[a-zA-Z ]{2,25}$/', $_REQUEST["ime"]))
		{
			$greska = 2;
			$greska_string = "Ime mora biti niz slova (+ razmaci) u dužini od 2 do 25 karaktera.";
		}
		elseif(!isset($_REQUEST["tim"]) || !preg_match('/^[a-zA-Z0-9. ]{2,20}$/', $_REQUEST["ime"]))
		{
			$greska = 2;
			$greska_string = "Naziv kluba može sadržavati samo slova i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["golovi"])) {
			$greska = 2;
			$greska_string = "Golovi moraju biti broj 0-99.";
		}
		else
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
}

$tabela = $xml->xpath('/podaci/tabela/klub');
$strijelci = $xml->xpath('/podaci/strijelci/igrac');

usort($tabela, 'cmp');
usort($strijelci, 'cmp_strijelci');

print "<div class=\"kolona dva glavni-sadrzaj\">";
print "<p>Trenutna tabela lige (klub, golovi):</p>";
print "<div class='red'><span class='greska' id='novi-klub-greska'>";
if ($greska == 1)
{
	print $greska_string;
}
print "</div>";

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<div class='tabela'>";
/*	print "<div class='tr'>";
	print "<div class='th'>Klub</div>";
	print "<div class='th'>Bodovi</div>";
	print "<div class='th'></div>";
	print "<div class='th'></div>";
	print "</div>";*/

	foreach ($tabela as $mjesto=>$klub) {
		//print "<div class='red'>
		print "<div class='tr'><form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return validacijaKluba(this, 'novi-klub-greska') && submitForm(this);\">
		<div class='td'><input type='text' name='naziv' value='$klub->naziv'></div>
		<div class='td'><input type='number' name='bodovi' min='0' max='99' value='$klub->bodovi'></div>
		<input type='hidden' name='edit-tabela' value='".$klub->attributes()["id"]."'>
		<div class='td'><input type='submit' value='Sačuvaj'></div>
		</form>
		<div class='td'>
		<form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='hidden' name='brisi-tabela' value='".$klub->attributes()["id"]."'>
		<input type='submit' value='Izbriši'>
		</form></div></div>";
	}
	print "<div class='tr'>
		<form method='POST' action='statistika.php' onsubmit=\"return validacijaKluba(this, 'novi-klub-greska') && submitForm(this);\">
		<input type='hidden' name='dodaj-tabela'>
		<div class='td'><input type='text' name='naziv'></div>
		<div class='td'><input type='number' min='0' max='99' name='bodovi'></div>
		<div class='td'><input type='submit' value='Dodaj klub'></div>
		</form>
	</div>";

	print "</div>";
}
else
{
	print "<table>";
	print "<tr>";
	print "<th>#</th>";
	print "<th>Klub</th>";
	print "<th>Bodovi</th>";
	print "</tr>";

	foreach ($tabela as $mjesto=>$klub) {
		print "<tr>";
		print "<td>". ($mjesto + 1) ."</td>";
		print "<td>$klub->naziv</td>";
		print "<td>$klub->bodovi</td>";
		print "</tr>";
	}

	print "</table>";
}

print "</div>";

print "<div class=\"kolona dva glavni-sadrzaj\">";
print "<p>Lista najboljih strijelaca (ime, tim, broj golova):</p>";
print "<div class='red'><span class='greska' id='edit-klub-greska'>";
if ($greska == 2)
{
	print $greska_string;
}
print "</div>";

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<div class='table'>";
//	print "<div class='tr'>";
//	print "<div class='th' style='width: 20%;'>Igrač</div>";
//	print "<div class='th' style='width: 20%;'>Tim</div>";
//	print "<div class='th' style='width: 20%;'>Golovi</div>";	
//	print "<div class='th'></div>";	
//	print "</div>";

	foreach ($strijelci as $mjesto=>$igrac) {
		//print "<div class='red'>
		print "<div class='tr'><form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return validacijaIgraca(this, 'edit-klub-greska') && submitForm(this);\">
		
		<div class='td'><input type='text' name='ime' value='$igrac->ime'></div>
		<div class='td'><input type='text' name='tim' value='$igrac->tim'></div>
		<div class='td'><input type='number' min='0' max='99' name='golovi' value='$igrac->golovi'></div>
		<input type='hidden' name='edit-strijelci' value='".$igrac->attributes()["id"]."'>
		<div class='td'><input type='submit' value='Sačuvaj'></div>
		</form>
		<div class='td'>
		<form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='hidden' name='brisi-strijelci' value='".$igrac->attributes()["id"]."'>
		<input type='submit' value='Izbriši'>
		</form>
		</div>
		</div>";
		//</div>";
	}

	//print "<div class='red'>
	print "<tr><form method='POST' action='statistika.php' onsubmit=\"return validacijaIgraca(this, 'edit-klub-greska') && submitForm(this);\">
	<td><input type='text' name='ime'></td>
	<td><input type='text' name='tim'></td>
	<td><input type='number' min='0' max='99' name='golovi'>
	<input type='hidden' name='dodaj-strijelci'></td>
	<td colspan='2'><input type='submit' value='Dodaj igrača'></td></tr>
	</form>
	</div>";
}

else
{
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
}

print "</div>";
?>
</div>