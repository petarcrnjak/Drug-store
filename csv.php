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
    $upitAkcija = "select * from Kategorije";

    $podaci = $baza->selectDB($upitAkcija);
    if ($podaci->num_rows != 0) {
        echo "<table border=1 class=db-table>";
        echo "<caption>Kategorije lijekova</caption>";
        echo "<thead>"
        . "<th>id_kategorije</th>"
        . "<th>moderator</th>"
        . "<th>naziv</th>"
        . "</thead>";
        while ($red = $podaci->fetch_array()) {
            echo "<tr>"
            . "<td>$red[0]</td>"
            . "<td>$red[1]</td>"
            . "<td>$red[2]</td>"
            . "</td>";
            echo "</tr>";
        }
    } else {
        $greska.= "Birana kategorija je prazna <br> ";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $file_handle = fopen("kategorije.csv", "r");

        while (!feof($file_handle)) {

            $red = fgetcsv($file_handle, 1024);
            $upit1 = "select idKategorije from Kategorije where idKategorije='" . $red[0] . "'";
            $podaci = $baza->selectDB($upit1);
            $id = mysqli_fetch_row($podaci);
            
           if ($podaci->num_rows != 0) {
                $upit1 = "update Kategorije set moderator='" . $red[1] . "' ,naziv='" . $red[2] . "' "
                        . " where idKategorije='" . $red[0] . "'";
                if($baza->updateDB($upit1)){
                $greska = "updateani podaci";
                }
            } else {
                $upit = "insert into Kategorije (idKategorije,moderator,naziv) values ('" . $red[0] . "','" . $red[1] . "','" . $red[2] . "')";
                if ($baza->updateDB($upit)) {
                    $greska = "uspjesno dodani podaci";
                    header("Location: csv.php");
                } else {
                    $greska = "greska";
                  header("Location:csv1.php");
                }
            }
        }

        fclose($file_handle);
    }
}

require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('ispis', $ispis);
$smarty->assign('ispis1', $ispis1);
$smarty->display('_header.tpl');
$smarty->display('csv.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  
