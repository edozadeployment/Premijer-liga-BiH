<div class="kolona cetri glavni-sadrzaj">
	<p>Ovu stranicu je osnovalo udruženje ljubitelja Premijer lige Bosne i Hercegovine, kako bi okupilo sve fanove na jedno mjesto i olakšalo im praćenje lige. Za sva pitanja, i sugestije vezane za stranicu ili rad udruženja, ispunite sljedeću formu: </p>

	<form class="izdvojena-forma" onsubmit="return alert('Poslan mail...') && false;">
		<div class="red">
			<div class="kolona dva">
		 		<label for="ime">Ime:</label>
		 		<input type="text" name="ime" id="ime">
		 	</div>
		 	<div class="kolona dva">
		 	</div>
	 	</div>
		<div class="red">
			<div class="kolona dva">
			 	<label for="email">Email:</label>
			 	<input type="email" name="email" id="email">
			</div>
			<div class="kolona dva">
			</div>
		</div>

		<div class="red">
			<div class="kolona dva">
				<label for="telefon">Telefon:</label>
				<input type="tel" name="telefon" id="telefon">
			</div>
			<div class="kolona dva">
			</div>
		</div>
		<div class="red">
			<!--<div class="kolona dva"></div>-->
			<div class="kolona cetri">
				<textarea id="tekst" rows="10" name="tekst" form="kontakt-forma"></textarea>
	 		</div>
	 	</div>
	 	<div class="red">
	 		<span id="greska"></span>
	 	</div>
		<div class="red">
			<div class="kolona jedan">
				<input type="submit" value="Pošalji">			
			</div>
			<div class="kolona jedan">
				<input type="button" value="Sačuvaj" onclick="sacuvajONama()">
			</div>
			<div class="kolona dva">
			</div>
		</div>
 	</form>
 	<script type="text/javascript" src="forma-validacija.js"></script>
</div>