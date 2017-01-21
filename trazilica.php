<?php
session_start();
$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
//$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");


$veza->exec("set names utf8");
//$xml = simplexml_load_file("fan-klub.xml");

echo "<div class='table'>";


if (!isset($_GET["pretraga"]) || ($_GET["pretraga"] == ""))
{
	
}
else
{
	//$clanovi = $xml->record;
	$pretraga = "%" . strtolower($_GET["pretraga"]) . "%";
	if (!isset($_REQUEST["sve"]))
	{
		$upit = $veza->prepare("SELECT * FROM fanklub WHERE ((ime LIKE ?) OR (prezime LIKE ?)) LIMIT 10;");
	}
	else
	{
		$upit = $veza->prepare("SELECT * FROM fanklub WHERE ((ime LIKE ?) OR (prezime LIKE ?));");
	}

	$upit->bindValue(1, $pretraga, PDO::PARAM_STR);
	$upit->bindValue(2, $pretraga, PDO::PARAM_STR);
	$upit->execute();
	$clanovi = $upit->fetchAll();
	$rezultat = "";
	$broj_pronadjenih = 0;

	print "<span>Rezultati: ime, prezime, email, telefon:</span><br>";

	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{
		foreach ($clanovi as $clan)
		{
				$rezultat = $rezultat . "<div class='tr'>
											<form method='POST' action='fanklub.php' onsubmit=\"return submitForm(this);\">
												<input type='hidden' name='edit' value='$clan[id]'>
												<div class='td'><input type='text' name='ime' value='".$clan["ime"]."'></div>
												<div class='td'><input type='text' name='prezime' value='".$clan["prezime"]."'></div>
												<div class='td'><input type='email' name='email' value='".$clan["email"]."'></div>
												<div class='td'><input type='text' name='telefon' value='".$clan["telefon"]."'></div>
												<div class='td'><input type='submit' value='Sačuvaj'></div>
											</form>
											<div class='td'>
											<form method='POST' action='fanklub.php' onsubmit=\"return submitForm(this);\">
												<input type='hidden' name='brisi' value='$clan[id]'>
												<input type='submit' value='Briši'>

											</form>
											</div>
										</div>";
			}
		}
	else
	{
		foreach ($clanovi as $clan)
		{
				$rezultat = $rezultat . "<div class='tr'>
											<div class='td'>$clan[ime]</div>
											<div class='td'>$clan[prezime]</div>
											<div class='td'>$clan[email]</div>
											<div class='td'>$clan[telefon]</div>
										</div>";

		}
	}

	echo $rezultat;
	echo "</div>";
}
?>