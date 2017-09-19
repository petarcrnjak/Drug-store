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
        . " where k.id_korisnik='$korisnik' and k.tipKorisnika='2' or '3' ";
$tip = $baza->selectDB($ulaz);
if ($tip->num_rows == 0) {
    $greska = "Samo moderator i administrator mogu prostupiti";
    header("refresh:2; url=prijava.php");
} else {
    
    $upit = "select idRezervacije from Rezervacije";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate rezervacija<br>";
    } else {
        $upit1 = "select idRezervacije from Rezervacije group by 1";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis[] = $red;
        }
    }
     $upitAkcija = "select r.idRezervacije,k.ime,k.prezime,l.naziv,r.kolicina,r.cijena,r.potvrdjena from Korisnik as k join Rezervacije as r on k.id_korisnik=r.Korisnik "
                        . "join Lijekovi as l on l.idLijekovi=r.Lijekovi order by r.cijena";

                $podaci = $baza->selectDB($upitAkcija);

                if ($podaci->num_rows != 0) {
                    echo "<table border=1 class=db-table>";
                    echo "<caption>Rezervacije</caption>";
                    echo "<thead>"
                    . "<th>id_rezervacije</th>"
                    . "<th>ime</th>"
                    . "<th>prezime</th>"
                    . "<th>naziv lijeka</th>"
                    . "<th>kolicina </th>"
                    . "<th>cijena (kn)</th>"
                    . "<th>potvrdjeno</th>"
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
                        . "</td>";

                        echo "</tr>";
                    }
                } else {
                    $greska.= "Greska kod dohvacanja <br> ";
                }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idRezervacije = $_POST['idRezervacije'];


        if (empty($_POST['idRezervacije'])) {
            $greska.= "Unesite ID rezervacije<br>";
        }
        if (empty($_POST['chbx_potvrdi']) && empty($_POST['chbx_odbi'])) {
            $greska.= "Oznacite potvrdi ili odbi <br>";
        } else {
            $upit1 = "select k.email,l.idLijekovi,r.Korisnik,l.naziv,r.kolicina,l.cijena from Korisnik as k join Rezervacije as r on k.id_korisnik=r.Korisnik "
                    . "join Lijekovi as l on l.idLijekovi=r.Lijekovi where r.idRezervacije='" . $idRezervacije . "' ";

            $podaci1 = $baza->selectDB($upit1);

            $email = mysqli_fetch_row($podaci1);
            $kupac = mysqli_fetch_row($podaci1);
            $ime = mysqli_fetch_row($podaci1);
            $lijek = mysqli_fetch_row($podaci1);
            $idLijekovi = mysqli_fetch_row($podaci1);
            $kolicina = mysqli_fetch_row($podaci1);
            $cijena = mysqli_fetch_row($podaci1);

            $upit2 = "select l.idLijekovi from Korisnik as k join Rezervacije as r on k.id_korisnik=r.Korisnik "
                    . "join Lijekovi as l on l.idLijekovi=r.Lijekovi where r.idRezervacije='" . $idRezervacije . "' ";
            $podaci2 = $baza->selectDB($upit2);
            $lijekovi = mysqli_fetch_row($podaci2);


            if ($podaci1->num_rows == 0) {
                $greska = "greska";
            }
            if (isset($_POST['chbx_potvrdi'])) {
                $potvrda = '1';

                $upit3 = "update Rezervacije set potvrdjena='" . $potvrda . "' where "
                        . " Rezervacije.idRezervacije='" . $idRezervacije . "' ";

                if ($baza->updateDB($upit3)) {
                    $greska = "Uspjesno potvrdjena rezervacija<br>";
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
$radnja = "Potvrda rezervacije";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);

                    $primatelj = $email[0];
                    $naslov = "Obavjest o rezervaciji id_rezervacije: " . $idRezervacije . "";
                    $poruka = "Postovani,\n Vasa rezervacija id_Rezervacije: " . $idRezervacije . " je potvrdjena!";
                    mail($primatelj, $naslov, $poruka);
                    header("refresh:10; url=potvrdaRezervacije.php");
                    //stvaranje racuna
                    $oblik = "Y-m-d H:i:s";
                    $vrijeme1 = new DateTime(date($oblik));
                    $vrijeme2 = $vrijeme1->format($oblik);

                }
                header("refresh:2; url=potvrdaRezervacije.php");
            } else {
                if (isset($_POST['chbx_odbi'])) {
                    $potvrda1 = '0';
                    $upit3 = "update Rezervacije set potvrdjena='" . $potvrda1 . "' where "
                            . " Rezervacije.idRezervacije='" . $idRezervacije . "' ";

                    $baza->updateDB($upit3);
                    $greska = "Uspjesno odbijena rezervacija";
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
$radnja = "Odbijena rezervacija ";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);

                    if ($baza->updateDB($upit)) {
                        $primatelj = $email[0];
                        $naslov = "Obavjest o rezervaciji id_rezervacije: " . $idRezervacije . "";
                        $poruka = "Postovani,\n Vasa rezervacija id_Rezervacije: " . $idRezervacije . " je odbijena!";
                        mail($primatelj, $naslov, $poruka);
                        header("refresh:3; url=potvrdaRezervacije.php");
                    }
                } else {
                    $greska.= "Greska pri radu s bazom podataka <br>";
                }
            }
        }
    
        if ((isset($_POST['chbx_cijena'])) && isset($_POST['chbx_naziv']) && isset($_POST['chbx_potvrda'])) {
            $greska.= "Unesite samo jedan checkbox<br>";
        } else {
            if (isset($_POST['chbx_cijena'])) {
                $upitAkcija = "select r.idRezervacije,k.ime,k.prezime,l.naziv,r.kolicina,r.cijena,r.potvrdjena from Korisnik as k join Rezervacije as r on k.id_korisnik=r.Korisnik "
                        . "join Lijekovi as l on l.idLijekovi=r.Lijekovi order by r.cijena";

                $podaci = $baza->selectDB($upitAkcija);

                if ($podaci->num_rows != 0) {
                    echo "<table border=1 class=db-table>";
                    echo "<caption>Rezervacije</caption>";
                    echo "<thead>"
                    . "<th>id_rezervacije</th>"
                    . "<th>ime</th>"
                    . "<th>prezime</th>"
                    . "<th>naziv lijeka</th>"
                    . "<th>kolicina </th>"
                    . "<th>cijena (kn)</th>"
                    . "<th>potvrdjeno</th>"
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
                        . "</td>";

                        echo "</tr>";
                    }
                } else {
                    $greska.= "Greska kod dohvacanja <br> ";
                }
            }
            if (isset($_POST['chbx_naziv'])) {
                $upitAkcija = "select r.idRezervacije,k.ime,k.prezime,l.naziv,r.kolicina,r.cijena,r.potvrdjena from Korisnik as k join Rezervacije as r on k.id_korisnik=r.Korisnik "
                        . "join Lijekovi as l on l.idLijekovi=r.Lijekovi order by l.naziv";

                $podaci = $baza->selectDB($upitAkcija);

                if ($podaci->num_rows != 0) {
                    echo "<table border=1 class=db-table>";
                    echo "<caption>Rezervacije</caption>";
                    echo "<thead>"
                    . "<th>id_rezervacije</th>"
                    . "<th>ime</th>"
                    . "<th>prezime</th>"
                    . "<th>naziv lijeka</th>"
                    . "<th>kolicina </th>"
                    . "<th>cijena (kn)</th>"
                    . "<th>potvrdjeno</th>"
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
                        . "</td>";

                        echo "</tr>";
                    }
                } else {
                    $greska.= "Greska kod dohvacanja <br> ";
                }
            }
            if (isset($_POST['chbx_potvrda'])) {
                $upitAkcija = "select r.idRezervacije,k.ime,k.prezime,l.naziv,r.kolicina,r.cijena,r.potvrdjena from Korisnik as k join Rezervacije as r on k.id_korisnik=r.Korisnik "
                        . "join Lijekovi as l on l.idLijekovi=r.Lijekovi order by r.potvrdjena";

                $podaci = $baza->selectDB($upitAkcija);

                if ($podaci->num_rows != 0) {
                    echo "<table border=1 class=db-table>";
                    echo "<caption>Rezervacije</caption>";
                    echo "<thead>"
                    . "<th>id_rezervacije</th>"
                    . "<th>ime</th>"
                    . "<th>prezime</th>"
                    . "<th>naziv lijeka</th>"
                    . "<th>kolicina </th>"
                    . "<th>cijena (kn)</th>"
                    . "<th>potvrdjeno</th>"
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
                        . "</td>";

                        echo "</tr>";
                    }
                } else {
                    $greska.= "Greska kod dohvacanja <br> ";
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
    $smarty->display('potvrdaRezervacije.tpl');
    $smarty->display('_footer.tpl');

    ob_end_flush();
?>  