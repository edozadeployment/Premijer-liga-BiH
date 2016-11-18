function provjeriFormu(e) {
	var greska = document.getElementById('greska');
	greska.innerHTML=""; // ocistimo prethodne greske
	
	var forma=document.getElementsByTagName('form')[0];
    
    var imeRegex = /^[a-zA-Z]{2,15}$/;
	var imeInput = forma['ime'];
	if (imeInput && !imeRegex.test(imeInput.value)) {
            greska.innerHTML+="Ime mora imati 2-15 karaktera<br>";
            e.preventDefault();
			return false;
	}

	var prezimeInput = forma['prezime'];
	if (prezimeInput && !imeRegex.test(prezimeInput.value)) {
            greska.innerHTML+="Prezime mora imati 2-15 karaktera<br>";
            e.preventDefault();
			return false;
	}

    var emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    var emailInput = forma['email'];
    if (emailInput && !emailRegex.test(emailInput.value)) {
			greska.innerHTML+="Email adresa nije ispravna<br>";  
            e.preventDefault(); 
			return false;
	}

	var telefonInput = forma['telefon'];
	var telefonRegEx = /^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{3})$/;
	if (telefonInput && !telefonRegEx.test(telefonInput.value)) {
			greska.innerHTML+="Telefon format: (061)-123-345 ili 061-123-456 ili 061123456<br>";  
            e.preventDefault(); 
			return false;
	}
	
	var tekstInput = forma['tekst'];
	if (tekstInput && tekstInput.value.length < 5) {
			greska.innerHTML+="Poruka ne može imati manje od 5 znakova";  
            e.preventDefault(); 
			return false;
	}

	var koloInput = forma['kolo'];
	if (koloInput && (!koloInput.value || koloInput.max < koloInput.value || koloInput.min > koloInput.value )) {
			greska.innerHTML+="Morate izabrati (ispravno) kolo";  
            e.preventDefault(); 
			return false;
	}

	var tribinaInput = forma['tribina'];
	if (tribinaInput && !tribinaInput.value) {
			greska.innerHTML+="Morate izabrati tribinu";  
            e.preventDefault(); 
			return false;
	}

	var utakmicaInput = forma['utakmica'];
	if (utakmicaInput && !utakmicaInput.value) {
			greska.innerHTML+="Morate izabrati utakmicu";  
            e.preventDefault(); 
			return false;
	}

	var brulaznicaInput = forma['brojulaznica'];
	if (brulaznicaInput && (!brulaznicaInput.value) || brulaznicaInput.value < brulaznicaInput.min || brulaznicaInput.value > brulaznicaInput.max) {
			greska.innerHTML+="Morate izabrati broj ulaznica (između 1 i 8)";  
            e.preventDefault(); 
			return false;
	}

	alert("Uspješno popunjena forma");
	return true;
}


function dodajValidaciju()
{
	var forma=document.getElementsByTagName('form')[0];

	if (forma)
		forma.addEventListener("submit", provjeriFormu, false);	
}