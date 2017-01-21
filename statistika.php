<div class="kolona cetri red glavni-sadrzaj">
<div class="red">

<?php
$greska = 0;
$greska_string = "";
$uspjeh = 0;
$uspjeh_string = "";
function idcmp($t1, $t2) {
	return intval($t1->attributes()["id"]) < intval($t2->attributes()["id"]);
}

function cmp($t1, $t2) {
	return ((int) $t1->bodovi) < ((int)$t2->bodovi);
}

function cmp_strijelci($t1, $t2) {
	return ((int) $t1->golovi) < ((int)$t2->golovi);
}

session_start();
$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
//$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");

$veza->exec("set names utf8");

if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	if (isset($_REQUEST["edit-tabela"]))
	{
		if(!preg_match('/^[a-zA-Z0-9.\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,20}$/u', $_REQUEST["naziv"]))
		{
			$uspjeh = 0;
			$greska = 1;
			$greska_string = "Naziv kluba može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0|0)$/', $_REQUEST["bodovi"])) {
			$greska = 1;
			$uspjeh = 0;
			$greska_string = "Bodovi moraju biti broj 0-99.";
		}
		else
		{
			$id = $_REQUEST["edit-tabela"];
			$naziv = $_REQUEST["naziv"];
			$bodovi = $_REQUEST["bodovi"];
			
			$upit = $veza->prepare("UPDATE tabela SET naziv=?, bodovi=? WHERE id=?;");
			$upit->bindValue(1, $naziv, PDO::PARAM_STR);
			$upit->bindValue(2, $bodovi, PDO::PARAM_INT);
			$upit->bindValue(3, $id, PDO::PARAM_INT);

			$rezultat = $upit->execute();


			if (!$rezultat)
			{
				$greska_info = $veza->errorInfo();
				$uspjeh = 0;
				$greska = 1;
				$greska_string = "Greška baze podataka: " . $greska_info[2];;
			}
			else
			{
				$greska = 0;
				$uspjeh = 1;
				$uspjeh_string = "Uspješno editovana tabela";
			}
		}
	}

	elseif (isset($_REQUEST["edit-strijelci"]))
	{
		if(!isset($_REQUEST["ime"]) || !preg_match('/^[a-zA-Z\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,25}$/u', $_REQUEST["ime"]))
		{
			$greska = 2;
			$uspjeh = 0;
			$greska_string = "Ime mora biti niz slova (+ razmaci) u dužini od 2 do 25 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0|0)$/', $_REQUEST["golovi"])) {
			$greska = 2;
			$uspjeh = 0;
			$greska_string = "Golovi moraju biti broj 0-99.";
		}
		else
		{
			$id = $_REQUEST["edit-strijelci"];
			$ime = $_REQUEST["ime"];
			$golovi =  $_REQUEST["golovi"];
			$tim = $_REQUEST["tim"];
			
			$upit = $veza->prepare("UPDATE strijelci SET ime=?, golovi=?, tim=? WHERE id=?;");
			$upit->bindValue(1, $ime, PDO::PARAM_STR);
			$upit->bindValue(2, $golovi, PDO::PARAM_INT);
			$upit->bindValue(3, $tim, PDO::PARAM_INT);
			$upit->bindValue(4, $id, PDO::PARAM_INT);

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
				$greska = 0;
				$uspjeh = 2;
				$uspjeh_string = "Uspješno editovana tabela strijelaca";
			}
		}
	}

	elseif (isset($_REQUEST["brisi-tabela"]))
	{
		$id = $_REQUEST["brisi-tabela"];
		$upit = $veza->prepare("DELETE FROM tabela WHERE id=?;");

		$upit->bindValue(1, $id, PDO::PARAM_INT);

		$rezultat = $upit->execute();

		if (!$rezultat)
		{
			$greska_info = $veza->errorInfo();
			$uspjeh = 0;
			$greska = 1;
			$greska_string = "Greška baze podataka: " . $greska_info[2];
		}
		else
		{
			$greska = 0;
			$uspjeh = 1;
			$uspjeh_string = "Uspješno izbrisan klub";
		}
	}

	if (isset($_REQUEST["brisi-strijelci"]))
	{
		$id = $_REQUEST["brisi-strijelci"];
		$upit = $veza->prepare("DELETE FROM strijelci WHERE id=?;");
		$upit->bindValue(1, $id, PDO::PARAM_INT);
		$rezultat = $upit->execute();

		if (!$rezultat)
		{
			$greska_info = $veza->errorInfo();
			$uspjeh = 0;
			$greska = 2;
			$greska_string = "Greška baze podataka: " . $greska_info[2];
		}
		else
		{
			$greska = 0;
			$uspjeh = 2;
			$uspjeh_string = "Uspješno izbrisan strijelac.";
		}
	}

	if (isset($_REQUEST["dodaj-tabela"]))
	{
		if(!preg_match('/^[a-zA-Z.\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,20}$/u', $_REQUEST["naziv"]))
		{
			$uspjeh = 0;
			$greska = 1;
			$greska_string = "Naziv kluba može sadržavati samo slova i razmake, u dužini od 2 do 20 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["bodovi"])) {
			$uspjeh = 0;
			$greska = 1;
			$greska_string = "Bodovi moraju biti broj 0-99.";
		}
		else
		{
			$naziv = $_REQUEST["naziv"];
			$bodovi = $_REQUEST["bodovi"];

			$upit = $veza->prepare("INSERT INTO tabela (naziv, bodovi) VALUES (?, ?);");
			$upit->bindValue(1, $naziv, PDO::PARAM_STR);
			$upit->bindValue(2, $bodovi, PDO::PARAM_INT);
			
			$rezultat = $upit->execute();


			if (!$rezultat)
			{
				$greska_info = $veza->errorInfo();
				$uspjeh = 0;
				$greska = 1;
				$greska_string = "Greška baze podataka: " . $greska_info[2];;
			}
			else
			{
				$greska = 0;
				$uspjeh = 1;
				$uspjeh_string = "Uspješno dodan klub";
			}
		}
	}

	if (isset($_REQUEST["dodaj-strijelci"]))
	{

		if(!isset($_REQUEST["ime"]) || !preg_match('/^[a-zA-Z\x{0106}\x{0107}\x{010C}\x{010D}\x{0110}\x{0111}\x{0160}\x{0161}\x{017D}\x{017E} ]{2,25}$/u', $_REQUEST["ime"]))
		{
			$uspjeh = 0;
			$greska = 2;
			$greska_string = "Ime mora biti niz slova (+ razmaci) u dužini od 2 do 25 karaktera.";
		}
		elseif (!preg_match('/^(\d?[1-9]|[1-9]0)$/', $_REQUEST["golovi"])) {
			$uspjeh = 0;
			$greska = 2;
			$greska_string = "Golovi moraju biti broj 0-99.";
		}
		else
		{
			$ime = $_REQUEST["ime"];
			$golovi =  $_REQUEST["golovi"];
			$tim = $_REQUEST["tim"];

			$upit = $veza->prepare("INSERT INTO strijelci (ime, golovi, tim) VALUES (?, ?, ?);");
			$upit->bindValue(1, $ime, PDO::PARAM_STR);
			$upit->bindValue(2, $golovi, PDO::PARAM_INT);
			$upit->bindValue(3, $tim, PDO::PARAM_INT);

			$rezultat = $upit->execute();

			if (!$rezultat)
			{
				$greska_info = $veza->errorInfo();
				$uspjeh = 0;
				$greska = 2;
				$greska_string = "Greška baze podataka: " . $greska_info[2];;
			}
			else
			{
				$greska = 0;
				$uspjeh = 2;
				$uspjeh_string = "Uspješno dodan strijelac";
			}
		}
	}
}

