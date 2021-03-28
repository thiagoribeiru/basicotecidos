<?
$url_vet = explode('/',str_replace('\\','/',getcwd()));
if (array_search('admin',$url_vet)==true or array_search('cliente',$url_vet)==true) {
    $voltaPasta = "../";
} else {
    $voltaPasta = "";
}
?>
<div id="debug">
    <div class="exit">
        <img src=<?echo $voltaPasta."images/cmd_icon.png";?> class="icon">
        Pront Debugger v0.1
        <img src=<?echo $voltaPasta."images/fechar.png";?> class="sair">
        <img src=<?echo $voltaPasta."images/minimizar.png";?> class="minimizar">
        <img src=<?echo $voltaPasta."images/maximizar.png";?> class="maximizar">
    </div>
    <div class="pront"></div>
</div>