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


    $upitAkcija = "select k.idKategorije,k.naziv, kor.ime,kor.prezime,kor. korisnickoIme "
            . "from Kategorije as k join Korisnik kor on kor.id_korisnik=k.moderator";

    $podaci = $baza->selectDB($upitAkcija);

    if ($podaci->num_rows != 0) {
        echo "<table border=1 class=db-table>";
        echo "<caption>Kategorije lijekova</caption>";
        echo "<thead>"
        . "<th>id_kategorije</th>"
        . "<th>naziv</th>"
        . "<th>moderator ime: </th>"
        . "<th>prezime</th>"
        . "<th>korisnicko ime</th>"
        . "</thead>";
        while ($red = $podaci->fetch_array()) {
            echo "<tr>"
            . "<td>$red[0]</td>"
            . "<td>$red[1]</td>"
            . "<td>$red[2]</td>"
            . "<td>$red[3]</td>" . "<td>$red[4]</td>"
            . "</td>";

            echo "</tr>";
        }
    } else {
        $greska.= "Greska kod dohvacanja <br> ";
    }
    $upit = "select k.id_korisnik,k.korisnickoIme from Korisnik k join tipKorisnika as t on "
            . " k.tipKorisnika=t.idtipKorisnika";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate nijednog moderatora<br>";
    } else {
        $upit1 = "select k.id_korisnik,k.korisnickoIme from Korisnik k join tipKorisnika as t on "
                . " k.tipKorisnika=t.idtipKorisnika group by 2";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis[] = $red;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kategorija = $_POST['kategorija'];
        $moderator = $_POST['moderator'];

        $datum = date("Y-m-d H:i:s");
        $vrijeme = date("H:i:s");
        if (empty($_POST['kategorija']) || empty($_POST['moderator'])) {
            $greska.= "Unesite kategoriju i moderatora<br>";
        } else {
            $upit3 = "insert into Kategorije (idKategorije,moderator,naziv) values "
                    . " ('default','" . $moderator . "','" . $kategorija . "')";
            if ($baza->selectDB($upit3) != 0) {
                $greska = "uspjesno kreirana nova kategorija";
                //dnevnik
                $korisnik = $_SESSION['id_korisnik'];
                $datum = date("Y-m-d ");
                $vrijeme = date("H:i:s");
                $url = $_SERVER['PHP_SELF'];
                $upitdn = str_replace("'", "", $upit3);
                $radnja = "Kreiranje nove kategorije";
                $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                $baza->selectDB($dnevnik);

                header("refresh:3; url=kreiranjeKategorija.php");
            } else {
                $greska.= "Greska kod kreiranja <br> ";
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
$smarty->display('kreiranjeKategorija.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  