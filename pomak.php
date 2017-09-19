<?php

session_start();
ob_start();

include_once './baza.class.php';
$baza = new Baza();
$greska = "";
$ispis = "";


$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];

 $url = "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=xml";
    $domdoc = new DOMDocument;
    $domdoc->load($url);

    $x = $domdoc->documentElement;
    foreach ($x->childNodes as $i) {
        $a = $i;
        foreach ($a->childNodes as $j) {
            $sati = $j->nodeValue;
        }
    }

$vrijeme_servera = time();
$vrijeme_sustava = $vrijeme_servera + ($sati * 60 * 60);
$ispis = "Stvarno vrijeme servera: " . date('d.m.Y H:i:s', $vrijeme_servera) . "<br>";
$greska = "Virtualno vrijeme sustava: " . date('d.m.Y H:i:s', $vrijeme_sustava) . "<br>";
$virtualno=date('Y-m-d H:i:s', $vrijeme_sustava);
if(1==1){
$upit3 = "update Vrijeme set pomak='" . $sati . "',virtualno='" .$virtualno. "' where idVrijeme = '1'";
$baza->updateDB($upit3);
//dnevnik
$korisnik = $_SESSION['id_korisnik'];
$datum = date("Y-m-d ");
//$time = vrati();
$vrijeme = date("H:i:s");//, $time);
$url = $_SERVER['PHP_SELF'];
$upitdn = str_replace("'", "", $upit3);
$radnja = "Preuzimanje virtualnog vremena";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->updateDB($dnevnik);
//return $sati;
}
function vrati() {
    include_once './baza.class.php';
   $baza = new Baza();
   $upit = "SELECT pomak FROM Vrijeme WHERE idVrijeme = '1'";
    $rezultat = $baza->select($upit);
   list($pomak) = mysqli_fetch_array($rezultat);
    return time() + ($pomak * 3600);
}

require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('ispis', $ispis);

$smarty->display('_header.tpl');
$smarty->display('pomak.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  
