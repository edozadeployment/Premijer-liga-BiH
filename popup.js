function doc_keyUp(e) {

    if (e.keyCode == 27) {
		popup.style.display = "none";
    }
}

function dodajPopup()
{
	var popup = document.getElementById('popup');

	if (popup)
	{
		var img = document.getElementById('popup-slika');
		var slike = document.getElementsByClassName('slika-stadiona');
		var popupSlika = document.getElementById("popup-slika");

		for (var i = 0; i < slike.length; i++)
		{
			slike[i].onclick = function(el)
			{
				popupSlika.src = el.target.src;
				popup.style.display = "block";
			}
		}
		document.addEventListener('keyup', doc_keyUp, false);
	}
}
