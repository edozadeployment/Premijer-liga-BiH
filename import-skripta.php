<?php

$veza = new PDO("mysql:dbname=sampledb;host=172.30.235.155;charset=utf8", "root", "");
//$veza = new PDO("mysql:dbname=bh_pliga;host=localhost;charset=utf8", "root", "");
$veza->exec("set names utf8");
$xml= simplexml_load_file("podaci.xml");

print "Import podataka je počeo, sačekajte...<br>";

foreach($xml->tabela->klub as $klub)
{
	$id = $klub->attributes()["id"];
	$naziv = $klub->naziv;
	$bodovi = $klub->bodovi;
	
	$upit = "SELECT COUNT(*) FROM tabela WHERE id=" . $id .";";
	$rezultat = $veza->query($upit);
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($rezultat->fetchColumn());

 		if ($broj == 0)
 		{
			$rezultat = $veza->query("INSERT INTO tabela (id, naziv, bodovi) VALUES ($id, '$naziv', $bodovi);");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = "UPDATE tabela SET naziv='$naziv',bodovi=$bodovi WHERE id=$id;";
 			$rezultat = $veza->query("UPDATE tabela SET naziv='$naziv',bodovi=$bodovi WHERE id=$id;");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 	}
}

print "...<br>";


foreach($xml->vijesti->vijest as $vijest)
{
	$id = $vijest->attributes()["id"];
	$naslov = $vijest->naslov;
	$tekst = $vijest->tekst;

	
	$upit = "SELECT COUNT(*) FROM vijesti WHERE id=" . $id .";";
	$rezultat = $veza->query($upit);
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($rezultat->fetchColumn());

 		if ($broj == 0)
 		{
			$rezultat = $veza->query("INSERT INTO vijesti (id, naslov, tekst) VALUES ($id, '$naslov', '$tekst');");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = "UPDATE vijesti SET naslov='$naslov',tekst='$tekst' WHERE id=$id;";
 			$rezultat = $veza->query($upit);
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 	}
}

print "...<br>";

foreach($xml->strijelci->igrac as $igrac)
{
	$id = $igrac->attributes()["id"];
	$ime = $igrac->ime;
	$tim = $igrac->tim;
	$golovi = $igrac->golovi;
	
	$upit = "SELECT COUNT(*) FROM strijelci WHERE id=" . $id .";";
	$rezultat = $veza->query($upit);
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($rezultat->fetchColumn());

 		if ($broj == 0)
 		{
			$rezultat = $veza->query("INSERT INTO strijelci (id, ime, tim, golovi) VALUES ($id, '$ime', $tim, $golovi);");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = "UPDATE strijelci SET ime='$ime',tim=$tim,golovi=$golovi WHERE id=$id;";
 			$rezultat = $veza->query($upit);
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 	}
}

print "...<br>";

foreach($xml->utakmice->utakmica as $utakmica)
{
	$id = $utakmica->attributes()["id"];
	$domacin = $utakmica->domacin;
	$gost = $utakmica->gost;
	$cijena = $utakmica->cijena;
	
	$upit = "SELECT COUNT(*) FROM utakmice WHERE id=" . $id .";";
	$rezultat = $veza->query($upit);
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($rezultat->fetchColumn());

 		if ($broj == 0)
 		{
			$rezultat = $veza->query("INSERT INTO utakmice (id, domacin, gost, cijena) VALUES ($id, $domacin, $gost, $cijena);");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = "UPDATE utakmice SET domacin=$domacin,gost=$gost,cijena=$cijena WHERE id=$id;";
 			$rezultat = $veza->query($upit);
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 	}
}

print "...<br>";

foreach($xml->korisnici->korisnik as $korisnik)
{
	$id = $korisnik->id;
	$username = $korisnik->username;
	$pass = $korisnik->password;

	$upit = "SELECT COUNT(*) FROM korisnici WHERE id=" . $id .";";
	$rezultat = $veza->query($upit);
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else{
 		$broj = intval($rezultat->fetchColumn());

 		if ($broj == 0)
 		{
			$rezultat = $veza->query("INSERT INTO korisnici (id, username, password) VALUES ($id, '$username', '$pass');");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = "UPDATE korisnici SET username='$username',password='$pass' WHERE id=$id;";
 			$rezultat = $veza->query($upit);
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 	}
}

print "...<br>";

$xml= simplexml_load_file("fan-klub.xml");

foreach($xml->record as $clan)
{
	$id = $clan->id;
	$ime = $clan->ime;
	$prezime = $clan->prezime;
	$email = $clan->email;
	$telefon = $clan->telefon;
	
	$upit = "SELECT COUNT(*) FROM fanklub WHERE id=" . $id .";";
	$rezultat = $veza->query($upit);
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($rezultat->fetchColumn());

 		if ($broj == 0)
 		{
			$rezultat = $veza->query("INSERT INTO fanklub (id, ime, prezime, telefon, email) VALUES ($id, '$ime', '$prezime', '$telefon', '$email');");
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = "UPDATE fanklub SET ime='$ime',prezime='$prezime',telefon='$telefon', email='$email' WHERE id=$id;";
 			$rezultat = $veza->query($upit);
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 	}
}

print "Import završen!";


?>