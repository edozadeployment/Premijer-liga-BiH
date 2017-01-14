<?php
function cmpbodovi($t1, $t2) {
	return ((int) $t1->bodovi) < ((int)$t2->bodovi);
}

session_start();
$greska = 0;
$greska_string = "";
$uspjeh = 0;
$uspjeh_string = "";

//$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");
$veza->exec("set names utf8");


if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["dodavanje"]) && isset($_REQUEST["naslov"]) && isset($_REQUEST["tekst-vijesti"]))
	{
		if(sizeof($_REQUEST["naslov"]) < 5 && sizeof($_REQUEST["naslov"]) > 60)
		{
			$greska = 1;
			$uspjeh = 0;
			$greska_string = "Nije validan unos. Naslov mora sadržavati samo 5-60 karaktera.";
		}
		elseif(sizeof($_REQUEST["tekst-vijesti"]) < 30 && sizeof($_REQUEST["tekst-vijesti"]) > 800)
		{
			$uspjeh = 0;
			$greska = 1;
			$greska_string = "Nije validan unos. Tekst mora sadržavati 30-800 karaktera.";
		}
		else
		{
			$naslov = $_REQUEST["naslov"];
			$tekst = $_REQUEST["tekst-vijesti"];
			$upit = $veza->prepare("INSERT INTO vijesti (naslov, tekst) VALUES (?, ?);");
			$upit->bindValue(1, $naslov, PDO::PARAM_STR);
			$upit->bindValue(2, $tekst, PDO::PARAM_STR);
			$rezultat = $upit->execute();

			if (!$rezultat) {
				$greska = $veza->errorInfo();
				$uspjeh = 0;
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
		    else
		    {
				$uspjeh = 1;
				$uspjeh_string = "Uspješno dodana vijest";
		    }
		}
	}

	if (isset($_REQUEST["edit"]) && isset($_REQUEST["naslov"]) && isset($_REQUEST["tekst-vijesti"]))
	{
		if(sizeof($_REQUEST["naslov"]) < 5 && sizeof($_REQUEST["naslov"]) > 60)
		{
			$uspjeh = 0;
			$greska = 2;
			$greska_string = "Nije validan unos. Naslov mora sadržavati samo 5-60 karaktera.";
		}
		elseif(sizeof($_REQUEST["tekst-vijesti"]) < 30 && sizeof($_REQUEST["tekst-vijesti"]) > 800)
		{
			$uspjeh = 0;
			$greska = 2;
			$greska_string = "Nije validan unos. Tekst mora sadržavati 30-800 karaktera.";
		}
		else
		{
			$id = $_REQUEST["vijestId"];
			$naslov = $_REQUEST["naslov"];
			$tekst = $_REQUEST["tekst-vijesti"];
			$upit = $veza->prepare("UPDATE vijesti SET naslov=?, tekst=? WHERE id=?;");
			$upit->bindValue(1, $naslov, PDO::PARAM_STR);
			$upit->bindValue(2, $tekst, PDO::PARAM_STR);
			$upit->bindValue(3, $id, PDO::PARAM_INT);

			$rezultat = $upit->execute();


			if (!$rezultat) {
				$greska_info = $veza->errorInfo();
				$uspjeh = 0;
		  		print "SQL greška: " . $greska_info[2];
		        exit();
		    }
		    else
		    {
				$uspjeh = 2;
				$greska = 0;
				$uspjeh_string = "Uspješno editovana vijest";
		    }
		}
	}

	if (isset($_REQUEST["brisi"]))
	{
		$id = $_REQUEST["brisi"];
		$upit = $veza->prepare("DELETE FROM vijesti WHERE id=?;");
		$upit->bindValue(1, $id, PDO::PARAM_INT);
		$rezultat = $upit->execute();

		if (!$rezultat) {
			$greska_info = $veza->errorInfo();
			$uspjeh = 0;
	        print "SQL greška: " . $greska_info[2];
	        exit();
	    }
	    else
	    {
			$uspjeh = 2;
			$greska = 0;
			$uspjeh_string = "Uspješno izbrisana vijest";
	    }
	}
}

$upit = $veza->prepare("SELECT id, naslov, tekst FROM vijesti;");
$upit->execute();
$vijesti = $upit->fetchAll();

print "<div class=\"kolona tri glavni-sadrzaj\">";

print "<div><span id='edit-greska' class='greska'>";
if ($greska == 2)
{
	print $greska_string;
}
print "</span></div>";

print "<div><span class='uspjeh'>";
if ($uspjeh == 2)
{
	print $uspjeh_string;
}
print "</span></div>";


foreach($vijesti as $vijest)
{	
	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{

		print	"<form  method='POST' action='vijesti.php' onsubmit=\"return provjeriFormu(this) && submitForm(this);\">
				<div class='red'><label for='naslov'>Naslov: </label>
				<input type='text' name='naslov' value='$vijest[naslov]'></div>
				<div class='red'><textarea class='siroki-text' name='tekst-vijesti'>$vijest[tekst]</textarea></div>
				<input type='hidden' name='vijestId' value='".$vijest["id"]."'>
				<input type='hidden' name='edit'>
				<input hidden='".$vijest["id"]."'>
				<div class='red'><input type='submit' value='Sačuvaj'></div>
			</form>";
	}
	else
	{
			print "<article>
				<h2>" . htmlspecialchars($vijest["naslov"]) . "</h2>";
	print "<p>" . htmlspecialchars($vijest["tekst"]) . "</p>
			</article>";

	}

	if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
	{
		print "<form class='brisanje mala-forma' method='POST' action='vijesti.php' onsubmit=\"return submitForm(this);\">
								<input type='hidden' name='brisi' value='".$vijest["id"]."'>
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

<span class='uspjeh'>";
	if ($uspjeh == 1)
	{
		print $uspjeh_string;
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


$upit = $veza->prepare("SELECT naziv, bodovi FROM tabela ORDER BY bodovi DESC;");
$uspjeh = $upit->execute();

if (!$uspjeh)
{
	$greska_info = $veza->errorInfo();
 	print "Greška baze podataka: " . $greska_info[2];
 	exit();
}

$tabela = $upit->fetchAll();

foreach ($tabela as $mjesto=>$klub) {
	print "<tr>
	<td>". ($mjesto + 1) . "</td>
	<td>$klub[naziv]</td>
	<td>$klub[bodovi]</td>
	</tr>";
}

print "</table>
	</div>";



print "<script src='edit-vijesti.js'></script>";