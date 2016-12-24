function pretrazi(e) {
	var req = new XMLHttpRequest();

	req.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById('rezultati-pretrage').innerHTML=this.responseText;
    }
  }
  	var upit = document.getElementById('pretraga-polje').value;
    req.open("GET","trazilica.php?pretraga="+upit,true);
	req.send();
}

function dodajTrazilicu()
{
	var rezultatiProstor = document.getElementById('rezultati-pretrage');

	if (rezultatiProstor)
	{	
		document.addEventListener('keyup', pretrazi, false);
	}

}