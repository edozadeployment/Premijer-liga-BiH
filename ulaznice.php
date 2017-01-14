<?php
function idcmp($t1, $t2) {
				return intval($t1->attributes()["id"]) < intval($t2->attributes()["id"]);
			}

$greska = 0;
$uspjeh = 0;

//$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");

$veza->exec("set names utf8");

if (isset($_REQUEST["kupovina"]))
{
	if (isset($_REQUEST["utakmica"]))
	{
		$id = $_REQUEST["utakmica"];

		$upit = $veza->prepare("SELECT tabela1.naziv AS domacin, tabela2.naziv AS gost, utakmice.cijena AS cijena FROM utakmice JOIN tabela tabela1 ON tabela1.id = utakmice.domacin JOIN tabela tabela2 ON tabela2.id = utakmice.gost WHERE utakmice.id=?;");
		$upit->bindValue(1, $id, PDO::PARAM_INT);
		$upit->execute();
		$ut = $upit->fetch(PDO::FETCH_ASSOC);
	}

	require('tfpdf/tfpdf.php');

	$pdf = new tFPDF('L', 'mm', 'A5');
	$pdf->AddPage();
	$pdf->SetFont('Helvetica','B',16);
	$pdf->Cell(40,10,'Racun');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Courier', '', 12);



	if (isset($_REQUEST["utakmica"]))
	{

		$par = ($ut["domacin"]) . " - " . ($ut["gost"]);
		
		$pdf->Cell(40,10,'Utakmica: '. $par);
		$pdf->Ln();
	}
	if (isset($_REQUEST["tribina"]))
	{
		$pdf->Cell(40,10,'Tribina: '.$_REQUEST["tribina"]);
		$pdf->Ln();
	}
	if (isset($_REQUEST["brojulaznica"]))
	{
		$pdf->Cell(40,10,'Broj ulaznica: '.$_REQUEST["brojulaznica"]);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Courier', 'B', 13);
		$pdf->Cell(40,10,'Cijena: '. $ut["cijena"] . 'KM * '.$_REQUEST["brojulaznica"].'.........'.$_REQUEST["brojulaznica"]* intval($ut["cijena"]) . 'KM');
		$pdf->Ln();
		$pdf->Ln();
	}

    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(0,10,'Racun izdat '.date("d.m.Y."));
	$pdf->Output();
}

