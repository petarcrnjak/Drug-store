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
    exit();
}
$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];


//galerija
$upit = "select idSlike,url,naziv,oznake from Slike where Korisnik='" . $korisnik . "'";
$podaci = $baza->selectDB($upit);
if ($podaci->num_rows == 0) {
    $greska.= "Nemate nijednu sliku<br>";
} else {
    $upit1 = "select idSlike,url,naziv,oznake from Slike where Korisnik='" . $korisnik . "'";
    $podaci1 = $baza->selectDB($upit1);
    if ($podaci1->num_rows != 0) {
        echo "<table border=1 class=db-table>";
        echo "<caption>Galerija</caption>";
        echo "<thead>"
        . "<th>ID slike</th>"
        . "<th>uploadana slika</th>"
        . "<th>naziv</th>"
        . "<th>oznake</th>"
        . "</thead>";
        while ($red = $podaci->fetch_array()) {
            echo "<tr>"
            . "<td>$red[0]</td>"
            . "<td><a href='" . $red[1] . "'><img src='" . $red[1] . "' width=165 height=145></a></td>"
            . "<td>$red[2]</td>"
            . "<td>$red[3]</td>"
            . "</td>";
            echo "</tr>";
        }
    } else {
        $greska.= "Greska <br> ";
    }
    $upit = "select idSlike from Slike where Korisnik='" . $korisnik . "'";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemoguce dohvatiti ID slike<br>";
    } else {
        $upit1 = "select idSlike from Slike where Korisnik='" . $korisnik . "'";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis[] = $red;
        }
    }
    $upitTag = "select idSlike,oznake from Slike where Korisnik='" . $korisnik . "' and oznake is not null order by 2 asc";
    $podaciTag = $baza->selectDB($upitTag);
    if ($podaciTag->num_rows == 0) {
        $greska.= "Nemoguce dohvatiti oznake slika<br>";
    } else {
        $upitTag1 = "select idSlike,oznake from Slike where Korisnik='" . $korisnik . "' and oznake is not null order by 2 asc";
        $podaciTag1 = $baza->selectDB($upitTag1);
        while ($red = mysqli_fetch_array($podaciTag1)) {
            $ispis1[] = $red;
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $oznake = $_POST['oznake'];
    $tag = $_POST['tag'];
    if (empty($_POST['id'])) {
        $greska.= "Nema id slike<br>";
    }
    if (empty($_POST['oznake'])) {
        $greska.= "Upisite oznaku za odabranu sliku<br>";
    }
    else {
        $upit1 = "update Slike set oznake='" . $oznake . "' where idSlike='" . $id . "' ";
        if ($baza->updateDB($upit1)) {
            $greska = "Uspjesno ste dodali oznaku za sliku ID '" . $id . "' ";
            header("refresh: 3,dodavanjeSlika.php");
        }
    }
    if (isset($_POST['tag']) && isset($_POST['chbx_tag'])) {
        $upit = "select idSlike,url,naziv,oznake from Slike where Korisnik='" . $korisnik . "' and oznake='".$tag."' order by 4";
        $podaci = $baza->selectDB($upit);
        if ($podaci->num_rows == 0) {
            $greska.= "Nemate nijednu sliku s odabranom oznakom<br>";
        } else {
            $upit1 = "select idSlike,url,naziv,oznake from Slike where Korisnik='" . $korisnik . "' and oznake='".$tag."' order by 4";
            $podaci1 = $baza->selectDB($upit1);
            if ($podaci1->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Galerija</caption>";
                echo "<thead>"
                . "<th>uploadana slika</th>"
                . "<th>ID slike</th>"
                . "<th>naziv</th>"
                 . "<th>oznake</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td><a href='" . $red[1] . "'><img src='" . $red[1] . "' width=165 height=145></a></td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "</td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Greska <br> ";
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
$smarty->assign('ispis1', $ispis1);

$smarty->display('_header.tpl');
$smarty->display('dodavanjeSlika.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  