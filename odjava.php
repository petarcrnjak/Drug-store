<?php

session_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$baza = new Baza();
$greska = "";
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
//$upitdn = str_replace("'", "", $upit3);
$radnja = "Odjava";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);

session_unset();
session_destroy();
header("Location:prijava.php");
?>