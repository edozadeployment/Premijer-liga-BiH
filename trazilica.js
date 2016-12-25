function pretrazi(e) {
	var req = new XMLHttpRequest();

	req.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById('pretraga-sugestija').innerHTML=this.responseText;
    }
  }
  	var upit = document.getElementById('pretraga-polje').value;
    req.open("GET","trazilica.php?pretraga="+upit,true);
	req.send();
}

function trazi_glavno(e)
{
	var req = new XMLHttpRequest();

	req.onreadystatechange=function() {
	    if (this.readyState==4 && this.status==200) {
	      //document.getElementById('pretraga-rezultati').innerHTML=this.responseText;
      document.getElementById('pretraga-sugestija').innerHTML=this.responseText;
	      
    	}
  	}
  	var upit =  "sve=1&pretraga=" + document.getElementById('pretraga-polje').value;
    req.open("GET","trazilica.php?" +upit,true);
	req.send();	
}

function dodajTrazilicu()
{
	var rezultatiProstor = document.getElementById('pretraga-sugestija');

	if (rezultatiProstor)
	{	
		document.addEventListener('keyup', pretrazi, false);
	}

}