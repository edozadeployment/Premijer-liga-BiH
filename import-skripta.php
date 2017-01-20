<?php

//$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");

$veza->exec("set names utf8");
$xml= simplexml_load_file("podaci.xml");

$veza->exec("CREATE TABLE IF NOT EXISTS fanklub 
		(
			id int NOT NULL AUTO_INCREMENT,
			ime varchar(15) NOT NULL,
			prezime varchar(15) NOT NULL,
			email varchar(254) NOT NULL,
			telefon varchar(13) NOT NULL,
			PRIMARY KEY (id)
		)
		CHARACTER SET utf8 COLLATE utf8_slovenian_ci ENGINE=InnoDB;");

$veza->exec("CREATE TABLE IF NOT EXISTS korisnici 
		(
			id int NOT NULL AUTO_INCREMENT,
			username varchar(20) NOT NULL,
			password varchar(20) NOT NULL,
			PRIMARY KEY (id)
		)
		CHARACTER SET utf8 COLLATE utf8_slovenian_ci ENGINE=InnoDB;");

$veza->exec("CREATE TABLE IF NOT EXISTS tabela
		(
			id int NOT NULL AUTO_INCREMENT,
			naziv varchar(20) NOT NULL,
			bodovi int NOT NULL,
			PRIMARY KEY (id)
		)
		CHARACTER SET utf8 COLLATE utf8_slovenian_ci ENGINE=InnoDB;");

$veza->exec("CREATE TABLE IF NOT EXISTS strijelci
		(
			id int NOT NULL AUTO_INCREMENT,
			ime varchar(25) NOT NULL,
			golovi int NOT NULL,
			tim int NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY (tim) REFERENCES tabela(id)
	       	ON DELETE CASCADE
	       	ON UPDATE CASCADE
      	)
		CHARACTER SET utf8 COLLATE utf8_slovenian_ci ENGINE=InnoDB;");

$veza->exec("CREATE TABLE IF NOT EXISTS utakmice
		(
			id int NOT NULL AUTO_INCREMENT,
			domacin int NOT NULL,
			gost int NOT NULL,
			cijena int NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY (domacin) REFERENCES tabela(id)
   			ON DELETE CASCADE
	       	ON UPDATE CASCADE,
			FOREIGN KEY (gost) REFERENCES tabela(id)
		   	ON DELETE CASCADE
	       	ON UPDATE CASCADE
		)
		CHARACTER SET utf8 COLLATE utf8_slovenian_ci ENGINE=InnoDB;");

$veza->exec("CREATE TABLE IF NOT EXISTS vijesti
		(
			id int NOT NULL AUTO_INCREMENT,
			vrijeme timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			naslov varchar(60) NOT NULL,
			tekst varchar(800) NOT NULL,
			PRIMARY KEY (id)
		)
		CHARACTER SET utf8 COLLATE utf8_slovenian_ci ENGINE=InnoDB;");

print "Import podataka je počeo, sačekajte...<br>";

foreach($xml->tabela->klub as $klub)
{
	$id = $klub->attributes()["id"];
	$naziv = $klub->naziv;
	$bodovi = $klub->bodovi;
	
	$upit = $veza->prepare("SELECT COUNT(*) FROM tabela WHERE id=?;");
	$upit->bindValue(1, $id, PDO::PARAM_INT);

	$uspjeh = $upit->execute();
	if (!$uspjeh) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($upit->fetchColumn());

 		if ($broj == 0)
 		{
			$upit = $veza->prepare("INSERT INTO tabela (id, naziv, bodovi) VALUES (?, ?, ?);");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$upit->bindValue(2, $naziv, PDO::PARAM_STR);
			$upit->bindValue(3, $bodovi, PDO::PARAM_INT);
			$uspjeh = $upit->execute();

			if (!$uspjeh) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = $veza->prepare("UPDATE tabela SET naziv=?,bodovi=? WHERE id=?;");
 			$upit->bindValue(1, $naziv, PDO::PARAM_STR);
 			$upit->bindValue(2, $bodovi, PDO::PARAM_INT);
 			$upit->bindValue(3, $id, PDO::PARAM_INT);
 			$uspjeh = $upit->execute();
			if (!$uspjeh) {
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

	
	$upit = $veza->prepare("SELECT COUNT(*) FROM vijesti WHERE id=?;");
	$upit->bindValue(1, $id, PDO::PARAM_INT);

	$uspjeh = $upit->execute();
	if (!$uspjeh) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($upit->fetchColumn());

 		if ($broj == 0)
 		{
			$upit = $veza->prepare("INSERT INTO vijesti (id, naslov, tekst) VALUES (?, ?, ?);");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$upit->bindValue(2, $naslov, PDO::PARAM_STR);
			$upit->bindValue(3, $tekst, PDO::PARAM_STR);
			$uspjeh = $upit->execute();

			if (!$uspjeh) {

				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
			$upit = $veza->prepare("UPDATE vijesti SET naslov='$naslov',tekst='$tekst' WHERE id=$id;");
			$upit->bindValue(1, $naslov, PDO::PARAM_STR);
			$upit->bindValue(2, $tekst, PDO::PARAM_STR);
			$upit->bindValue(3, $id, PDO::PARAM_INT);
			$uspjeh = $upit->execute();

			if (!$uspjeh) {
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
	
	$upit = $veza->prepare("SELECT COUNT(*) FROM strijelci WHERE id=?");
	$upit->bindValue(1, $id, PDO::PARAM_INT);

	$uspjeh = $upit->execute();
	if (!$uspjeh) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($upit->fetchColumn());

 		if ($broj == 0)
 		{
			$upit = $veza->prepare("INSERT INTO strijelci (id, ime, tim, golovi) VALUES (?, ?, ?, ?);");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$upit->bindValue(2, $ime, PDO::PARAM_STR);
			$upit->bindValue(3, $tim, PDO::PARAM_INT);
			$upit->bindValue(4, $golovi, PDO::PARAM_INT);

			$uspjeh = $upit->execute();
			if (!$uspjeh) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
			$upit = $veza->prepare("UPDATE strijelci SET ime='$ime',tim=$tim,golovi=$golovi WHERE id=$id;");
			$upit->bindValue(1, $ime, PDO::PARAM_STR);
			$upit->bindValue(2, $tim, PDO::PARAM_INT);
			$upit->bindValue(3, $golovi, PDO::PARAM_INT);
			$upit->bindValue(4, $id, PDO::PARAM_INT);

 			$uspjeh = $upit->execute();
			if (!$uspjeh) {
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
	
	$upit = $veza->prepare("SELECT COUNT(*) FROM utakmice WHERE id=?;");
	$upit->bindValue(1, $id, PDO::PARAM_INT);
	$uspjeh = $upit->execute();

	if (!$uspjeh) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($upit->fetchColumn());

 		if ($broj == 0)
 		{
			$upit = $veza->prepare("INSERT INTO utakmice (id, domacin, gost, cijena) VALUES (?, ?, ?, ?);");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$upit->bindValue(2, $domacin, PDO::PARAM_INT);
			$upit->bindValue(3, $gost, PDO::PARAM_INT);
			$upit->bindValue(4, $cijena, PDO::PARAM_INT);
			$uspjeh = $upit->execute();

			if (!$uspjeh) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = $veza->prepare("UPDATE utakmice SET domacin=?,gost=?,cijena=? WHERE id=?;");
			$upit->bindValue(1, $domacin, PDO::PARAM_INT);
			$upit->bindValue(2, $gost, PDO::PARAM_INT);
			$upit->bindValue(3, $cijena, PDO::PARAM_INT);
			$upit->bindValue(4, $id, PDO::PARAM_INT);

			$uspjeh = $upit->execute();

			if (!$uspjeh) {
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

	$upit = $veza->prepare("SELECT COUNT(*) FROM korisnici WHERE id=?;");
	$upit->bindValue(1, $id, PDO::PARAM_INT);

	$uspjeh = $upit->execute();
	if (!$uspjeh) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else{
 		$broj = intval($upit->fetchColumn());

 		if ($broj == 0)
 		{
			$upit = $veza->prepare("INSERT INTO korisnici (id, username, password) VALUES (?, ?, ?);");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$upit->bindValue(2, $username, PDO::PARAM_STR);
			$upit->bindValue(3, $pass, PDO::PARAM_STR);
			$rezultat = $upit->execute();
			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
 			$upit = $veza->prepare("UPDATE korisnici SET username=?,password=? WHERE id=?;");
			$upit->bindValue(1, $username, PDO::PARAM_STR);
			$upit->bindValue(2, $pass, PDO::PARAM_STR);
			$upit->bindValue(3, $id, PDO::PARAM_INT);

 			$rezultat = $upit->execute();
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
	
	$upit = $veza->prepare("SELECT COUNT(*) FROM fanklub WHERE id=?;");
	$upit->bindValue(1, $id, PDO::PARAM_INT);

	$rezultat = $upit->execute();
	if (!$rezultat) {
	  $greska = $veza->errorInfo();
      print "SQL greška: " . $greska[2];
      exit();
 	}
 	else
 	{
 		$broj = intval($upit->fetchColumn());

 		if ($broj == 0)
 		{
			$upit = $veza->prepare("INSERT INTO fanklub (id, ime, prezime, telefon, email) VALUES (?, ?, ?, ?, ?);");
			$upit->bindValue(1, $id, PDO::PARAM_INT);
			$upit->bindValue(2, $ime, PDO::PARAM_STR);
			$upit->bindValue(3, $prezime, PDO::PARAM_STR);
			$upit->bindValue(4, $telefon, PDO::PARAM_STR);
			$upit->bindValue(5, $email, PDO::PARAM_STR);
			$rezultat = $upit->execute();

			if (!$rezultat) {
				$greska = $veza->errorInfo();
		        print "SQL greška: " . $greska[2];
		        exit();
		    }
 		}
 		else
 		{
			$upit = $veza->prepare("UPDATE fanklub SET ime=?,prezime=?,telefon=?, email=? WHERE id=?;");
			$upit->bindValue(1, $ime, PDO::PARAM_STR);
			$upit->bindValue(2, $prezime, PDO::PARAM_STR);
			$upit->bindValue(3, $telefon, PDO::PARAM_STR);
			$upit->bindValue(4, $email, PDO::PARAM_STR);
			$upit->bindValue(5, $id, PDO::PARAM_INT);

			$rezultat = $upit->execute();
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