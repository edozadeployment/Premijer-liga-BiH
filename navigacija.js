var sadrzaj = document.getElementById("sadrzaj");
var request = new XMLHttpRequest();
var otvorenaStranica = "vijesti.php";
request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			sadrzaj.innerHTML = request.responseText;

			dodajValidaciju();
			dodajPopup();

			switch (otvorenaStranica)
			{
				case "onama.php":
					povuciONama();
					break;
				case "fanklub.php":
					povuciFanKlub();
					break;
				case "ulaznice.php":
					povuciUlaznice();
					break;
			}
		}
	}

var pocetni = document.getElementById("vijesti");
otvori(pocetni, "vijesti.php");

function otvori(elem, stranica)
{
	request.open("GET", stranica, true);
	request.setRequestHeader('Content-type', 'text/html');
	request.send();

	otvorenaStranica = stranica;

	var navigacija = document.getElementsByClassName("link-navigacije");

	for(var i = 0; i < navigacija.length; ++i)
	{
		   navigacija[i].className = navigacija[i].className.replace(/(?:^|\s)selected(?!\S)/g , '');
	}

	if (elem.id != "admin-link")
	{
		elem.className += " selected";
	}
}