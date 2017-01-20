<!doctype html>
<html lang="bs">
<head>
<script type="text/javascript" src="spasavanje-forme.js"></script>
<script type="text/javascript" src="forma-validacija.js"></script>
<script type="text/javascript" src="popup.js"></script>
<script type="text/javascript" src="trazilica.js"></script>
<script type="text/javascript" src="posiljalac-zahtjeva.js"></script>
<meta charset = "utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BHT Premijer liga</title>
<link rel="stylesheet" type="text/css" href="grid.css">
<link rel="stylesheet" type="text/css" href="izgled.css">
<link rel="stylesheet" type="text/css" href="popup.css">
<link rel="stylesheet" type="text/css" href="stadioni.css">
<link rel="stylesheet" type="text/css" href="onama.css">
</head>
 
<body>
		<?php

		session_start();
		unset($_SESSION["pogresan_pass"]);
		
		if (isset($_REQUEST["logout"]))
		{
			unset($_SESSION["username"]);
		}
		else
		{
			$username;
			if (isset($_SESSION["username"]))
			{
				$username = $_SESSION["username"];
			}
			else if (isset($_REQUEST["username"]) && isset($_REQUEST["password"]))
			{
				$username = $_REQUEST["username"];
				$password = $_REQUEST["password"];
				$veza = new PDO("mysql:dbname=bhpliga;host=mysql-55-centos7;charset=utf8", "edo", "pass123");
				//$veza = new PDO("mysql:dbname=bhpliga;host=localhost;charset=utf8", "edo", "pass123");

				$veza->exec("set names utf8");
				$upit = $veza->prepare("SELECT * FROM korisnici WHERE username=? AND password=?;");
				$upit->bindValue(1, $username, PDO::PARAM_STR);
				$upit->bindValue(2, $password, PDO::PARAM_STR);
				$upit->execute();

				$korisnik = $upit->fetch();

				if ($korisnik)
				{
					$_SESSION["username"] = $username;
				}
				else
				{
					unset($_SESSION["username"]);
					$_SESSION["pogresan_pass"] = true;
				}
			}

		}
		?>

<div class="red navigacija-wrap">
	<div class="kolona jedan naslov-stranice">

		<?php
			if (!isset($_SESSION["username"]))
			{
				$prikaz = "<span id=\"logout-forma\">";
				if (isset($_SESSION["pogresan_pass"]) && $_SESSION["pogresan_pass"] == true)
					$prikaz = $prikaz."Unijeli ste neispravnu kombinaciju username/password. ";

				 print $prikaz."<a onclick=\"otvori(this, 'admin-login.php')\" href=\"#\">Admin</a>"."</span>";
			}
			else
			{
				print "<a href=\"import-skripta.php\">Importuj podatke u bazu</a>";
				print "<form method='post' action='index.php' id='logout-forma'>";
				print "		<input type='hidden' name='logout'>";
				print "		<input type='submit' value='Logout'>";
				print "</form>";
			}
		?>
		<h1>Premijer liga BiH</h1>
	</div>
	<div class="kolona tri navigacija-kolona">
	<nav>
		<ul class="navigacija">
			<li><a id="vijesti" class="link-navigacije selected" onclick="otvori(this, 'vijesti.php')" href="#">Vijesti</a></li>
			<li><a class="link-navigacije" onclick="otvori(this, 'statistika.php')" href="#">Liga</a></li>
			<li><a class="link-navigacije" onclick="otvori(this, 'ulaznice.php')" href="#">Kupovina ulaznica</a></li>
			<li><a class="link-navigacije" onclick="otvori(this, 'fanklub.php')" href="#">Fan klub</a></li>
			<li><a class="link-navigacije" onclick="otvori(this, 'stadioni.php')" href="#">Stadioni</a></li>
			<li><a class="link-navigacije" onclick="otvori(this, 'onama.php')" href="#">O nama</a></li>
		</ul>
	</nav>
	</div>
</div>

<div id="sadrzaj" class="red">
</div>

 <div class="red dno">
 	<div id="navtable">
 		<div class="navrow">
 			<p><a href="#top"><img src="top.png" title="Idi na vrh stranice" alt="top"></a></p>
 		</div>
 		<div class="navrow">
 			<p><a href="#top">Top</a></p>
 		</div>
 	</div>

	<footer>
		<p>Napravio: Edo Imamović.</p>
	</footer>
 </div>
</body>

<script type="text/javascript" src="navigacija.js"></script>
</html>