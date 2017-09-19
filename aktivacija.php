<?php
ob_start();
  header('Content-Type: text/html; charset=utf-8');
  include_once './baza.class.php';
  $greska="";
  
$baza = new Baza();

if (empty($_GET['aktivacijskiKod'])) {
$greska.= "Morate upotrijebiti aktivacijski kod. <br>";
}

if (isset($_GET['aktivacijskiKod'])) {
$poruka="";
$aktivacijski_kod = $_GET['aktivacijskiKod'];
$upit = "SELECT * from Korisnik where aktivacijskiKod = '{$aktivacijski_kod}' limit 1";
$rezultat1 = $baza ->selectDB($upit);
$lista = $rezultat1->fetch_array();
//dohvat korsinicko ime
$upit1 = "SELECT korisnickoIme from Korisnik where aktivacijskiKod = '{$aktivacijski_kod}' limit 1";
$rezultat2 = $baza ->selectDB($upit1);
$korisnickoIme = $rezultat2->fetch_array();
if($rezultat1->num_rows==0){
    echo 'Neispravan kod ili je korisnicki racun vec aktiviran. <br>';
    die();
}
//dohvat virtualnog
$upit = "SELECT pomak FROM Vrijeme WHERE idVrijeme = '1'";
$rezultat = $baza->selectDB($upit);
$pomak = mysqli_fetch_array($rezultat);
$time = time() + ($pomak[0] * 3600);
$istek = 12;
$nacin = "Y-m-d H:i:s";
//$vrijeme = new DateTime(date("Y-m-d H:i:s", $time));
$formatirano_vrijeme = date("Y-m-d H:i:s",$time);
$vrijemeRegistracije = date($nacin, strtotime($lista['vrijemeRegistracije'] . " +$istek hour"));

if($vrijemeRegistracije < $formatirano_vrijeme){
    echo "Proslo je vrijeme za aktivaciju racuna (12 sati). <br>";
    die();
}

$upitUP = "UPDATE Korisnik set statusKorisnika = '1',tipKorisnika='1'  WHERE korisnickoIme = '".$korisnickoIme[0]."'";
if($baza->updateDB($upitUP)){
$greska= 'Aktivacija je uspješna. <br>';
header( "refresh:3; url=prijava.php" );
//header("Location: prijava.php");
}
else {
   $greska= 'Greska kod aktiviranja. <br>';
}
}
 
     require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
     require_once 'ukljuciSmarty.php';
    $smarty = new Smarty();
    $obj= new UkljuciSmarty($smarty);
    $smarty->assign('greska',$greska);
    $smarty->display('_header.tpl');
    $smarty->display('aktivacija.tpl');
    $smarty->display('_footer.tpl');

ob_end_flush();
 ?>   