<?php
	$greska = 0;
	$greska_string = "";
	$uspjeh = 0;
	$uspjeh_string = "";

	if (isset($_REQUEST['csv']))
	{
		$sadrzaj = "sep=,\n";
		$xml = simplexml_load_file("fan-klub.xml");

		$clanovi = $xml->record;

		foreach ($clanovi as $clan)
		{
			$sadrzaj = $sadrzaj.$clan->id.','.$clan->ime.','.$clan->prezime.','.$clan->email.','.$clan->telefon."\n";
		}

		$file = "fanklub.csv";
		file_put_contents($file, $sadrzaj);

		if (file_exists($file)) {
 			header('Content-Description: File Transfer');
    		header('Content-Type: application/octet-stream');
    		header('Content-Disposition: attachment; filename="'.basename($file).'"');
	    	header('Expires: 0');
	    	header('Cache-Control: must-revalidate');
    		header('Pragma: public');
    		header('Content-Length: ' . filesize($file));
    		readfile($file);
    	}
	}

	if (isset($_REQUEST["registracija"]))
	{
		if(!isset($_REQUEST["ime"]) || !preg_match('/^([a-zA-Z]){2,15}$/', $_REQUEST["ime"]))
		{
			$greska = 1;
			$greska_string = "Ime mora imati 2-15 slova<br>";
		}
		elseif(!isset($_REQUEST["prezime"]) || !preg_match('/^[a-zA-Z]{2,15}$/', $_REQUEST["prezime"]))
		{
			$greska = 1;
			$greska_string = "Prezime mora imati 2-15 slova<br>";			
		}
		elseif(!isset($_REQUEST["email"]) || (filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL) === false))
		{
			$greska = 1;
			$greska_string = "Email adresa nije ispravna<br>";
		}
		elseif(!isset($_REQUEST["telefon"]) || !preg_match('/^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{3})$/', $_REQUEST["telefon"]))
		{
			$greska = 1;
			$greska_string = "Telefon format: (061)-123-345 ili 061-123-456 ili 061123456<br>";
		}
		else
		{
			$xml= simplexml_load_file("fan-klub.xml");
			$sortirani = $xml->xpath('/dataset/record');

			function cmp($t1, $t2) {

	    		return intval($t1->id) < intval($t2->id);
			}
			usort($sortirani, 'cmp');
			$noviId = $sortirani[0]->id + 1;

			$novi = $xml->addChild("record", "");
			$novi->addChild("id", $noviId);
			$novi->addChild("ime", $_REQUEST["ime"]);
			$novi->addChild("prezime", $_REQUEST["prezime"]);
			$novi->addChild("email", $_REQUEST["email"]);
			$novi->addChild("telefon", $_REQUEST["telefon"]);

			$xml->asXml("fan-klub.xml");

			$uspjeh = 1;
			$uspjeh_string = "Uspješno registrovan novi član.";
		}
	}
	elseif (isset($_REQUEST["edit"]))
	{
		if(!isset($_REQUEST["ime"]) || preg_match('/^[a-zA-Z]{2,15}$/', $_REQUEST["ime"]))
		{
			$greska = 2;
			$greska_string = "Ime mora imati 2-15 slova<br>";
		}
		elseif(!isset($_REQUEST["prezime"]) || preg_match('/^[a-zA-Z]{2,15}$/', $_REQUEST["prezime"]))
		{
			$greska = 2;
			$greska_string = "Prezime mora imati 2-15 slova<br>";			
		}
		elseif(!isset($_REQUEST["email"]) || (filter_var($email, FILTER_VALIDATE_EMAIL) === false))
		{
			$greska = 2;
			$greska_string = "Email adresa nije ispravna<br>";
		}
		elseif(!isset($_REQUEST["telefon"]) || preg_match('/^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{3})$/', $_REQUEST["telefon"]))
		{
			$greska = 2;
			$greska_string = "Telefon format: (061)-123-345 ili 061-123-456 ili 061123456<br>";
		}
		else
		{
			$xml= simplexml_load_file("fan-klub.xml");
			$src = "/dataset/record[id=".$_REQUEST["edit"]."]";
			$trazeni = ($xml->xpath($src));
			if (sizeof($trazeni != 1))
			{
				$greska = 2;
				$greska_string = "Greska baze podataka";
			}
			else
			{
				$trazeni[0]->ime = $_REQUEST["ime"];
				$trazeni[0]->prezime = $_REQUEST["prezime"];
				$trazeni[0]->email = $_REQUEST["email"];
				$trazeni[0]->telefon = $_REQUEST["telefon"];

				$xml->asXml("fan-klub.xml");

				$uspjeh = 2;
				$uspjeh_string = "Uspješno editovan član.";
			}
		}

	}

	if (isset($_REQUEST["brisi"]))
	{
		$xml= simplexml_load_file("fan-klub.xml");
		$trazeni = ($xml->xpath('/dataset/record[id='.$_REQUEST["brisi"].']'));
		
		
		unset($trazeni[0][0]);
		$xml->asXml("fan-klub.xml");

		$uspjeh = 2;
		$uspjeh_string = "Uspješno izbrisan član.";
	}