else
{

session_start();

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["edit-utakmica"]))
	{
		if(!isset($_REQUEST["domacin"]) || !isset($_REQUEST["gost"]) || ($_REQUEST["domacin"] == $_REQUEST["gost"]))
		{
			$greska = 1;
			$greska_string = "Klub ne može igrati protiv samog sebe.";
		}
		elseif (!isset($_REQUEST["cijena"]) || !preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["cijena"])) {
			$greska = 1;
			$greska_string = "Cijena mora biti broj 1-99KM.";
		}
		else
		{
			$id = $_REQUEST["edit-utakmica"];
			$domacin = $_REQUEST["domacin"];
			$gost = $_REQUEST["gost"];
			$cijena = $_REQUEST["cijena"];

			$upit = $veza->prepare("UPDATE utakmice SET domacin=$domacin, gost=$gost, cijena=$cijena WHERE id=?;");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$rezultat = $upit->execute();

			if (!$rezultat)
			{
				$greska_info = $veza->errorInfo();
				$greska = 1;
				$uspjeh = 0;
				$greska_string = "Greška baze podataka: " . $greska_info[2];
			}
			else
			{
				$uspjeh = 1;
				$greska = 0;
				$uspjeh_string = "Uspješno editovana utakmica";
			}
		}
	}
	elseif (isset($_REQUEST["brisi-utakmica"]))
	{
		$id = $_REQUEST["brisi-utakmica"];
		$upit = $veza->prepare("DELETE FROM utakmice WHERE id=?;");
		$upit->bindValue(1, $id, PDO::PARAM_INT);

		$rezultat = $upit->execute();

		if (!$rezultat)
		{
			$greska_info = $veza->errorInfo();
			$greska = 1;
			$uspjeh = 0;
			$greska_string = "Greška baze podataka: " . $greska_info[2];
		}
		else
		{
			$greska = 0;
			$uspjeh = 1;
			$uspjeh_string = "Uspješno izbrisana utakmica.";
		}
	}
	elseif (isset($_REQUEST["dodaj-utakmica"]))
	{
		if(!isset($_REQUEST["domacin"]) || !isset($_REQUEST["gost"]) || ($_REQUEST["domacin"] == $_REQUEST["gost"]))
		{
			$greska = 1;
			$greska_string = "Klub ne može igrati protiv samog sebe.";
		}
		elseif (!isset($_REQUEST["cijena"]) || !preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["cijena"])) {
			$greska = 1;
			$greska_string = "Cijena mora biti broj 1-99KM.";
		}
		else
		{
			$id = $_REQUEST["dodaj-utakmica"];
			$domacin = $_REQUEST["domacin"];
			$gost = $_REQUEST["gost"];
			$cijena = $_REQUEST["cijena"];		
			
			$upit = $veza->prepare("INSERT INTO utakmice (domacin, gost, cijena) VALUES (?, ?, ?);");
			$upit->bindValue(1, $domacin, PDO::PARAM_INT);
			$upit->bindValue(2, $gost, PDO::PARAM_INT);
			$upit->bindValue(3, $cijena, PDO::PARAM_INT);

			$rezultat = $upit->execute();

			if (!$rezultat)
			{
				$uspjeh = 0;
				$greska_info = $veza->errorInfo();
				$greska = 1;
				$greska_string = "Greška baze podataka: " . $greska_info[2];
			}
			else
			{
				$greska = 0;
				$uspjeh = 1;
				$uspjeh_string = "Uspješno dodana utakmica.";
			}
		}
	}


}

 print "<div class='kolona cetri glavni-sadrzaj red'>
	<div class='kolona dva'>
		<p>Ovdje možete rezervisati karte za utakmice.</p>
		<p>Moguće je rezervisati do osam karata. Ako ste član fan kluba, dobijate popust na cijenu od 10%.</p>
		<div class='red'><span class='greska' id='greska'>";
	 	if ($greska == 1)
	 	{
	 		print $greska_string;
	 	}
	 	print "</span>
	 	</div>

	 	<div class='red'><span class='uspjeh'>";
	 	if ($uspjeh == 1)
	 	{
	 		print $uspjeh_string;
	 	}
	 	print "</span>
	 	</div>
	</div>
	<div class='kolona dva'>";


$upit = $veza->prepare("SELECT utakmice.id, tabela1.naziv AS domacin, tabela2.naziv AS gost, tabela1.id AS domacinid, tabela2.id AS gostid, utakmice.cijena FROM utakmice JOIN tabela tabela1 ON tabela1.id = utakmice.domacin JOIN tabela tabela2 ON tabela2.id = utakmice.gost;");
$uspjeh = $upit->execute();

$utakmice = $upit->fetchAll();

