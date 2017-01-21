<?php
$greska = 0;
$greska_string = "";
$uspjeh = 0;
$uspjeh_string = "";

$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
//$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");

$veza->exec("set names utf8");

if (isset($_REQUEST['csv']))
{
	$sadrzaj = "sep=,\n";
	$clanovi = $veza->query("SELECT id, ime, prezime, email, telefon FROM fanklub;");

	foreach ($clanovi as $clan)
	{
		$sadrzaj = $sadrzaj.$clan["id"].','.$clan["ime"].','.$clan["prezime"].','.$clan["email"].','.$clan["telefon"]."\n";
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

if (isset($_REQUEST["registracija"]) && isset($_REQUEST["ime"]) && isset($_REQUEST["prezime"]) && isset($_REQUEST["email"]) && isset($_REQUEST["telefon"]))
{
	if(!preg_match('/^[a-zA-Z\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E}]{2,15}$/u', $_REQUEST["ime"]))
	{
		$greska = 1;
		$uspjeh = 0;
		$greska_string = "Ime mora imati 2-15 slova<br>";
	}
	elseif(!preg_match('/^[a-zA-Z\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E}]{2,15}$/u', $_REQUEST["prezime"]))
	{
		$greska = 1;
		$uspjeh = 0;
		$greska_string = "Prezime mora imati 2-15 slova<br>";			
	}
	elseif((filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL) === false))
	{
		$greska = 1;
		$uspjeh = 0;
		$greska_string = "Email adresa nije ispravna<br>";
	}
	elseif(!preg_match('/^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{3})$/', $_REQUEST["telefon"]))
	{
		$uspjeh = 0;
		$greska = 1;
		$greska_string = "Telefon format: (061)-123-345 ili 061-123-456 ili 061123456<br>";
	}
	else
	{
		$ime = $_REQUEST["ime"];
		$prezime = $_REQUEST["prezime"];
		$email = $_REQUEST["email"];
		$telefon = $_REQUEST["telefon"];
		$upit = $veza->prepare("INSERT INTO fanklub (ime, prezime, email, telefon) VALUES (?, ?, ?, ?);");
		$upit->bindValue(1, $ime, PDO::PARAM_STR);
		$upit->bindValue(2, $prezime, PDO::PARAM_STR);
		$upit->bindValue(3, $email, PDO::PARAM_STR);
		$upit->bindValue(4, $telefon, PDO::PARAM_STR);

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
			$uspjeh_string = "Uspješno registrovan novi član.";			
		}
	}
}
elseif (isset($_REQUEST["edit"]) && isset($_REQUEST["ime"]) && isset($_REQUEST["prezime"]) && isset($_REQUEST["telefon"]) && isset($_REQUEST["email"]))
{
	if(!preg_match('/^[a-zA-Z\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E}]{2,15}$/u', $_REQUEST["ime"]))
	{
		$uspjeh = 0;
		$greska = 2;
		$greska_string = "Ime mora imati 2-15 slova<br>";
	}
	elseif(!preg_match('/^[a-zA-Z\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E}]{2,15}$/u', $_REQUEST["prezime"]))
	{
		$uspjeh = 0;
		$greska = 2;
		$greska_string = "Prezime mora imati 2-15 slova<br>";			
	}
	elseif((filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL) === false))
	{
		$uspjeh = 0;
		$greska = 2;
		$greska_string = "Email adresa nije ispravna<br>";
	}
	elseif(!preg_match('/^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{3})$/', $_REQUEST["telefon"]))
	{
		$greska = 2;
		$uspjeh = 0;
		$greska_string = "Telefon format: (061)-123-345 ili 061-123-456 ili 061123456<br>";
	}
	else
	{
		$id = $_REQUEST["edit"];
		$ime = $_REQUEST["ime"];
		$prezime = $_REQUEST["prezime"];
		$email = $_REQUEST["email"];
		$telefon = $_REQUEST["telefon"];
		

		$upit = $veza->prepare("UPDATE fanklub SET ime=?, prezime=?, email=?, telefon=? WHERE id=?;");
		$upit->bindValue(1, $ime, PDO::PARAM_STR);
		$upit->bindValue(2, $prezime, PDO::PARAM_STR);
		$upit->bindValue(3, $email, PDO::PARAM_STR);
		$upit->bindValue(4, $telefon, PDO::PARAM_STR);
		$upit->bindValue(5, $id, PDO::PARAM_INT);

		$rezultat = $upit->execute();
		if (!$rezultat)
		{
			$greska_info = $veza->errorInfo();
			$greska = 2;
			$uspjeh = 0;
			$greska_string = "Greška baze podataka: " . $greska_info[2];
		}
		else
		{
			$uspjeh = 2;
			$greska = 0;
			$uspjeh_string = "Uspješno editovan član.";
		}
	}
}
if (isset($_REQUEST["brisi"]))
{
	$id = $_REQUEST["brisi"];

	$upit = $veza->prepare("DELETE FROM fanklub WHERE id=?");
	$upit->bindValue(1, $id);
	$rezultat = $upit->execute();
	if (!$rezultat)
	{
		$greska_info = $veza->errorInfo();
		$greska = 2;
		$uspjeh = 0;
		$greska_string = "Greška baze podataka: " . $greska_info[2];
	}
	else
	{
		$uspjeh = 2;
		$greska = 0;
		$uspjeh_string = "Uspješno izbrisan član.";
	}
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
<?php
session_start();
if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print 	"<form method=\"GET\" action=\"fanklub.php\">
	<input type=\"submit\" value=\"Download CSV svih članova\">
	<input type=\"hidden\" name=\"csv\">
	</form>";
}

print "</div>";
?>

</div>
	<div id="pretraga-sugestija">
	</div>

	<div id="pretraga-rezultati">
	</div>
<script type="text/javascript" src="forma-validacija.js"></script>
