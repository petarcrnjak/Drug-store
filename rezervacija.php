<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
$frmKorisIme = "";
$greska = '';
$ispis = '';
include_once './baza.class.php';
$baza = new Baza();
if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}
$korisnickoIme = $_SESSION['korisnickoIme'];
$upit = "select idKategorije,naziv from Kategorije";
$podaci = $baza->selectDB($upit);
while ($red = mysqli_fetch_array($podaci)) {
    $ispis[] = $red;
}


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategorija = $_POST['kategorija'];
    $lijekovi = $_POST['lijekovi'];
    $kolicina = $_POST['kolicina'];
    $korisnik = $_SESSION['id_korisnik'];

    if (empty($_POST['kategorija'])) {
        $greska.= "Unesite kategoriju";
    }
    $upitAkcija = "select * from Lijekovi as l left join Kategorije as k on l.Kategorije=k.idKategorije where k.idKategorije='" . $kategorija . "'";
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
            echo "<td><a href='like.php?lijekovi=$red[0]'>sviđa mi se</a></td>";
            echo "<td><a href='dislike.php?lijekovi=$red[0]'>ne sviđa mi se</a></td>";
            echo "</tr>";
        }
    } else {
        $greska.= "Birana kategorija je prazna <br> ";
    }

    if (empty($_POST['kategorija']) || empty($_POST['lijekovi']) || empty($_POST['kolicina'])) {
        $greska.= "Unesite sve vrijednosti";
    } else {
        //Dohvati novu cijenu iz akcije
        $upit1 = "select l.cijena from Lijekovi as l where  l.idLijekovi='{$lijekovi}'";
        $rezultat1 = $baza->selectDB($upit1);
        if ($rezultat1->num_rows != 0) {
            $cijena = mysqli_fetch_row($rezultat1);

            //Napravi rezervaciju s novom cijenom
            $upit3 = "insert into Rezervacije (Korisnik,Lijekovi,kolicina,cijena)"
                    . "values ('{$korisnik}','{$lijekovi}', '{$kolicina}', '{$cijena[0]}')";
            if ($rezultat = $baza->updateDB($upit3)) {
                $greska.="Uspješno kreirana rezervacija.";
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
$radnja = "Rezervacija";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);
                header("refresh:3; url=rezervacija.php");
            } else {
                $greska = "Greska kod odabira lijeka";
            }
        } else {
            $greska = "Greska kod cijene";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $kategorija = $_POST['kategorija'];
     
    if ((isset($_POST['chbx_cijena'])) && isset($_POST['chbx_naziv'])) {
        $greska.= "Unesite samo jedan checkbox<br>";
    } else {
        if (isset($_POST['chbx_cijena'])) {
            $upit = "select l.idLijekovi,l.naziv,l.cijena from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "' order by 3";
  
            $podaci = $baza->selectDB($upit);
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
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "</td>";
                    echo "<td><a href='like.php?lijekovi=$red[0]'>sviđa mi se</a></td>";
                    echo "<td><a href='dislike.php?lijekovi=$red[0]'>ne sviđa mi se</a></td>";
                    echo "</tr>";
                }
            } else {
                $greska.= "Greska <br> ";
        }}
            if (isset($_POST['chbx_naziv'])) {
              $upit = "select l.idLijekovi,l.naziv,l.cijena from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "' order by 2";
  
            $podaci = $baza->selectDB($upit);
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
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "</td>";
                    echo "<td><a href='like.php?lijekovi=$red[0]'>sviđa mi se</a></td>";
                    echo "<td><a href='dislike.php?lijekovi=$red[0]'>ne sviđa mi se</a></td>";
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
$smarty->assign('cookieKorisnicko', $frmKorisIme);

$smarty->display('_header.tpl');
$smarty->display('rezervacija.tpl');
$smarty->display('_footer.tpl');
ob_end_flush();
?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

