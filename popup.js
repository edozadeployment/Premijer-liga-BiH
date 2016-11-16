// Get the modal
var popup = document.getElementById('popup');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('popup-slika');
var slike = document.getElementsByClassName('slika-stadiona');
var popupSlika = document.getElementById("popup-slika");

for (var i = 0; i < slike.length; i++)
{
	slike[i].onclick = function()
	{
		popupSlika.src = this.src;
		popup.style.display = "block";
	}
}

function doc_keyUp(e) {

    // this would test for whichever key is 40 and the ctrl key at the same time
    if (e.keyCode == 27) {
		popup.style.display = "none";
    }
}
// register the handler 
document.addEventListener('keyup', doc_keyUp, false);