//$tabela = $veza->query("SELECT id, naziv, bodovi FROM tabela ORDER BY bodovi DESC;");
$upit = $veza->prepare("SELECT id, naziv, bodovi FROM tabela ORDER BY bodovi DESC;");
$ispravno = $upit->execute();

if (!$ispravno) {
	$greska = $veza->errorInfo();
    print "SQL greška: " . $greska[2];
    exit();
}
$tabela = $upit->fetchAll();

$upit = $veza->prepare("SELECT strijelci.id, strijelci.ime, tabela.naziv AS tim, strijelci.tim AS timid, strijelci.golovi FROM strijelci JOIN tabela ON tabela.id=strijelci.tim ORDER BY strijelci.golovi DESC;");

$ispravno = $upit->execute();

if (!$ispravno) {
	$greska = $veza->errorInfo();
    print "SQL greška: " . $greska[2];
    exit();
}

$strijelci = $upit->fetchAll();


//usort($tabela, 'cmp');
//usort($strijelci, 'cmp_strijelci');

print "<div class=\"kolona dva glavni-sadrzaj\">";
print "<p>Trenutna tabela lige (klub, golovi):</p>";
print "<div class='red'><span class='greska' id='novi-klub-greska'>";
if ($greska == 1)
{
	print $greska_string;
}
print "</div>";

print "<div class='red'><span class='uspjeh'>";
if ($uspjeh == 1)
{
	print $uspjeh_string;
}
print "</div>";


