<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$baza = new Baza();
$greska = "";
$ispis = "";
$ispis1 = "";

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

        $upit1 = "select DISTINCT datum from Dnevnici as d";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis[] = $red;
        }
         $upit1 = "select * from Korisnik group by 1 order by korisnickoIme";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis1[] = $red;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kategorija = $_POST['kategorija'];
        

        if ((isset($_POST['chbx_svi']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Prijava' ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                 if ((isset($_POST['chbx_svi']))|| (isset($_POST['chbx_sort']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Prijava' order by 1 ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Biran datum je prazan <br> ";
            }
        }}}
        if ((isset($_POST['chbx_odjava']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Odjava' ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                if ((isset($_POST['chbx_odjava'])) || (isset($_POST['chbx_sort']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Odjava' order by 1";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Biran datum je prazan <br> ";
            }
        }}}
         if ((isset($_POST['chbx_treca']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Odjava' ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
         if ((isset($_POST['chbx_treca'])) || (isset($_POST['chbx_sort']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Odjava' order by 1";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Biran datum je prazan <br> ";
            }
         }}}
         if ((isset($_POST['chbx_aplikativna']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Aplikativna statistika' ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
        if ((isset($_POST['chbx_aplikativna'])|| (isset($_POST['chbx_sort'])))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Aplikativna statistika' order by 1";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Biran datum je prazan <br> ";
            }
         }}}
         if ((isset($_POST['chbx_akcije']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Definiranje lijekova' ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
        if ((isset($_POST['chbx_akcije']))|| (isset($_POST['chbx_sort']))) {
            $upitAkcija = "select d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "' and d.radnja='Definiranje lijekova' order by 1 ";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Statistika </caption>";
                echo "<thead>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "<td>$red[4]</td>"
                    . "<td>$red[5]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Biran datum je prazan <br> ";
            }
        }
    }
    }}
require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('ispis', $ispis);
$smarty->assign('ispis1', $ispis1);
$smarty->display('_header.tpl');
$smarty->display('posjecenost.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  

