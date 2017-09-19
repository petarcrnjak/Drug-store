<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$baza = new Baza();
$greska = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    // header("refresh:2; url=prijava.php");
    exit();
}
$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];

$ulaz = "select * from Korisnik as k join tipKorisnika as t on k.tipKorisnika=t.idtipKorisnika"
        . " where k.id_korisnik='$korisnik' and k.tipKorisnika='3'";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
} else {
    header("Location:http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html");
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