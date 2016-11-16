var sadrzaj = document.getElementById("sadrzaj");
var request = new XMLHttpRequest();
request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			sadrzaj.innerHTML = request.responseText;

		dodajValidaciju();
		}
	}

var pocetni = document.getElementById("vijesti");
otvori(pocetni, "vijesti.html");

function otvori(elem, stranica)
{
	request.open("GET", stranica, true);
	request.setRequestHeader('Content-type', 'text/html');
	request.send();

	var navigacija = document.getElementsByClassName("link-navigacije");

	for(var i = 0; i < navigacija.length; ++i)
	{
		   navigacija[i].className = navigacija[i].className.replace(/(?:^|\s)selected(?!\S)/g , '');
	}

	elem.className += " selected";
}