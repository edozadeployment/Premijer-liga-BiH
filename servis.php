<?php
function zag() {
    header("{$_SERVER['SERVER_PROTOCOL']} 200 OK");
    header('Content-Type: text/html');
    header('Access-Control-Allow-Origin: *');
}

if ($_SERVER['REQUEST_METHOD'] != "GET")
{
	header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
}
else{
	$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
	//$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");

	zag();
	$utakmice = [];
	if (!isset($_GET["id"]))
	{
			print "{\n";
			print "\"status\": 0,\n";
			print "\"statusOpis\": " . "\"nije proslijeđen id\",\n";
			print "\"utakmice\": " . json_encode($utakmice, JSON_PRETTY_PRINT);
			print "\n}";
			exit();
	}

	$id = $_GET["id"];
//	$upit = $veza->prepare("SELECT * FROM tabela WHERE id=?");
//	$upit->bindValue(1, $id, PDO::PARAM_INT);
//	$uspjeh = $upit->execute();

	$upit2 = $veza->prepare("SELECT utakmice.id, tabela1.naziv AS domacin, tabela2.naziv AS gost, utakmice.cijena AS cijena FROM utakmice JOIN tabela tabela1 ON tabela1.id = utakmice.domacin JOIN tabela tabela2 ON tabela2.id = utakmice.gost WHERE tabela1.id=? OR tabela2.id=?;");
	
	$upit2->bindValue(1, $id, PDO::PARAM_INT);
	$upit2->bindValue(2, $id, PDO::PARAM_INT);
	$uspjeh2 = $upit2->execute();	
	if (!$uspjeh2)
	{
			print "{\n";
			print "\"status\": 0,\n";
			print "\"statusOpis\": " . "\"greška baze podataka\",\n";
			print "\"utakmice\": " . json_encode($utakmice, JSON_PRETTY_PRINT);
			print "\n}";
			exit();
	}

//	$klub = $upit->fetch(PDO::FETCH_ASSOC);
	$utakmice = $upit2->fetchAll(PDO::FETCH_ASSOC);

/*	if (!$klub)
	{
			print "{\n";
			print "\"status\": 0,\n";
			print "\"statusOpis\": " . "\"nepostojeći id\",\n";
			print "\"utakmice\": " . json_encode($utakmice, JSON_PRETTY_PRINT);
			print "\n}";
			exit();
	}*/
	
	print "{\n";
	print "\"status\": 1,\n";
	print "\"statusOpis\": " . "\"uspjeh\",\n";
//	//print "\"klub\": " . json_encode($klub, JSON_PRETTY_PRINT) . ",\n" ;
	print "\"utakmice\": " . json_encode($utakmice, JSON_PRETTY_PRINT);
	print "\n}";
}

?>