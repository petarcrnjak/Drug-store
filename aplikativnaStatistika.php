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
        . " where k.id_korisnik='$korisnik' and (k.tipKorisnika='2' || k.tipKorisnika='3')";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo moderator i administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
} else {


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ((empty($_POST['chbx_kategorije'])) && empty($_POST['chbx_lijekovi'])) {
            $greska.= "Unesite checkbox željenog pregleda<br>";
        } else {
            if ((isset($_POST['chbx_kategorije'])) && isset($_POST['chbx_lijekovi'])) {
                $greska.= "Unesite samo jedan checkbox<br>";
            } else {
                if (isset($_POST['chbx_lijekovi']) and ! isset($_POST['chbx_vrijeme'])) {
                    $upit3 = "select l.Lijekovi,l.Korisnik,l.lajk,l.datum,k.naziv from Lajkovi as l join Lijekovi on Lijekovi.idLijekovi="
                            . "l.Lijekovi join Kategorije as k on k.idKategorije=Lijekovi.Kategorije order by 1";

                    //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
                    $podaci = $baza->selectDB($upit3);
                    if ($podaci->num_rows != 0) {
                       // $greska = "Pregled statistike lajkova po lijekovima";
                        echo "<table border=1 class=db-table>";
                        echo "<caption>Aplikativna statistika po lijekovima </caption>";
                        echo "<thead>"
                        . "<th>id_lijek</th>"
                        . "<th>id_korisnik</th>"
                        . "<th>like ili dislike (L/D)</th>"
                        . "<th>datum nastanka</th>"
                        . "<th>kategorija</th>"
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
$radnja = "Aplikativna statistika";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);
                    } else {
                        $greska.= "Birana kategorija je prazna <br> ";
                    }
                }
                if (isset($_POST['chbx_lijekovi']) and isset($_POST['chbx_vrijeme'])) {
                    $upit3 = "select l.Lijekovi,l.Korisnik,l.lajk,l.datum,k.naziv from Lajkovi as l join Lijekovi on Lijekovi.idLijekovi="
                            . "l.Lijekovi join Kategorije as k on k.idKategorije=Lijekovi.Kategorije order by 4";

                    //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
                    $podaci = $baza->selectDB($upit3);
                    if ($podaci->num_rows != 0) {
                      //  $greska = "Pregled statistike lajkova po lijekovima";
                        echo "<table border=1 class=db-table>";
                        echo "<caption>Aplikativna statistika po lijekovima </caption>";
                        echo "<thead>"
                        . "<th>id_lijek</th>"
                        . "<th>id_korisnik</th>"
                        . "<th>like ili dislike (L/D)</th>"
                        . "<th>datum nastanka</th>"
                        . "<th>kategorija</th>"
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
$radnja = "Aplikativna po vremenu";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);
                    } else {
                        $greska.= "Birana kategorija je prazna <br> ";
                    }
                }

                if (isset($_POST['chbx_kategorije']) and ! isset($_POST['chbx_vrijeme'])) {
                    $upit3 = "select l.Lijekovi,l.Korisnik,l.lajk,l.datum,k.naziv from Lajkovi as l join Lijekovi on Lijekovi.idLijekovi="
                            . "l.Lijekovi join Kategorije as k on k.idKategorije=Lijekovi.Kategorije order by 5 asc";

                    $podaci = $baza->selectDB($upit3);
                    if ($podaci->num_rows != 0) {
                        $greska = "Pregled statistike lajkova po kategorijama";
                        echo "<table border=1 class=db-table>";
                        echo "<caption>Aplikativna statistika po kategorijama </caption>";
                          echo "<thead>"
                        . "<th>id_lijek</th>"
                        . "<th>id_korisnik</th>"
                        . "<th>like ili dislike (L/D)</th>"
                        . "<th>datum nastanka</th>"
                        . "<th>kategorija</th>"
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
$radnja = "Aplikativna statistika";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);
                    } else {
                        $greska.= "Birana kategorija je prazna <br> ";
                    }
                }
                if (isset($_POST['chbx_kategorije']) and isset($_POST['chbx_vrijeme'])) {
                    $upit3 = "select l.Lijekovi,l.Korisnik,l.lajk,l.datum,k.naziv from Lajkovi as l join Lijekovi on Lijekovi.idLijekovi="
                            . "l.Lijekovi join Kategorije as k on k.idKategorije=Lijekovi.Kategorije order by 4";

                    $podaci = $baza->selectDB($upit3);
                    if ($podaci->num_rows != 0) {
                        $greska = "Pregled statistike lajkova po korisniku";
                        echo "<table border=1 class=db-table>";
                        echo "<caption>Aplikativna statistika po korisniku </caption>";
                        echo "<thead>"
                        . "<th>id_lijek</th>"
                        . "<th>id_korisnik</th>"
                        . "<th>like ili dislike (L/D)</th>"
                        . "<th>datum nastanka</th>"
                        . "<th>kategorija</th>"
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
$radnja = "Aplikativna po vremenu";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);
                    } else {
                        $greska.= "Birana kategorija je prazna <br> ";
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
$smarty->display('aplikativnaStatistika.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  
