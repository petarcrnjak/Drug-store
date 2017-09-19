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
   // header("refresh:2; url=prijava.php");
    exit();
}
$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];

$ulaz="select * from Korisnik as k join tipKorisnika as t on k.tipKorisnika=t.idtipKorisnika"
        . " where k.id_korisnik='$korisnik' and k.tipKorisnika='2' or '3'";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska="Samo moderator i administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
}
else{
$upit = "select idKategorije,naziv from Kategorije where moderator='" . $korisnik . "'";
$podaci = $baza->selectDB($upit);
if ($podaci->num_rows == 0) {
    $greska.= "Nemate dodijeljenu nijednu kategoriju<br>";
} else {
    $upit1 = "select idKategorije,naziv from Kategorije as k"
            . " where k.moderator='" . $korisnik . "'";
    $podaci1 = $baza->selectDB($upit1);
    while ($red = mysqli_fetch_array($podaci1)) {
        $ispis[] = $red;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategorija = $_POST['kategorija'];
    $lijekovi = $_POST['lijekovi'];
    $cijena = $_POST['cijena'];

    if (empty($_POST['kategorija'])) {
        $greska.= "Unesite kategoriju<br>";
    } else {
        $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije where k.idKategorije='" . $kategorija . "'";

     //   $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "'";
        $podaci = $baza->selectDB($upitAkcija);
        if ($podaci->num_rows != 0) {
            echo "<table border=1 class=db-table>";
            echo "<caption>Lijekovi </caption>";
            echo "<thead>"
            . "<th>id_lijek</th>"
            . "<th>naziv</th>"
            . "<th>cijena (kn)</th>"
            . "</thead>";
            while ($red = $podaci->fetch_array()) {
                echo "<tr>"
                . "<td>$red[0]</td>"
                . "<td>$red[2]</td>"
                . "<td>$red[3]</td>"
                . "</td>";
                echo "</tr>";
            }
        } else {
            $greska.= "Birana kategorija je prazna <br> ";
        }
    }
    if (empty($_POST['kategorija']) || empty($_POST['lijekovi'])|| empty($_POST['cijena'])) {
        $greska.= "Unesite sve vrijednosti";
    } else {
        //Napravi novi lijek s novom cijenom
        $upit3 = "insert into Lijekovi (idLijekovi,Kategorije,naziv,cijena)"
                . "values (default,'{$kategorija}', '{$lijekovi}', '{$cijena}')";
        if ($rezultat = $baza->updateDB($upit3)) {
            $greska.="Uspješno definiran novi lijek.";
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
$radnja = "Definiranje lijekova";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);
               
                header("refresh:3; url=definiranjeLijekova.php");
        }
        else {
    $greska = "Greska";
}
    }
    
} if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $kategorija = $_POST['kategorija'];
     
    if ((isset($_POST['chbx_cijena'])) && isset($_POST['chbx_naziv'])) {
        $greska.= "Unesite samo jedan checkbox<br>";
    } else {
        if (isset($_POST['chbx_cijena'])) {
             $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije where k.idKategorije='" . $kategorija . "' order by l.cijena";

       $podaci = $baza->selectDB($upitAkcija);
        if ($podaci->num_rows != 0) {
            echo "<table border=1 class=db-table>";
            echo "<caption>Lijekovi </caption>";
            echo "<thead>"
            . "<th>id_lijek</th>"
            . "<th>naziv</th>"
            . "<th>cijena (kn)</th>"
            . "</thead>";
            while ($red = $podaci->fetch_array()) {
                echo "<tr>"
                . "<td>$red[0]</td>"
                . "<td>$red[2]</td>"
                . "<td>$red[3]</td>"
                . "</td>";
                echo "</tr>";
            }
        } else {
            $greska.= "Birana kategorija je prazna <br> ";
        }}
            if (isset($_POST['chbx_naziv'])) {
              $upitAkcija = "select * from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije where k.idKategorije='" . $kategorija . "' order by l.naziv";

       $podaci = $baza->selectDB($upitAkcija);
        if ($podaci->num_rows != 0) {
            echo "<table border=1 class=db-table>";
            echo "<caption>Lijekovi </caption>";
            echo "<thead>"
            . "<th>id_lijek</th>"
            . "<th>naziv</th>"
            . "<th>cijena (kn)</th>"
            . "</thead>";
            while ($red = $podaci->fetch_array()) {
                echo "<tr>"
                . "<td>$red[0]</td>"
                . "<td>$red[2]</td>"
                . "<td>$red[3]</td>"
                . "</td>";
                echo "</tr>";
            }
        } else {
            $greska.= "Birana kategorija je prazna <br> ";
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
$smarty->display('definiranjeLijekova.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  