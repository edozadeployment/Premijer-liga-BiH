function submitForm(oFormElement)
{
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById('sadrzaj').innerHTML=this.responseText;
    }
  }

  xhr.open (oFormElement.method, oFormElement.action, true);
  xhr.send (new FormData (oFormElement));
  return false;
}