?>



<div class="kolona cetri glavni-sadrzaj">
	<div class="red">
		<div class="kolona dva">
			<p>Članstvom u fan-klubu ostvarujete pogodnosti kao što su popust pri kupovini ulaznica, učešće u nagradnim igrama i slično.</p>
			<p>Ovdje se možete učlaniti u fan-klub bh. Premijer lige.</p>
		</div>
		
		<div class='kolona dva'>

			<form method='POST' action='fanklub.php' id="registracija-fan" onsubmit='return provjeriFormu(this) && submitForm(this);'>
			<input type="hidden" name="registracija">
				<div class='red'>
					<div class='kolona dva'>
			 			<label for='ime'>Ime:</label>
						<input type='text' name='ime' id='ime'>	
					</div>
					<div class='kolona dva'></div>
				</div>

				<div class='red'>
					<div class='kolona dva'>
				 		<label for='prezime'>Prezime:</label>
						<input type='text' name='prezime' id='prezime'>
					</div>
					<div class='kolona dva'></div>
				</div>

				<div class='red'>
					<div class='kolona dva'>
				 		<label for='email'>Email:</label>
						<input type='text' name='email' id='email'>
					</div>
					<div class='kolona dva'></div>
				</div>

				<div class='red'>
					<div class='kolona dva'>
				 		<label for='tel'>Telefon:</label>
						<input type='tel' name='telefon' id='telefon'>
					</div>
					<div class='kolona dva'></div>
				</div>

				<div class='red'>
					<div class='kolona cetri'><?php
					$izlaz = "<span id='greska'>";
					if ($greska == 1)
					{
						$izlaz = $izlaz . $greska_string;
					} 
					
					$izlaz = $izlaz ."</span>";
					print $izlaz;
					$izlaz = "<span id='uspjeh'>";
					if ($uspjeh == 1)
					{
						$izlaz = $izlaz . $uspjeh_string;
					} 
					
					$izlaz = $izlaz ."</span>";
					print $izlaz;
					?>
					</div>
				</div>

				<div class='red'>
					<div class='kolona jedan'>
						<input type='submit' value='Registruj se'>
					</div>
					<div class='kolona jedan'>
						<input type='button' value='Sačuvaj' onclick='sacuvajFanKlub()'>
					</div>
					<div class='kolona dva'></div>
				</div>
			</form>
		</div>
	</div>
<div class="red">
	<div class="kolona cetri">
		<?php
			$izlaz = "<span class='greska'>";
			if ($greska == 2)
				{
					$izlaz = $izlaz . $greska_string;
				}
			$izlaz = $izlaz . "</span>";
			print $izlaz;

			$izlaz = "<span class='uspjeh'>";
			if ($uspjeh == 2)
				{
					$izlaz = $izlaz . $uspjeh_string;
				}
			$izlaz = $izlaz . "</span>";
			print $izlaz;

			?>
	</div>
</div>
<div class="red">
<div class="kolona dva">
	<form onsubmit="trazi_glavno(); return false;">
		<label style="width: auto;" for="pretraga-polje">Pretraga članova: </label> <input type="text" id="pretraga-polje" oninput="pretrazi();" name="pretraga-polje">
		<input type="submit" value="Traži">
	</form>
</div>
<div class="kolona jedan">
</div>
<div class="kolona jedan">
	<form method="GET" action="fanklub.php">
		<input type="submit" value="Download CSV svih članova">
		<input type="hidden" name="csv">
	</form>
</div>

</div>
	<div id="pretraga-sugestija">
	</div>

	<div id="pretraga-rezultati">
	</div>
<script type="text/javascript" src="forma-validacija.js"></script>
