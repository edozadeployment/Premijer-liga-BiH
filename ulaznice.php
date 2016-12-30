<?php
function idcmp($t1, $t2) {
				return intval($t1->attributes()["id"]) < intval($t2->attributes()["id"]);
			}

$greska = 0;
$uspjeh = 0;

$xml= simplexml_load_file("podaci.xml");
$utakmice = $xml->xpath('/podaci/utakmice/utakmica');

	if (isset($_REQUEST["kupovina"]))
	{
		require('fpdf181/fpdf.php');

		$pdf = new FPDF('L', 'mm', 'A5');
		$pdf->AddPage();
		$pdf->SetFont('Helvetica','B',16);
		$pdf->Cell(40,10,'Racun');
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Courier', '', 12);

		$ut;

		if (isset ($_REQUEST["utakmica"]))
		{
			$ut=$xml->xpath('//utakmica[@id="'.$_REQUEST["utakmica"].'"]')[0];
			//var_dump($ut);

		}


		if (isset($_REQUEST["utakmica"]))
		{

			$par = $ut->domacin . " - " . $ut->gost;

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
			$pdf->Cell(40,10,'Cijena: '. $ut->cijena . 'KM * '.$_REQUEST["brojulaznica"].'.........'.$_REQUEST["brojulaznica"]* intval($ut->cijena) . 'KM');
			$pdf->Ln();
			$pdf->Ln();
		}

	    $pdf->SetFont('Arial','I',8);
	    $pdf->Cell(0,10,'Racun izdat '.date("d.m.Y."));
		$pdf->Output();
	}

session_start();

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["edit-utakmica"]))
	{
		if(!isset($_REQUEST["domacin"]) || !preg_match('/^[a-zA-Z0-9.\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,20}$/u', $_REQUEST["domacin"]))
		{
			$greska = 1;
			$greska_string = "Naziv kluba (domacin) može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif(!isset($_REQUEST["gost"]) || !preg_match('/^[a-zA-Z0-9.\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,20}$/u', $_REQUEST["gost"]))
		{
			$greska = 1;
			$greska_string = "Naziv kluba (gost) može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!isset($_REQUEST["cijena"]) || !preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["cijena"])) {
			$greska = 1;
			$greska_string = "Cijena mora biti broj 1-99KM.";
		}
		else
		{
			$nadjeni;
			$trazeni = $_REQUEST["edit-utakmica"];

			foreach($xml->utakmice->utakmica as $utakmica)
			{
				if ($utakmica->attributes()["id"] == $trazeni)
				{
					//var_dump($utakmica);
					$utakmica->domacin = $_REQUEST["domacin"];
					$utakmica->gost = $_REQUEST["gost"];
					$utakmica->cijena = $_REQUEST["cijena"];
					$xml->asXml("podaci.xml");
					$uspjeh = 1;
					$uspjeh_string = "Uspješno editovana utakmica.";
					break;
				}
			}
		}
	}
	elseif (isset($_REQUEST["brisi-utakmica"]))
	{
		$ut=$xml->xpath('//utakmica[@id="'.$_REQUEST["brisi-utakmica"].'"]');

		unset($ut[0][0]);
		$xml->asXml("podaci.xml");
		$utakmice = $xml->xpath('/podaci/utakmice/utakmica');
		$uspjeh = 1;
		$uspjeh_string = "Uspješno izbrisana utakmica.";
	}
	
	elseif (isset($_REQUEST["dodaj-utakmica"]))
	{
		if(!isset($_REQUEST["domacin"]) || !preg_match('/^[a-zA-Z0-9.\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,20}$/u', $_REQUEST["domacin"]))
		{
			$greska = 1;
			$greska_string = "Naziv kluba (domacin) može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif(!isset($_REQUEST["gost"]) || !preg_match('/^[a-zA-Z0-9.\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,20}$/u', $_REQUEST["gost"]))
		{
			$greska = 1;
			$greska_string = "Naziv kluba (gost) može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!isset($_REQUEST["cijena"]) || !preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["cijena"])) {
			$greska = 1;
			$greska_string = "Cijena mora biti broj 1-99KM.";
		}
		else
		{
			
			$idtabela = $xml->xpath('/podaci/utakmice/utakmica');
			usort($idtabela, "idcmp");
			$idtabela[0];
			$novi = $xml->utakmice->addChild("utakmica", "");
			$novi->addAttribute("id", intval($idtabela[0]->attributes()["id"]) + 1);
			$novi->addChild("domacin", $_REQUEST["domacin"]);
			$novi->addChild("gost", $_REQUEST["gost"]);
			$novi->addChild("cijena", $_REQUEST["cijena"]);

			$xml->asXml("podaci.xml");
			$utakmice = $xml->xpath('/podaci/utakmice/utakmica');
			$uspjeh = 1;
			$uspjeh_string = "Uspješno dodana utakmica.";
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

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<div class='tabela'>";

	foreach ($utakmice as $utakmica) {
		//print "<div class='red'>
		print
		"<div class='tr'>
			<form class='mala-forma' method='POST' action='ulaznice.php' onsubmit=\"return validacijaUtakmice(this, 'greska') && submitForm(this);\">
				<input type='hidden' name='edit-utakmica' value='".$utakmica->attributes()["id"]."'>
				<div class='td'><input type='text' name='domacin' value='$utakmica->domacin'></div>
				<div class='td'><input type='text' name='gost' value='$utakmica->gost'></div>
				<div class='td'><input type='number' name='cijena' min='1' max='99' value='$utakmica->cijena'></div>
				<div class='td'><input type='submit' value='Sačuvaj'></div>
			</form>
			<div class='td'>
				<form class='mala-forma' method='POST' action='ulaznice.php' onsubmit=\"return submitForm(this);\">
					<input type='hidden' name='brisi-utakmica' value='".$utakmica->attributes()["id"]."'>
					<input type='submit' value='Izbriši'>
				</form>
			</div>
		</div>";
	}
	print
		"<div class='tr'>
			<form method='POST' action='ulaznice.php' onsubmit=\"return validacijaUtakmice(this, 'greska') && submitForm(this);\">
				<input type='hidden' name='dodaj-utakmica'>
				<div class='td'><input type='text' name='domacin'></div>
				<div class='td'><input type='text' name='gost'></div>
				<div class='td'><input type='number' name='cijena' min='1' max='99'></div>
				<div class='td'><input type='submit' value='Dodaj utakmicu'></div>
			</form>
		</div>";

	print "</div>";
}
else
{
	print "<form action='ulaznice.php' method='GET' >
	
		<div class='red'>
			<label for='utakmica'>Utakmica: </label>
			
			<select name='utakmica' id='utakmica'>";
				foreach($utakmice as $utakmica)
				{
				print "<option value='" . $utakmica->attributes()["id"] ."'>" . $utakmica->domacin ." - " . $utakmica->gost . "</option>";
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
?>	
	</div>
</div>
<script type='text/javascript' src='forma-validacija.js'></script>"
