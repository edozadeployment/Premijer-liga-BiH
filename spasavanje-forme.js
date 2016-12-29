function sacuvajONama()
{
	localStorage.setItem("onama_ime", document.getElementById("ime").value);
	localStorage.setItem("onama_email", document.getElementById("email").value);
	localStorage.setItem("onama_telefon", document.getElementById("telefon").value);
	localStorage.setItem("onama_tekst", document.getElementById("tekst").value);

	alert("Uspješno sačuvan sadržaj forme");
}

function povuciONama()
{
	document.getElementById("ime").value = localStorage.getItem("onama_ime");
	document.getElementById("email").value = localStorage.getItem("onama_email");
	document.getElementById("telefon").value = localStorage.getItem("onama_telefon");
	document.getElementById("tekst").innerHTML = localStorage.getItem("onama_tekst");
}

function sacuvajFanKlub()
{
	localStorage.setItem("fanklub_ime", document.getElementById("ime").value);
	localStorage.setItem("fanklub_prezime", document.getElementById("prezime").value);
	localStorage.setItem("fanklub_email", document.getElementById("email").value);
	localStorage.setItem("fanklub_telefon", document.getElementById("telefon").value);

	alert("Uspješno sačuvan sadržaj forme");
}

function povuciFanKlub()
{
	document.getElementById("ime").value = localStorage.getItem("fanklub_ime");
	document.getElementById("prezime").value = localStorage.getItem("fanklub_prezime");
	document.getElementById("email").value = localStorage.getItem("fanklub_email");
	document.getElementById("telefon").value = localStorage.getItem("fanklub_telefon");
}

function sacuvajUlaznice()
{
	localStorage.setItem("ulaznice_kolo", document.getElementById("kolo").value);
	localStorage.setItem("ulaznice_utakmica", document.getElementById("utakmica").selectedIndex);
	localStorage.setItem("ulaznice_tribina", document.getElementById("tribina").selectedIndex);
	localStorage.setItem("ulaznice_brojulaznica", document.getElementById("brojulaznica").value);

	alert("Uspješno sačuvan sadržaj forme");
}

function povuciUlaznice()
{
	if (document.getElementById("utakmica"))
	{
		document.getElementById("utakmica").selectedIndex = localStorage.getItem("ulaznice_utakmica");
		document.getElementById("tribina").selectedIndex = localStorage.getItem("ulaznice_tribina");
		document.getElementById("brojulaznica").value = localStorage.getItem("ulaznice_brojulaznica");
	}
}