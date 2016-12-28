<?php
session_start();
$greska = 0;
$greska_string = "";

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["dodavanje"]))
	{
		if(!preg_match('/^[a-zA-Z.,!]{5,30}$/', $_REQUEST["naslov"]))
		{
			$greska = 1;
			$greska_string = "Nije validan unos. Naslov može sadržavati samo 5-30 alfanumeričkih znakova, razmaka i znakova interpunkcija.";
		}
		elseif(!preg_match('/^[a-zA-Z.,!]{30,500}$/', $_REQUEST["tekst-vijesti"]))
		{
			$greska = 1;
			$greska_string = "Nije validan unos. Tekst može sadržavati samo 30-500 alfanumeričkih znakova, razmaka i znakova interpunkcija.";
		}
		else
		{
			$xml= simplexml_load_file("podaci.xml");
			$sortirani = $xml->xpath('/podaci/vijesti/vijest');

			function cmp($t1, $t2) {
	    		return intval($t1->attributes()["id"]) > intval($t2->attributes()["id"]);
			}

			usort($sortirani, 'cmp');

			$noviId = $sortirani[0]->attributes()["id"] + 1;

			$novi = $xml->vijesti->addChild("vijest", "");
			$novi->addAttribute("id", $noviId);
			$novi->addChild("naslov", $_REQUEST["naslov"]);
			$novi->addChild("tekst", $_REQUEST["tekst-vijesti"]);
			$novi->addChild("autor", $_SESSION["username"]);

			$xml->asXml("podaci.xml");
		}
	}

	if (isset($_REQUEST["edit"]))
	{
		if(!preg_match('/^[a-zA-Z.,!]{5,30}$/', $_REQUEST["naslov"]))
		{
			echo "uso1";
			$greska = 2;
			$greska_string = "Nije validan unos. Naslov može sadržavati samo 5-30 alfanumeričkih znakova, razmaka i znakova interpunkcija.";
		}
		elseif(!preg_match('/^[a-zA-Z.,!]{30,500}$/', $_REQUEST["tekst-vijesti"]))
		{
			echo "uso2";
			$greska = 2;
			$greska_string = "Nije validan unos. Tekst može sadržavati samo 5-30 alfanumeričkih znakova, razmaka i znakova interpunkcija.";
		}
		else
		{
			echo "uso3";
			$xml= simplexml_load_file("podaci.xml");
			$nadjeni;
			$trazeni = $_REQUEST["vijestId"];

			foreach($xml->vijesti->vijest as $vijest)
			{
				if ($vijest->attributes()["id"] == $trazeni)
				{
					$vijest->tekst = $_REQUEST["tekst-vijesti"];
					$vijest->naslov = $_REQUEST["naslov"];
					$xml->asXml("podaci.xml");

					break;
				}
			}
		}
	}

	if (isset($_REQUEST["brisi"]))
	{
		$xml= simplexml_load_file("podaci.xml");
		$vijesti=$xml->xpath('//vijest[@id="'.$_REQUEST["brisi"].'"]');

		unset($vijesti[0][0]);
		$xml->asXml("podaci.xml");
	}

}

print "<div class=\"kolona tri glavni-sadrzaj\">";
$xml= simplexml_load_file("podaci.xml");


$vijesti = $xml->vijesti->vijest;
$tabela = $xml->tabela->klub;
print "<div><span id='edit-greska' class='greska'>";
if ($greska == 2)
{
	print $greska_string;
}
print "</span></div>";

foreach($vijesti as $vijest)
{	
	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{


		print	"<form  method='POST' action='vijesti.php' onsubmit=\"return provjeriFormu(this) && submitForm(this);\">
				<div class='red'><label for='naslov'>Naslov: </label>
				<input type='text' name='naslov' value='$vijest->naslov'></div>
				<div class='red'><textarea class='siroki-text' name='tekst-vijesti'>$vijest->tekst</textarea></div>
				<input type='hidden' name='vijestId' value='".$vijest->attributes()["id"]."'>
				<input type='hidden' name='edit'>
				<input hidden='".$vijest->attributes()["id"]."'>
				<div class='red'><input type='submit' value='Sačuvaj'></div>
			</form>";
	}
	else
	{
			print "<article>
				<h2>$vijest->naslov</h2>";
	print "<p>$vijest->tekst</p>
			</article>";

	}

	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{
		print "<form class='brisanje mala-forma' method='POST' action='vijesti.php' onsubmit=\"return submitForm(this);\">
								<input type='hidden' name='brisi' value='".$vijest->attributes()["id"]."'>
								<input type='submit' value='Briši'>
								</form>";
	}
				

}

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<form class='izdvojena-forma' id='pisanje-vijesti' method='POST' action='vijesti.php' onsubmit='return provjeriFormu(this) && submitForm(this);'>
	<div class='red'>
	<div class='kolona jedan'><label for='naslov'>Naslov: </label></div>
	<div class='kolona tri'><input type='text' name='naslov'></div>
	</div>
	<div class='red'>
	<div class='kolona cetri'>
	<textarea name='tekst-vijesti'></textarea>
	</div>
	</div>
	<div class='red'>
	<span class='greska' id='nova-vijest-greska'>";
	if ($greska == 1)
	{
		print $greska_string;
	}
	print "</span>
	</div>
	<div class='red'>
	<div class='kolona tri'></div>
	<div class='kolona jedan'><input type='submit' value='dodaj'></div>
	<input type='hidden' name='dodavanje'>
	<input type='hidden' name='autor' value='$_SESSION[username]'>
	</div>
	</form>";

}

print "</div>";

print "<div class=\"kolona jedan strana\">
	<div class=\"info\">
	<p>Trenutno stanje tabele:</p>
	<table id=\"tabela\">
	<tr>
	<th>#</th>
	<th></th>
	<th>B</th>
	</tr>";
	
foreach ($tabela as $klub) {
	print "<tr>
	<td>$klub->mjesto</td>
	<td>$klub->naziv</td>
	<td>$klub->bodovi</td>
	</tr>";
}

print "</table>
	</div>";



print "<script src='edit-vijesti.js'></script>";