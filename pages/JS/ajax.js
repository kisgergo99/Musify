function showMenu(path){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("framepage").innerHTML = this.responseText;
      }
    };
    console.log(path);
    xmlhttp.open("GET", "/musify/pages/home/menuquery.php?menu="+path, true);
    xmlhttp.send();
}
