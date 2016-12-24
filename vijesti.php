<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["dodavanje"]))
	{
		$xml= simplexml_load_file("podaci.xml");
		$sortirani = $xml->xpath('/podaci/vijesti/vijest');

		function cmp($t1, $t2) {
    		return $t1->attributes()["id"] > $t2->attributes()["id"];
		}

		usort($sortirani, 'cmp');

		$noviId = $sortirani[0]->attributes()["id"] + 1;

		$novi = $xml->vijesti->addChild("vijest", "");
		$novi->addAttribute("id", $noviId);
		$novi->addChild("naslov", $_REQUEST["naslov"]);
		$novi->addChild("tekst", $_REQUEST["tekst"]);
		$novi->addChild("autor", $_REQUEST["autor"]);

		$xml->asXml("podaci.xml");
	}

	if (isset($_REQUEST["edit"]))
	{
		$xml= simplexml_load_file("podaci.xml");
		$nadjeni;
		$trazeni = $_REQUEST["vijestId"];

		foreach($xml->vijesti->vijest as $vijest)
		{
			if ($vijest->attributes()["id"] == $trazeni)
			{
				$vijest->tekst = $_REQUEST["tekst"];
				$vijest->naslov = $_REQUEST["naslov"];
				$xml->asXml("podaci.xml");

				break;
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

foreach($vijesti as $vijest)
{	
	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{


		print	"<form  method='POST' action='vijesti.php' onsubmit=\"return submitForm(this);\">
				<input type='text' name='naslov' value='$vijest->naslov'>
				<textarea name='tekst'>$vijest->tekst</textarea>
				<input type='hidden' name='vijestId' value='".$vijest->attributes()["id"]."'>
				<input type='hidden' name='edit'>
				<input hidden='".$vijest->attributes()["id"]."'>
				<input type='submit' value='Sačuvaj'>
			</form>";
	}
	
	print "<article>
				<h2>$vijest->naslov</h2>";
	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{
		print "<a href=\"#\">Edit</a>
				<form class='brisanje' method='POST' action='vijesti.php' onsubmit=\"return submitForm(this);\">
								<input type='hidden' name='brisi' value='".$vijest->attributes()["id"]."'>
								<input type='submit' value='Briši'>
								</form>";
	}
				
	print "<p>$vijest->tekst</p>
			</article>";

}

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<form class='izdvojena-forma' id='pisanje-vijesti' method='POST' action='vijesti.php' onsubmit=\"return submitForm(this);\">
	<div class='red'>
	<div class='kolona jedan'><label for='naslov'>Naslov: </label></div>
	<div class='kolona tri'><input type='text' name='naslov'></div>
	</div>
	<div class='red'>
	<div class='kolona cetri'>
	<textarea name='tekst'></textarea>
	</div>
	</div>
	<div class='red'>
	<div class='kolona tri'></div>
	<div class='kolona jedan'><input type='submit' value='dodaj'></div>
	<input type='hidden' name='dodavanje'>
	<input type='hidden' name='autor' value='$_SESSION[username]'>
	</form>
	</div>";
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