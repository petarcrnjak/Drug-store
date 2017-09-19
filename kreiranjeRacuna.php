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
// header("Location:prijava.php");
    header("Location:prijava.php");
    exit();
}
$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];

$ulaz = "select * from Korisnik as k join tipKorisnika as t on k.tipKorisnika=t.idtipKorisnika"
        . " where k.id_korisnik='$korisnik' and k.tipKorisnika='2' or '3' ";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo moderator i administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
} else {
    $upit = "select * from Korisnik as k join tipKorisnika as t on  "
            . " k.tipKorisnika=t.idtipKorisnika where k.id_korisnik='" . $korisnik . "'and k.tipKorisnika='2' or '3'";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate ovlasti<br>";
        header("Location:prijava.php");
    }


    $upitAkcija = "select r.idRacuni,k.ime,k.prezime,r.iznos,r.datum,r.vrijeme "
            . "from Racuni as r join Korisnik as k on k.id_korisnik=r.Korisnik where r.Korisnik='" . $korisnik . "' ";

    $podaci = $baza->selectDB($upitAkcija);

    if ($podaci->num_rows != 0) {
        echo "<table border=1 class=db-table>";
        echo "<caption>Moji kreirani racuni</caption>";
        echo "<thead>"
        . "<th>id_racun</th>"
        . "<th>ime</th>"
        . "<th>prezime</th>"
        . "<th>iznos racuna(kn)</th>"
                . "<th>datum</th>"
        . "<th>vrijeme</th>"
        . "</thead>";
        while ($red = $podaci->fetch_array()) {
            echo "<tr>"
            . "<td>$red[0]</td>"
            . "<td>$red[1]</td>"
            . "<td>$red[2]</td>"
            . "<td>$red[3]</td>"
            . "<td>$red[4]</td>". "<td>$red[5]</td>"
            . "</td>";

            echo "</tr>";
        }
    } else {
        $greska.= "Greska kod dohvacanja <br> ";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $datum = date("Y-m-d H:i:s");
        $vrijeme = date("H:i:s");

        $upit3 = "insert into Racuni (idRacuni,Korisnik,datum,vrijeme) values "
                . " ('default','" . $korisnik . "','" . $datum . "','" . $vrijeme . "')";

        if ($baza->selectDB($upit3)) {
            echo "<table border = 1 class = db-table>";
            echo "<caption>Moji kreirani racuni</caption>";
            echo "<thead>"
            . "<th>id_racun</th>"
            . "<th>ime</th>"
            . "<th>prezime</th>"
            . "<th>naziv stavke lijeka</th>"
            . "<th>kolicina </th>"
            . "<th>iznos racuna(kn)</th>"
            . "<th>potvrdjeno</th>"
            . "<th>vrijeme</th>"
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
                . "<td>$red[7]</td>"
                . "</td>";

                echo "</tr>";
            }
            $greska = "uspjesno kreiran novi racun";
            //dnevnik
            $korisnik = $_SESSION['id_korisnik'];
            $datum = date("Y-m-d ");
            $vrijeme = date("H:i:s");
            $url = $_SERVER['PHP_SELF'];
            $upitdn = str_replace("'", "", $upit3);
            $radnja = "Kreiranje racuna";
            $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                    . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
            $baza->selectDB($dnevnik);

            header("refresh:3; url=dodavanjeStavki.php");
        } else {
            $greska.= "Greska kod dohvacanja <br> ";
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
$smarty->display('kreiranjeRacuna.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  