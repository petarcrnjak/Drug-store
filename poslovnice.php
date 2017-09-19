<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$baza = new Baza();
$greska = "";
$ispis = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}
$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];
$ulaz = "select * from Korisnik as k join tipKorisnika as t on k.tipKorisnika=t.idtipKorisnika"
        . " where k.id_korisnik='$korisnik' and (k.tipKorisnika='2' or k.tipKorisnika='3')";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo moderator i administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
} else {
    $upitAkcija = "select p.idPoslovnice,k.korisnickoIme,p.broj,p.ulica,p.grad,p.drzava, radno_vrijeme from Poslovnice as p join Korisnik as k"
            . " on k.id_korisnik=p.upisao group by 1";

    $podaci = $baza->selectDB($upitAkcija);
    if ($podaci->num_rows != 0) {
        echo "<table border=1 class=db-table>";
        echo "<caption>Lijekovi</caption>";
        echo "<thead>"
        . "<th>id_poslovnice</th>"
        . "<th>upisao</th>"
        . "<th>broj</th>"
        . "<th>ulica</th>"
        . "<th>grad</th>"
        . "<th>drzava</th>"
        . "<th>radno_vrijeme</th>"
        . "</thead>";
        while ($red = $podaci->fetch_array()) {
            echo "<tr>"
            . "<td>$red[0]</td>"
            . "<td>$red[1]</td>"
            . "<td>$red[2]</td>"
            . "<td>$red[3]</td>"
            . "<td>$red[4]</td>"
            . "<td>$red[5]</td>"
            . "<td>$red[6]</td>"
            . "</td>";
            echo "</tr>";
        }
    } else {
        $greska.= "Nema poslovnica <br> ";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $broj = $_POST['broj'];
        $ulica = $_POST['ulica'];
        $grad = $_POST['grad'];
        $drzava = $_POST['drzava'];
        $radno = $_POST['radno'];

        if ((empty($_POST['broj']) || empty($_POST['ulica']) || empty($_POST['grad']) || empty($_POST['drzava']) || empty($_POST['radno']))) {
            $greska.= "Unesite sve vrijednosti<br>";
        } else {
            $upit3 = "insert into Poslovnice (idPoslovnice,upisao,broj,ulica,grad,drzava,radno_vrijeme) values "
                    . " (default,'" . $korisnik . "', '" . $broj . "','" . $ulica . "','" . $grad . "','" . $drzava . "', '" . $radno . "')";

            if ($baza->updateDB($upit3)) {
                $greska.= "Uspjesno dodana lokacija poslovnice! <br> ";
                //dnevnik
                $korisnik = $_SESSION['id_korisnik'];
                $datum = date("Y-m-d ");
                $vrijeme = date("H:i:s");
                $url = $_SERVER['PHP_SELF'];
                $upitdn = str_replace("'", "", $upit3);
                $radnja = "Definiranje adresa poslovnica";
                $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                $baza->selectDB($dnevnik);
                header("refresh:3; url=poslovnice.php");
            }
        }
    }
}
require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('ispis', $ispis);

$smarty->display('_header.tpl');
$smarty->display('poslovnice.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  