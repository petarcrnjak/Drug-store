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
    exit();
}

$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];
$oblik = "Y-m-d H:i:s";
$vrijeme1 = new DateTime(date($oblik));
$vrijeme2 = $vrijeme1->format($oblik);
$lijekovi = $_GET['lijekovi'];
$like = "L";

$upit1 = "select * from Lajkovi as l where l.Lijekovi='" . $lijekovi . "' and l.Korisnik='" . $korisnik . "'";
$rezultat1 = $baza->selectDB($upit1);
if ($rezultat1->num_rows == 0) {
    $upit3 = "insert into Lajkovi (Lijekovi,Korisnik,lajk,datum)"
            . "values ('{$lijekovi}','{$korisnik}', '{$like}', '{$vrijeme2}')";
    if ($rezultat = $baza->updateDB($upit3)) {
        $greska.="Uspješno ste lajkali";
                          //dohvat virtualnog
$upit = "SELECT pomak FROM Vrijeme WHERE idVrijeme = '1'";
$rezultat = $baza->selectDB($upit);
$pomak = mysqli_fetch_array($rezultat);
$time = time() + ($pomak[0] * 3600);
//dnevnik
$korisnik = $_SESSION['id_korisnik'];
$datum = date("Y-m-d ",$time);
$vrijeme = date("H:i:s", $time);
$url = $_SERVER['PHP_SELF'];
$upitdn = str_replace("'", "", $upit3);
$radnja = "Lajkanje";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);

        // header("Location:rezervacija.php");
        header("refresh:3; url=rezervacija.php");
    }
} else {
    $upit3 = "update Lajkovi "
            . "set lajk='{$like}',datum='{$vrijeme2}'
          where Lajkovi.Lijekovi='" . $lijekovi . "' and Lajkovi.Korisnik='" . $korisnik . "'";
    if ($rezultat = $baza->updateDB($upit3)) {
        $greska.="Uspješno ste opet lajkali!";
       //dohvat virtualnog
                   //dohvat virtualnog
$upit = "SELECT pomak FROM Vrijeme WHERE idVrijeme = '1'";
$rezultat = $baza->selectDB($upit);
$pomak = mysqli_fetch_array($rezultat);
$time = time() + ($pomak[0] * 3600);
//dnevnik
$korisnik = $_SESSION['id_korisnik'];
$datum = date("Y-m-d ",$time);
$vrijeme = date("H:i:s", $time);
$url = $_SERVER['PHP_SELF'];
$upitdn = str_replace("'", "", $upit3);
$radnja = "Lajkanje";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);
        // header("Location:rezervacija.php");
        header("refresh:3; url=rezervacija.php");
    }
}

require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->display('_header.tpl');
$smarty->display('like.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  