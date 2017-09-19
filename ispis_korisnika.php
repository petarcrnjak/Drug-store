<?php
ob_start();
  header('Content-Type: text/html; charset=utf-8');
  session_start();
 if(!isset($_SESSION['email']))
        {
            header("Location:prijava.php");
            exit();
        }
 if($_SESSION['tipKorisnika']!=1){
     header("Location: error.php?greska=1");
     exit();
 };     
 //else
include_once './baza.class.php';
$baza = new Baza();
$upit = "select * from korisnik where statusKorisnika=1 or statusKorisnika=2";
$podaci = $baza->selectDB($upit);
if($podaci->num_rows>3){
    
$ispis='<ul id="listaKorisnika">';
while($red=$podaci->fetch_array())
{
   $ispis.="<li>";
   $ispis.="<a href=\"detalji_korisnika.php?id=".$red[0]."\">$red[3]</a>";
   $ispis.="</li>";
        $ispis.="<ul>";
            $ispis.="<li>";
            $ispis.=$red["imeKorisnika"];
            $ispis.="</li>";
            $ispis.="<li>";
            $ispis.=$red["prezimeKorisnika"];
            $ispis.="</li>";
            $ispis.="<li>";
            $ispis.=$red["lozinka"];
            $ispis.="</li>";
        $ispis.="</ul>";
}
  $ispis.="</ul>";
  
} 
else {
    $ispis="Tablica nema dosta redova!";

}
//smarty dio;
    require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
    require_once 'ukljuciSmarty.php';
    $smarty = new Smarty();
    $obj= new UkljuciSmarty($smarty);
    $smarty->assign('ispis',$ispis);
    $smarty->display('_header.tpl');
    $smarty->display('popis_korisnika.tpl');
    $smarty->display('_footer.tpl');
    ob_end_flush();  
?>