if (!$uspjeh)
{
	$greska = $veza->errorInfo();
	print "GREŠKA BAZE: " + $greska[2];
	exit();
}

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<div class='tabela'>";

	$upit = $veza->prepare("SELECT id, naziv FROM tabela;");
	$upit->execute();
	$domacini = $upit->fetchAll();

	foreach ($utakmice as $utakmica) {
		//$domacini = $veza->query("SELECT id, naziv FROM tabela;");
		//$gosti = $veza->query("SELECT id, naziv FROM tabela;");
		//print "<div class='red'>
		print
		"<div class='tr'>
			<form class='mala-forma' method='POST' action='ulaznice.php' onsubmit=\"return validacijaUtakmice(this, 'greska') && submitForm(this);\">
				<input type='hidden' name='edit-utakmica' value='".$utakmica["id"]."'>
				<div class='td'><select name='domacin'>";
				
				 foreach ($domacini as  $domacin) {
				 	if (intval($utakmica["domacinid"]) == intval($domacin["id"]))
				 	{
				 		print "<option value='$domacin[id]' selected>$domacin[naziv]</option>";
				 	}
				 	else
				 	{
				 		print "<option value='$domacin[id]'>$domacin[naziv]</option>";
				 	}
				 }
				
				print "</select></div><div class='td'>
				<select name='gost'>";

				 foreach ($domacini as  $gost) {
				 	if (intval($utakmica["gostid"]) == intval($gost["id"]))
				 	{
				 		print "<option value='$gost[id]' selected>$gost[naziv]</option>";
				 	}
				 	else
				 	{
				 		print "<option value='$gost[id]'>$gost[naziv]</option>";
				 	}
				 }

				print "</select></div>
				<div class='td'><input type='number' name='cijena' min='1' max='99' value='$utakmica[cijena]'></div>
				<div class='td'><input type='submit' value='Sačuvaj'></div>
			</form>";
			print "<div class='td'>
				<form class='mala-forma' method='POST' action='ulaznice.php' onsubmit=\"return submitForm(this);\">
					<input type='hidden' name='brisi-utakmica' value='".$utakmica["id"]."'>
					<input type='submit' value='Izbriši'>
				</form>
			</div>
		</div>";
	}
	//$klubovi = $veza->query("SELECT id, naziv FROM tabela;");
	$opcije = "";
	foreach($domacini as $klub)
	{
		$opcije = $opcije . "<option value=$klub[id]>$klub[naziv]</option>";
	}

	print
		"<div class='tr'>
			<form method='POST' action='ulaznice.php' onsubmit=\"return validacijaUtakmice(this, 'greska') && submitForm(this);\">
				<input type='hidden' name='dodaj-utakmica'>
				<div class='td'><select name='domacin'>";
				print $opcije;			
				print "</select></div>
				<div class='td'><select name='gost'>" . $opcije .  "</select></div>
				<div class='td'><input type='number' name='cijena' min='1' max='99'></div>
				<div class='td'><input type='submit' value='Dodaj utakmicu'></div>
			</form>
		</div>";

	print "</div>";
}
else
{
	//$utakmice = $veza->query("SELECT utakmice.id, tabela1.naziv AS domacin, tabela2.naziv AS gost FROM utakmice JOIN tabela tabela1 ON tabela1.id = utakmice.domacin JOIN tabela tabela2 ON tabela2.id = utakmice.gost;");

	if (!$utakmice)
	{
		$greska = $veza->errorInfo();
		print "GREŠKA BAZE: " + $greska[2];
		exit();
	}

	print "<form action='ulaznice.php' method='GET' >
	
		<div class='red'>
			<label for='utakmica'>Utakmica: </label>
			
			<select name='utakmica' id='utakmica'>";
				foreach($utakmice as $utakmica)
				{
				print "<option value='" . $utakmica["id"] ."'>" . $utakmica["domacin"] ." - " . $utakmica["gost"] . "</option>";
				}

			print "</select>
		</div>

		<div class='red'>
			<label for='tribina'>Tribina: </label>
			<select id='tribina' name='tribina'>
				<option value='Zapad'>Zapad</option>
				<option value='Istok'>Istok</option>
				<option value='Jug'>Jug</option>
				<option value='Sjever'>Sjever</option>
			</select>
		</div>

		<div class='red'>		
			<label for='brojulaznica'>Broj karata: </label><input type='number' name='brojulaznica' id='brojulaznica' max='9' min='1' >
		</div>


		<div class='red'>
			<div class='kolona jedan'>
				<input type='button' value='Sačuvaj' onclick='sacuvajUlaznice()'>
			</div>
			<div class='kolona tri'>
				<input type='submit' value='Rezerviši'>
			</div>
		</div>
		<input type='hidden' name='kupovina'>
	</form>";
}
}

?>	
	</div>
</div>
<script type='text/javascript' src='forma-validacija.js'></script>"
