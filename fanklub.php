<?php
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
		$xml= simplexml_load_file("fan-klub.xml");
		$sortirani = $xml->xpath('/dataset/record');

		function cmp($t1, $t2) {
    		return $t1->id > $t2->id;
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
	}

	if (isset($_REQUEST["edit"]))
	{
		$xml= simplexml_load_file("fan-klub.xml");
		$trazeni = ($xml->xpath('/dataset/record[id='.$_REQUEST["id"].']'))[0];
		$trazeni->ime = $_REQUEST["ime"];
		$trazeni->prezime = $_REQUEST["prezime"];
		$trazeni->email = $_REQUEST["email"];
		$trazeni->telefon = $_REQUEST["telefon"];

		$xml->asXml("fan-klub.xml");
	}

	if (isset($_REQUEST["brisi"]))
	{
		$xml= simplexml_load_file("fan-klub.xml");
		$trazeni = ($xml->xpath('/dataset/record[id='.$_REQUEST["id"].']'))[0];
		
		unset($trazeni[0][0]);
		$xml->asXml("fan-klub.xml");
	}
?>



<div class="kolona cetri glavni-sadrzaj">
	<div class="red">
		<div class="kolona dva">
			<p>Članstvom u fan-klubu ostvarujete pogodnosti kao što su popust pri kupovini ulaznica, učešće u nagradnim igrama i slično.</p>
			<p>Ovdje se možete učlaniti u fan-klub bh. Premijer lige.</p>
		</div>
		
		<div class='kolona dva'>
			<form method='POST' action='fanklub.php'  onsubmit='return submitForm(this);'>
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
					<div class='kolona cetri'>
						<span id='greska'></span>
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
<div class="kolona dva">
	<form onsubmit="trazi_glavno(); return false;">
		<label style="width: auto;" for="pretraga-polje">Pretraga članova: </label> <input type="text" id="pretraga-polje" name="pretraga-polje">
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
