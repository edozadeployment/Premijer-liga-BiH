<?php
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
		
		if (isset($_REQUEST["kolo"]))
		{
			$pdf->Cell(40,10,'Kolo: '.$_REQUEST["kolo"]);
			$pdf->Ln();
		}
		if (isset($_REQUEST["utakmica"]))
		{
			$pdf->Cell(40,10,'Utakmica: '.$_REQUEST["utakmica"]);
			$pdf->Ln();
		}
		if (isset($_REQUEST["tribina"]))
		{
			$pdf->Cell(40,10,'Kolo: '.$_REQUEST["tribina"]);
			$pdf->Ln();
		}
		if (isset($_REQUEST["brojulaznica"]))
		{
			$pdf->Cell(40,10,'Broj ulaznica: '.$_REQUEST["brojulaznica"]);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetFont('Courier', 'B', 13);
			$pdf->Cell(40,10,'Cijena: '.'5 * '.$_REQUEST["brojulaznica"].'KM.........'.$_REQUEST["brojulaznica"]*5 . 'KM');
			$pdf->Ln();
			$pdf->Ln();
		}

	    $pdf->SetFont('Arial','I',8);
	    $pdf->Cell(0,10,'Racun izdat '.date("d.m.Y."));
		$pdf->Output();
	}
?>

<div class="kolona cetri glavni-sadrzaj red">
	<div class="kolona dva">
		<p>Ovdje možete rezervisati karte za utakmice.</p>
		<p>Moguće je rezervisati do osam karata. Ako ste član fan kluba, dobijate popust na cijenu od 10%.</p>
	</div>
	<div class="kolona dva">
	<form action="ulaznice.php" method="GET">
		<div class="red">
			<label for="kolo">Kolo: </label>
			<input type="number" id="kolo" name="kolo" max="30" min="28">
		</div>
		
		<div class="red">
			<label for="utakmica">Utakmica: </label>
			
			<select name="utakmica" id="utakmica">
				<option value="Celik - Siroki Brijeg">Čelik - Široki Brijeg</option>
				<option value="Mladost - Zrinjski">Mladost - Zrinjski</option>
				<option value="Željezničar - Radnik">Željezničar - Radnik</option>
				<option value="Metalleghe - Vitez">Metalleghe - Vitez</option>
				<option value="Krupa - Olimpic">Krupa - Olimpic</option>
				<option value="Sloboda - Sarajevo">Sloboda - Sarajevo</option>
			</select>
		</div>

		<div class="red">
			<label for="tribina">Tribina: </label>
			<select id="tribina" name="tribina">
				<option value="Zapad">Zapad</option>
				<option value="Istok">Istok</option>
				<option value="Jug">Jug</option>
				<option value="Sjever">Sjever</option>
			</select>
		</div>

		<div class="red">		
			<label for="brojulaznica">Broj karata: </label><input type="number" name="brojulaznica" id="brojulaznica" max="8" min="1" >
		</div>

		<div class="red">
			<p>Preostalo ulaznica: <span id="preostalo">294</span></p>
		</div>
	 	<div class="red">
	 		<span id="greska"></span>
	 	</div>
		<div class="red">
			<div class="kolona jedan">
				<input type="button" value="Sačuvaj" onclick="sacuvajUlaznice()">
			</div>
			<div class="kolona tri">
				<input type="submit" value="Rezerviši">
			</div>
		</div>
		<input type=hidden name="kupovina">
	</form>
	</div>
</div>
<script type="text/javascript" src="forma-validacija.js"></script>