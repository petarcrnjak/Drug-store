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
        . " where k.id_korisnik='$korisnik' and (k.tipKorisnika='2' or k.tipKorisnika='3')";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo moderator i administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
} else {

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
        $snizenost = $_POST['snizenost'];
        $datum = $_POST['datum'];

        if (empty($_POST['kategorija'])) {
            $greska.= "Unesite kategoriju<br>";
        } else {
            $upitAkcija = "select l.idLijekovi,l.naziv,l.cijena from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije "
                    . " where k.idKategorije='" . $kategorija . "' ";

            $podaci = $baza->selectDB($upitAkcija);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Lijekovi</caption>";
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
                   echo "</tr>";
                }
            } else {
                $greska.= "Birana kategorija je prazna <br> ";
            }
        }
        if (empty($_POST['kategorija']) || empty($_POST['lijekovi']) || empty($_POST['snizenost']) || empty($_POST['datum'])) {
            $greska.= "Unesite sve vrijednosti";
        }
        if (1 == 1) {
            $upit = "select * from Akcije as a join Lijekovi as l on a.Lijekovi=l.idLijekovi join Kategorije as k on l.Kategorije=k.idKategorije "
                    . " where k.idKategorije='" . $kategorija . "' and l.idLijekovi='" . $lijekovi . "'";
            $podaci = $baza->selectDB($upit);
            if ($podaci->num_rows != 0) {
                $greska = "Akcija za odabrani lijek vec postoji";
                header("refresh:3; url=definiranjeAkcija.php");
            } else {
                $upit1 = "select l.cijena from Lijekovi as l where l.idLijekovi='" . $lijekovi . "'";
                $podaci1 = $baza->selectDB($upit1);
                if ($podaci1->num_rows != 0) {
                    $stara = mysqli_fetch_row($podaci1); //stara cijena
                    $cijena = $stara[0] * (1 - ($snizenost / 100));


                    //Napravi novu akciju s novom cijenom
                    $upit3 = "insert into Akcije (idAkcije, Lijekovi, traje_do, nova_cijena, snizenost)"
                            . "values (default, '{$lijekovi}', '{$datum}', '{$cijena}', '{$snizenost}')";
                    if ($rezultat = $baza->updateDB($upit3)) {
                        $greska.="Uspješno definirana nova akcija.";
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
$radnja = " Kreiranje akcije";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);
                        header("refresh:2; url=definiranjeAkcija.php");
                    } else {
                        $greska = "Greska";
                    }
                } else {
                    
                }
            }
        }
    } else {
        $greska = "";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kategorija = $_POST['kategorija'];

        if ((isset($_POST['chbx_cijena'])) && isset($_POST['chbx_naziv'])) {
            $greska.= "Unesite samo jedan checkbox<br>";
        } else {
            if (isset($_POST['chbx_cijena'])) {
                $upitAkcija = "select l.idLijekovi,l.naziv,l.cijena from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije "
                        . " where k.idKategorije='" . $kategorija . "' order by l.cijena";

                $podaci = $baza->selectDB($upitAkcija);
                if ($podaci->num_rows != 0) {
                    echo "<table border=1 class=db-table2>";
                    echo "<caption>Lijekovi</caption>";
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
                       echo "</tr>";
                    }
                } else {
                    $greska.= "Birana kategorija je prazna <br> ";
                }
            }
            if (isset($_POST['chbx_naziv'])) {
                $upitAkcija = "select l.idLijekovi,l.naziv,l.cijena from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije "
                        . " where k.idKategorije='" . $kategorija . "' order by l.naziv";

                $podaci = $baza->selectDB($upitAkcija);
                if ($podaci->num_rows != 0) {
                    echo "<table border=1 class=db-table>";
                    echo "<caption>Lijekovi</caption>";
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
$smarty->display('definiranjeAkcija.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  