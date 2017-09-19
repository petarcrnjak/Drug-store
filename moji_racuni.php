<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
$greska = "";
$ispis = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
    header("Location:prijava.php");
    exit();
}
$korisnik = $_SESSION['id_korisnik'];
$tipKorisnika = $_SESSION['tipKorisnika'];
include_once './baza.class.php';
$baza = new Baza();

$upit = "select distinct r.idRacuni,r.Korisnik,re.idRezervacije,r.iznos,r.datum from Racuni as r left join Stavke as s on r.idRacuni=s.Racuni left join "
        . " Rezervacije as re on re.idRezervacije=s.Rezervacije left join Korisnik as k on k.id_korisnik=r.Korisnik where re.Korisnik='" . $korisnik . "' and r.iznos is not null order by r.datum ";
          $podaci = $baza->selectDB($upit);
if ($podaci->num_rows != 0) {
    echo "<table border=1 class=db-table>";
    echo "<caption>Moji racuni </caption>";
    echo "<thead>"
    . "<th>id racun</th>"
    . "<th>djelatnik</th>"
    . "<th>id rezervacije</th>"        
    . "<th>iznos (kn)</th>"
    . "<th>datum</th>"    
    . "</thead>";
    while ($red = $podaci->fetch_array()) {
        echo "<tr>"
        . "<td>$red[0]</td>"
        . "<td>$red[1]</td>"
        . "<td>$red[2]</td>"
        . "<td>$red[3]</td>". "<td>$red[4]</td>"
        . "</td>";
    }
} else {
    $greska.= "Nemate ni jedan izdan racun";
}

    

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ((isset($_POST['chbx_datum'])) && isset($_POST['chbx_iznos'])) {
        $greska.= "Unesite samo jedan checkbox<br>";
    } else {
        if (isset($_POST['chbx_datum'])) {
            $upit = "select distinct r.idRacuni,r.Korisnik,re.idRezervacije,r.iznos,r.datum from Racuni as r left join Stavke as s on r.idRacuni=s.Racuni left join "
        . " Rezervacije as re on re.idRezervacije=s.Rezervacije left join Korisnik as k on k.id_korisnik=r.Korisnik where r.Korisnik='" . $korisnik . "' order by r.datum ";
            $podaci = $baza->selectDB($upit);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Moji racuni </caption>";
                echo "<thead>"
                . "<th>id racun</th>"
                . "<th>djelatnik</th>"
                        . "<th>id rezervacije</th>"  
                . "<th>iznos (kn)</th>"
                . "<th>datum</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>". "<td>$red[4]</td>"
                    . "</td>";
                }
            } else {
                $greska.= "Nemate ni jedan izdan racun";
            }
        }
        if (isset($_POST['chbx_iznos'])) {
            $upit = "select distinct r.idRacuni,r.Korisnik,re.idRezervacije,r.iznos,r.datum from Racuni as r left join Stavke as s on r.idRacuni=s.Racuni left join "
        . " Rezervacije as re on re.idRezervacije=s.Rezervacije left join Korisnik as k on k.id_korisnik=r.Korisnik where r.Korisnik='" . $korisnik . "' order by r.iznos ";
             $podaci = $baza->selectDB($upit);
            if ($podaci->num_rows != 0) {
                echo "<table border=1 class=db-table>";
                echo "<caption>Moji racuni </caption>";
                echo "<thead>"
                . "<th>id racun</th>"
                . "<th>djelatnik</th>"
                        . "<th>id rezervacije</th>"  
                . "<th>iznos (kn)</th>"
                . "<th>datum</th>"
                . "</thead>";
                while ($red = $podaci->fetch_array()) {
                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>". "<td>$red[4]</td>"
                    . "</td>";
                }
            } else {
                $greska.= "Nemate ni jedan izdan racun";
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
$smarty->display('moji_racuni.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  