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
        $korisnici = $_POST['korisnici'];

        if ((isset($_POST['chbx_svi']))) {
            $upitAkcija = "select d.idDnevnici,d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.poruka,d.upit,d.radnja "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.datum='" . $kategorija . "'";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Lijekovi </caption>";
                echo "<thead>"
                . "<th>id dnevnik</th>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "<th>upit</th>" . "<th>radnja</th>"
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
                    . "<td>$red[7]</td>" . "<td>$red[8]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Birana kategorija je prazna <br> ";
            }
        }
        if((empty($_POST['chbx_svi']))){
            if((empty($_POST['chbx_radnja']))) {
            if((empty($_POST['chbx_datum']))) {
            $upit3 = "select d.idDnevnici,d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.poruka,d.upit "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.Korisnik='" . $korisnici . "' and d.datum='" . $kategorija . "'";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upit3);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Lijekovi </caption>";
                echo "<thead>"
                . "<th>id dnevnik</th>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "<th>upit</th>"
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
                    $greska = "Uspjesano izvrseno";

                    //dnevnik
                 //   $korisnik = $_SESSION['id_korisnik'];
                  //  $datum = date("Y-m-d ");
                    //$vrijeme = date("H:i:s");
                    //$url = $_SERVER['PHP_SELF'];
                    //$upitdn = str_replace("'", "", $upit3);
                    //$radnja = "Pregled dnevnika po korisniku za određeni datum";
                    //$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                      //      . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                    //$baza->selectDB($dnevnik);
                }
            } else {
                $greska.= "Birana kategorija je prazna <br> ";
            }
        }}}
       if((isset($_POST['chbx_datum']))) {
            $upit3 = "select d.idDnevnici,d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.poruka,d.upit "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.Korisnik='" . $korisnici . "'";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upit3);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Lijekovi </caption>";
                echo "<thead>"
                . "<th>id dnevnik</th>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "<th>upit</th>"
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
                    $greska = "Uspjesano izvrseno";

                    //dnevnik
                 //   $korisnik = $_SESSION['id_korisnik'];
                  //  $datum = date("Y-m-d ");
                    //$vrijeme = date("H:i:s");
                    //$url = $_SERVER['PHP_SELF'];
                    //$upitdn = str_replace("'", "", $upit3);
                    //$radnja = "Pregled dnevnika po korisniku za sve datume";
                    //$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                      //      . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                    //$baza->selectDB($dnevnik);
                }
            } else {
                $greska.= "Birana kategorija je prazna <br> ";
            }
    }
    if((isset($_POST['chbx_radnja']))) {
            if((empty($_POST['chbx_datum']))) {
            $upit3 = "select d.idDnevnici,d.korisnik,k.korisnickoIme,d.datum,d.vrijeme,d.url,d.poruka,d.upit "
                    . " from Dnevnici as d join Korisnik as k on d.Korisnik=k.id_korisnik where d.Korisnik='" . $korisnici . "' and d.datum='" . $kategorija . "' order by d.poruka asc";
            //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
            $podaci = $baza->selectDB($upit3);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Lijekovi </caption>";
                echo "<thead>"
                . "<th>id dnevnik</th>"
                . "<th>id korisnik</th>"
                . "<th>korisnicko ime</th>"
                . "<th>datum</th>"
                . "<th>vrijeme</th>"
                . "<th>url</th>"
                . "<th>poruka</th>"
                . "<th>upit</th>"
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
                    $greska = "Uspjesano izvrseno";

                    //dnevnik
                 //   $korisnik = $_SESSION['id_korisnik'];
                  //  $datum = date("Y-m-d ");
                    //$vrijeme = date("H:i:s");
                    //$url = $_SERVER['PHP_SELF'];
                    //$upitdn = str_replace("'", "", $upit3);
                    //$radnja = "Pregled dnevnika po korisniku za određeni datum";
                    //$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                      //      . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                    //$baza->selectDB($dnevnik);
                }
            } else {
                $greska.= "Birana kategorija je prazna <br> ";
            }
        }}
    }

require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('ispis', $ispis);
$smarty->assign('ispis1', $ispis1);
$smarty->display('_header.tpl');
$smarty->display('dnevnik.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  
