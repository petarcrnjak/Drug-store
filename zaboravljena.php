<?php

ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$greska = "";

$baza = new Baza();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (empty($_POST['email'])) {
        $greska.= "Morate unesti emal! <br>";
    } else {
        $aktivacijskikod = md5($email . time());

        $upit3 = "update Korisnik set lozinka='{$aktivacijskikod}' where email='" . $email . "'";
        $upit1 = "update Korisnik set potvrdaLozinke='{$aktivacijskikod}' where email='" . $email . "'";
        if ($baza->updateDB($upit3)) {
            if ($baza->updateDB($upit1)) {
                $primatelj = $email;
                $naslov = "Nova lozinka";
                $poruka = "Postovani,\n Ovdje vam je nova lozinka\n lozinka: " . $aktivacijskikod . "";
                mail($primatelj, $naslov, $poruka);
                $greska = "Uspjesno poslana nova lozinka, provjerite mail!";

                //dnevnik
                $upitK = "select k.id_korisnik from Korisnik as k where k.email='" . $email . "'";
                $rezultatK = $baza->selectDB($upitK);
                if ($rezultatK->num_rows != 0) {
                    $korisnikK = mysqli_fetch_row($rezultatK);
                    $datum = date("Y-m-d ");
                    $vrijeme = date("H:i:s");
                    $url = $_SERVER['PHP_SELF'];
                    $upitdn = str_replace("'", "", $upit3);
                    $radnja = "Zahtjev za novom lozinkom";
                    $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                            . " ('{$korisnikK[0]}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                    $baza->selectDB($dnevnik);
                    header("refresh:3; url=prijava.php");
                    //header("Location: prijava.php"); 
                }
            }
        } else {
            $greska.= "Greska pri radu s bazom podataka <br>";
        }
    }
}

require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->display('_header.tpl');
$smarty->display('zaboravljena.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>   