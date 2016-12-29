function provjeriFormu(e) {
	var greska = document.getElementById('greska');
	if (greska)
	{
		greska.innerHTML=""; // ocistimo prethodne greske		
	}
	
	//var forma=document.getElementsByTagName('form');
	var forma = e;
	    var imeRegex = /^[a-zA-Z]{2,15}$/;
		var imeInput = forma['ime'];
		if (imeInput && !imeRegex.test(imeInput.value)) {
	            greska.innerHTML+="Ime mora imati 2-15 slova<br>";
	            //e.preventDefault();
				return false;
		}

		var prezimeInput = forma['prezime'];
		if (prezimeInput && !imeRegex.test(prezimeInput.value)) {
	            greska.innerHTML+="Prezime mora imati 2-15 slova<br>";
	            //e.preventDefault();
				return false;
		}

	    var emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	    var emailInput = forma['email'];
	    if (emailInput && !emailRegex.test(emailInput.value)) {
				greska.innerHTML+="Email adresa nije ispravna<br>";  
	            //e.preventDefault(); 
				return false;
		}

		var telefonInput = forma['telefon'];
		var telefonRegEx = /^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{3})$/;
		if (telefonInput && !telefonRegEx.test(telefonInput.value)) {
				greska.innerHTML+="Telefon format: (061)-123-345 ili 061-123-456 ili 061123456<br>";  
	            //e.preventDefault(); 
				return false;
		}
		
		var tekstInput = forma['tekst'];
		if (tekstInput && tekstInput.value.length < 5) {
				greska.innerHTML+="Poruka ne može imati manje od 5 znakova";  
	            //e.preventDefault(); 
				return false;
		}


		var tribinaInput = e.target['tribina'];
		if (tribinaInput && !tribinaInput.value) {
				greska.innerHTML+="Morate izabrati tribinu";  
	            e.preventDefault(); 
				return false;
		}

		var utakmicaInput = e.target['utakmica'];
		if (utakmicaInput && utakmicaInput.value >= 0) {
				greska.innerHTML+="Morate izabrati utakmicu";  
	            e.preventDefault(); 
				return false;
		}

		var brulaznicaInput = e.target['brojulaznica'];
		if (brulaznicaInput && ((!brulaznicaInput.value) || brulaznicaInput.value < brulaznicaInput.min || brulaznicaInput.value > brulaznicaInput.max))
		{
				greska.innerHTML+="Morate izabrati broj ulaznica (između 1 i 8)";  
	            e.preventDefault(); 
				return false;
		}

	var naslovRegex = /^([a-zA-Z0-9.,:! ]){5,60}$/;
	var naslovInput = forma['naslov'];
	if (naslovInput && !naslovRegex.test(naslovInput.value)) {
			var ova_greska = document.getElementById('edit-greska');
			
			if (forma.id == "pisanje-vijesti")
			{
				ova_greska = document.getElementById('nova-vijest-greska');
			}

            ova_greska.innerHTML="Nije validan unos. Naslov može sadržavati samo 5-60 alfanumeričkih znakova, razmaka i znakova interpunkcija.";
            //e.preventDefault();
			return false;
	}

	var tekstRegex = /^[a-zA-Z0-9.,!: \n]{30,800}$/;
	var tekstInput = forma['tekst-vijesti'];
	if (tekstInput && !tekstRegex.test(tekstInput.value)) {
			var ova_greska = document.getElementById('edit-greska');
            ova_greska.innerHTML="Nije validan unos. Tekst može sadržavati samo 30-800 alfanumeričkih znakova, razmaka i znakova interpunkcija.";
            //e.preventDefault();
			return false;
	}

	var username = e.target['username'];
	var pass = e.target['password'];

	if((username && pass) && (username.value.length < 1 || pass.value.length < 1))
	{
		document.getElementById("greska").innerHTML = "Morate unijeti username i password.";
        e.preventDefault();
		return false;
	}

	return true;
}

function validacijaKluba(e, greskaId)
{
	var nazivRegex = /^[a-zA-Z0-9. ]{2,20}$/;
	var input = e['naziv'];
	if (input && !nazivRegex.test(input.value)) {
			var greska = document.getElementById(greskaId);
            greska.innerHTML= " Naziv kluba može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
            //e.preventDefault();
			return false;
	}

	input = e['bodovi'];
	if (input && !((!isNaN(parseFloat(input.value)) && isFinite(input.value)) && (parseInt(input.value) >= 0) && (parseInt(input.value) <= 99)))
	{
		var greska = document.getElementById(greskaId);
        greska.innerHTML= " Bodovi moraju biti broj 0-99.";
        //e.preventDefault();
		return false;
	}

	return true;
}

function validacijaIgraca(e, greskaId)
{
	var imeRegex = /^[a-zA-Z ]{2,25}$/;
	var input = e['ime'];
	if (input && !imeRegex.test(input.value)) {
			var greska = document.getElementById(greskaId);
            greska.innerHTML= "Ime mora biti niz slova (+ razmaci) u dužini od 2 do 25 karaktera";
            //e.preventDefault();
			return false;
	}

	var nazivRegex = /^[a-zA-Z0-9. ]{2,20}$/;
	var input = e['tim'];
	if (input && !nazivRegex.test(input.value)) {
			var greska = document.getElementById(greskaId);
            greska.innerHTML= " Naziv kluba može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
            //e.preventDefault();
			return false;
	}

	input = e['golovi'];
	if (input && !((!isNaN(parseFloat(input.value)) && isFinite(input.value)) && (parseInt(input.value) >= 0) && (parseInt(input.value) <= 99)))
	{
		var greska = document.getElementById(greskaId);
        greska.innerHTML= " Golovi moraju biti broj 0-99.";
        //e.preventDefault();
		return false;
	}

	var greske = document.getElementsByClassName('greska');
	for (var i = 0; i < greske.length; i++)
	{
		greske[i].innerHTML = "";
	}

	return true;
}



function validacijaUtakmice(e, greskaId)
{
	var greska = document.getElementById(greskaId);



	var regex = /^[a-zA-Z0-9. ]{2,20}$/;
	var input = e["domacin"];

	if (input && !regex.test(input.value)) {
            greska.innerHTML= "Naziv kluba (domacin) može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
            //e.preventDefault();
			return false;
	}

	var regex = /^[a-zA-Z0-9. ]{2,20}$/;
	var input = e["gost"];

	if (input && !regex.test(input.value)) {
            greska.innerHTML= "Naziv kluba (gost) može sadržavati samo slova, brojeve i razmake, u dužini od 2 do 20 karaktera.";
            //e.preventDefault();
			return false;
	}

	var input = e['cijena'];
	if (input && !((!isNaN(parseFloat(input.value)) && isFinite(input.value)) && (parseInt(input.value) >= 1) && (parseInt(input.value) <= 99))){
            greska.innerHTML= "Cijena mora biti broj 1-99KM.";
            //e.preventDefault();
			return false;
	}

	return true;
}

function validacijaLogina(e)
{
	var username = e['username'];
	var pass = e['password'];

	if(username.value.length < 1 || pass.value.length < 1)
	{
		document.getElementById("greska").innerHTML = "Morate unijeti username i password.";
		return false;
	}

	return true;
}



function dodajValidaciju()
{
	var forma=document.getElementsByTagName('form')[0];

	if (forma)
		forma.addEventListener("submit", provjeriFormu, false);	
}


function provjeriVijestFormu(forma, greska_id)
{
	var greska = document.getElementById(greska_id);

}