if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<div class='tabela'>";
/*	print "<div class='tr'>";
	print "<div class='th'>Klub</div>";
	print "<div class='th'>Bodovi</div>";
	print "<div class='th'></div>";
	print "<div class='th'></div>";
	print "</div>";*/

	foreach ($tabela as $mjesto=>$klub) {
		//print "<div class='red'>
		print "<div class='tr'><form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return validacijaKluba(this, 'novi-klub-greska') && submitForm(this);\">
		<div class='td'><input type='text' name='naziv' value='$klub[naziv]'></div>
		<div class='td'><input type='number' name='bodovi' min='0' max='99' value='$klub[bodovi]'></div>
		<input type='hidden' name='edit-tabela' value='".$klub["id"]."'>
		<div class='td'><input type='submit' value='Sačuvaj'></div>
		</form>
		<div class='td'>
		<form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='hidden' name='brisi-tabela' value='".$klub["id"]."'>
		<input type='submit' value='Izbriši'>
		</form></div></div>";
	}
	print "<div class='tr'>
		<form method='POST' action='statistika.php' onsubmit=\"return validacijaKluba(this, 'novi-klub-greska') && submitForm(this);\">
		<input type='hidden' name='dodaj-tabela'>
		<div class='td'><input type='text' name='naziv'></div>
		<div class='td'><input type='number' min='0' max='99' name='bodovi'></div>
		<div class='td'><input type='submit' value='Dodaj klub'></div>
		</form>
	</div>";

	print "</div>";
}
else
{
	print "<table>";
	print "<tr>";
	print "<th>#</th>";
	print "<th>Klub</th>";
	print "<th>Bodovi</th>";
	print "</tr>";

	foreach ($tabela as $mjesto=>$klub) {
		print "<tr>";
		print "<td>". ($mjesto + 1) ."</td>";
		print "<td>$klub[naziv]</td>";
		print "<td>$klub[bodovi]</td>";
		print "</tr>";
	}

	print "</table>";
}

print "</div>";

print "<div class=\"kolona dva glavni-sadrzaj\">";
print "<p>Lista najboljih strijelaca (ime, tim, broj golova):</p>";
print "<div class='red'><span class='greska' id='edit-klub-greska'>";
if ($greska == 2)
{
	print $greska_string;
}
print "</div>";
print "<div class='red'><span class='uspjeh'>";
if ($uspjeh == 2)
{
	print $uspjeh_string;
}
print "</div>";


if (isset($_SESSION["username"]) && $_SESSION["username"] == "admin")
{
	print "<div class='table'>";
//	print "<div class='tr'>";
//	print "<div class='th' style='width: 20%;'>Igrač</div>";
//	print "<div class='th' style='width: 20%;'>Tim</div>";
//	print "<div class='th' style='width: 20%;'>Golovi</div>";	
//	print "<div class='th'></div>";	
//	print "</div>";

	foreach ($strijelci as $mjesto=>$igrac) {
		//print "<div class='red'>
		print "<div class='tr'><form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return validacijaIgraca(this, 'edit-klub-greska') && submitForm(this);\">
		
		<div class='td'><input type='text' name='ime' value='". $igrac["ime"] . "'></div>
		<div class='td'><select name='tim'>";
		
		foreach ($tabela as $klub)
		{
			if ($klub["id"] == $igrac["timid"])
			{
				print "<option value='$klub[id]' selected>$klub[naziv]</option>";
			}
			else
			{
				print "<option value='$klub[id]'>$klub[naziv]</option>";
			}
		}

		print "</select></div>
		<div class='td'><input type='number' min='0' max='99' name='golovi' value='" . $igrac["golovi"] ."'></div>
		<input type='hidden' name='edit-strijelci' value='".$igrac["id"]."'>
		<div class='td'><input type='submit' value='Sačuvaj'></div>
		</form>
		<div class='td'>
		<form class='mala-forma' method='POST' action='statistika.php' onsubmit=\"return submitForm(this);\">
		<input type='hidden' name='brisi-strijelci' value='".$igrac["id"]."'>
		<input type='submit' value='Izbriši'>
		</form>
		</div>
		</div>";
		//</div>";
	}

	//print "<div class='red'>
	print "<tr><form method='POST' action='statistika.php' onsubmit=\"return validacijaIgraca(this, 'edit-klub-greska') && submitForm(this);\">
	<td><input type='text' name='ime'></td>
	<td><select name='tim'>";

	foreach ($tabela as $klub)
	{
		print "<option value='$klub[id]'>$klub[naziv]</option>";
	}

	print "</select></td>
	<td><input type='number' min='0' max='99' name='golovi'>
	<input type='hidden' name='dodaj-strijelci'></td>
	<td colspan='2'><input type='submit' value='Dodaj igrača'></td></tr>
	</form>
	</div>";
}

else
{
print "<table>";
print "<tr>";
print "<th>#</th>";
print "<th>Igrač</th>";
print "<th>Tim</th>";
print "<th>Golovi</th>";
print "</tr>";

foreach ($strijelci as $mjesto=>$strijelac) {
	print "<tr>";
	print "<td>".($mjesto + 1)."</td>";
	print "<td>".$strijelac["ime"]."</td>";
	print "<td>".$strijelac["tim"]."</td>";
	print "<td>".$strijelac["golovi"]."</td>";
	print "</tr>";
}
print "</table>";	
}

print "</div>";
?>
</div>