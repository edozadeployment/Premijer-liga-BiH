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
?>

<div class="kolona cetri glavni-sadrzaj">
	<div class="red">
		<div class="kolona dva">
			<p>Članstvom u fan-klubu ostvarujete pogodnosti kao što su popust pri kupovini ulaznica, učešće u nagradnim igrama i slično.</p>
			<p>Ovdje se možete učlaniti u fan-klub bh. Premijer lige.</p>
		</div>
		
		<div class="kolona dva">
			<form>
				<div class="red">
					<div class="kolona dva">
			 			<label for="ime">Ime:</label>
						<input type="text" name="ime" id="ime">	
					</div>
					<div class="kolona dva"></div>
				</div>

				<div class="red">
					<div class="kolona dva">
				 		<label for="prezime">Prezime:</label>
						<input type="text" name="prezime" id="prezime">
					</div>
					<div class="kolona dva"></div>
				</div>

				<div class="red">
					<div class="kolona dva">
				 		<label for="email">Email:</label>
						<input type="text" name="email" id="email">
					</div>
					<div class="kolona dva"></div>
				</div>

				<div class="red">
					<div class="kolona dva">
				 		<label for="tel">Telefon:</label>
						<input type="tel" name="telefon" id="telefon">
					</div>
					<div class="kolona dva"></div>
				</div>

				<div class="red">
					<div class="kolona cetri">
						<span id="greska"></span>
					</div>
				</div>

				<div class="red">
					<div class="kolona jedan">
						<input type="submit" value="Registruj se">
					</div>
					<div class="kolona jedan">
						<input type="button" value="Sačuvaj" onclick="sacuvajFanKlub()">
					</div>
					<div class="kolona dva"></div>
				</div>
			</form>
		</div>
	</div>
<div class="red">
<div class="kolona dva">
	<form>
		<label style="width: auto;" for="pretraga-polje">Pretraga članova: </label> <input type="text" id="pretraga-polje" name="pretraga-polje">
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
	<div id="rezultati-pretrage">
	</div>

<script type="text/javascript" src="forma-validacija.js"></script>
