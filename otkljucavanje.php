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
        . " where k.id_korisnik='$korisnik' and k.tipKorisnika='3' ";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo administrator može prostupiti";
    header("refresh:2; url=prijava.php");
} else {
    $upitAkcija = "select k.id_korisnik, k.ime,k.prezime, k.korisnickoIme, k.zakljucan from Korisnik as k ";

    $podaci = $baza->selectDB($upitAkcija);
    if ($podaci->num_rows != 0) {
        echo "<table border=1 class=db-table>";
        echo "<caption>Korisnici</caption>";
        echo "<thead>"
        . "<th>id_korisnik</th>"
        . "<th>ime</th>"
        . "<th>prezime</th>"
        . "<th>korisnicko ime</th>"
        . "<th>zakljucan (mora biti 4)</th>"
        . "</thead>";
        while ($red = $podaci->fetch_array()) {
            echo "<tr>"
            . "<td>$red[0]</td>"
            . "<td>$red[1]</td>"
            . "<td>$red[2]</td>"
            . "<td>$red[3]</td>"
            . "<td>$red[4]</td>"
            . "</td>";
           echo "</tr>";
        }
    } else {
        $greska.= "Birana kategorija je prazna <br> ";
    }
    $upit = "select id_korisnik from Korisnik";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate rezervacija<br>";
    } else {
        $upit1 = "select id_korisnik from Korisnik group by 1";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis[] = $red;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $korisnik = $_POST['korisnik'];

        if (empty($_POST['korisnik'])) {
            $greska.= "Unesite ID korisnika<br>";
        } else {
            if ((empty($_POST['chbx_otklj'])) && empty($_POST['chbx_zaklj'])) {
                $greska.= "Unesite checkbox<br>";
            } else {
                if ((isset($_POST['chbx_otklj'])) && isset($_POST['chbx_zaklj'])) {
                    $greska.= "Unesite samo jedan checkbox<br>";
                } else {
                    if (isset($_POST['chbx_otklj'])) {
                        $upit3 = "update Korisnik as k set zakljucan='0' where k.id_korisnik='" . $korisnik . "'";
                        $podaci = $baza->updateDB($upit3);
                        if ($podaci = $baza->updateDB($upit3)) {
                            $greska.= "Otkljucali ste racun korisnika ID $korisnik";
                            //dnevnik
                            $korisnik = $_SESSION['id_korisnik'];
                            $datum = date("Y-m-d ");
                            $vrijeme = date("H:i:s");
                            $url = $_SERVER['PHP_SELF'];
                            $upitdn = str_replace("'", "", $upit3);
                            $radnja = "Otkljucavanje racuna";
                            $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                                    . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                            $baza->selectDB($dnevnik);
                            header("refresh:3; url=otkljucavanje.php");
                        } else {
                            $greska = "Greska kod otkljucavanja";
                        }
                    } else {
                        if (isset($_POST['chbx_zaklj'])) {
                            $upit3 = "update Korisnik as k set zakljucan='4' where k.id_korisnik='" . $korisnik . "'";
                            $podaci = $baza->updateDB($upit3);
                            if ($podaci = $baza->updateDB($upit3)) {
                                $greska.= "Zakljucali ste racun korisnika";
                                //dnevnik
                                $korisnik = $_SESSION['id_korisnik'];
                                $datum = date("Y-m-d ");
                                $vrijeme = date("H:i:s");
                                $url = $_SERVER['PHP_SELF'];
                                $upitdn = str_replace("'", "", $upit3);
                                $radnja = "Zakljucavanje racuna";
                                $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                                        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                                $baza->selectDB($dnevnik);
                                header("refresh:3; url=otkljucavanje.php");
                            } else {
                                $greska = "Greska kod zakljucavanja";
                            }
                        }
                    }
                }
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
$smarty->display('otkljucavanje.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  
