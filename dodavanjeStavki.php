<?php

session_start();
ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$baza = new Baza();
$greska = "";
$ispis = "";
$print = "";

if (!isset($_SESSION['korisnickoIme'])) {
    $greska.= "Morate biti prijavljeni";
// header("Location:prijava.php");
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

    $upit = "select * from Korisnik as k join tipKorisnika as t on  "
            . " k.tipKorisnika=t.idtipKorisnika where k.id_korisnik='" . $korisnik . "'and k.tipKorisnika='2' or '3'";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate ovlasti<br>";
        header("Location:prijava.php");
    }
    //izvrsenje racun
    $vrijeme = date("Y-m-d H:i:s");
    $time = strtotime($vrijeme);
    $time = $time - (15 * 60);
    $vrijeme2 = date("Y-m-d H:i:s", $time);
    $upit = "select idRacuni from Racuni where Racuni.datum>'".$vrijeme2."'";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate nijedan račun  <br>";
    } else {
        $upit1 = "select idRacuni from Racuni where Racuni.datum>'".$vrijeme2."'";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $ispis[] = $red;
        }
    }
    //izvrsenje rezervacija
     $vrijeme = date("Y-m-d H:i:s");
    $time = strtotime($vrijeme);
    $time = $time - (15 * 60);
    $vrijeme2 = date("Y-m-d H:i:s", $time);
    $upit = "select idRezervacije from Rezervacije as re where re.potvrdjena='1'";
    $podaci = $baza->selectDB($upit);
    if ($podaci->num_rows == 0) {
        $greska.= "Nemate nijedan račun  <br>";
    } else {
        $upit1 = "select idRezervacije from Rezervacije as re where re.potvrdjena='1' group by idRezervacije";
        $podaci1 = $baza->selectDB($upit1);
        while ($red = mysqli_fetch_array($podaci1)) {
            $print[] = $red;
        }
    }
    
    $vrijeme = date("Y-m-d H:i:s");
    $time = strtotime($vrijeme);
    $time = $time - (15 * 60);
    $vrijeme2 = date("Y-m-d H:i:s", $time);
    
 
        $upitAkcija = "select r.idRacuni,k.ime,k.prezime,r.iznos,r.datum"
                . " from Racuni as r,Korisnik as k where k.id_korisnik=r.Korisnik and r.datum>'".$vrijeme2."'";

        $podaci = $baza->selectDB($upitAkcija);
        
        if ($podaci->num_rows != 0) {
            echo "<table border=1 class=db-table1>";
            echo "<caption>Moji kreirani racuni</caption>";
            echo "<thead>"
            . "<th>id_racun</th>"
            . "<th>Moderator: ime</th>"
            . "<th>prezime</th>"
            . "<th>iznos racuna(kn)</th>"
            . "<th>vrijeme</th>"
                   
            . "</thead>";
            while ($red = $podaci->fetch_array()) {
                echo "<tr>"
                . "<td>$red[0]</td>"
                . "<td>$red[1]</td>"
                . "<td>$red[2]</td>"
                . "<td>$red[3]</td>"
                . "<td>$red[4]</td>"
                . "</td>";

                echo "</tr>";
            }
        } else {
            $greska.= "Greska kod dohvacanja, nemate račun stariji od 15min <br> ";
        }
        $upit = "select re.idRezervacije,k.ime,k. prezime,l.naziv,re.kolicina,re.cijena"
                . " from Rezervacije as re join Lijekovi as l on re.Lijekovi=l.idLijekovi join Korisnik as k on k.id_korisnik="
                . "re.Korisnik where re.potvrdjena='1' ";

        $reza = $baza->selectDB($upit);
        
        if ($reza->num_rows != 0) {
            echo "<table border=1 class=db-table2>";
            echo "<caption>Rezervacije</caption>";
            echo "<thead>"
            . "<th>id_rezervacije</th>"
              . "<th>Korisnik: ime</th>"
                      . "<th>prezime</th>"
            . "<th>lijekovi</th>"
            . "<th>kolicina</th>"
            . "<th>cijena</th>"
                   
            . "</thead>";
            while ($red1 = $reza->fetch_array()) {
                echo "<tr>"
                . "<td>$red1[0]</td>"
                . "<td>$red1[1]</td>"
                . "<td>$red1[2]</td>"
                . "<td>$red1[3]</td>" . "<td>$red1[4]</td>" . "<td>$red1[5]</td>"
                . "</td>";

                echo "</tr>";
            }
        } else {
            $greska.= "Greska kod dohvacanja rezervacija <br> ";
        }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $racuni=$_POST['racuni'];
        $rezervacije=$_POST['rezervacije'];
        $datum = date("Y-m-d H:i:s");
        $vrijeme = date("H:i:s");
//uzimanje podataka racuna
        if (empty($_POST['racuni']) and empty($_POST['rezervacije'])) {
        $greska="Unesite ID racuna i ID rezervacije<br>";
        }
        //dohvati naziv lijeka
        $upit="select l.naziv from Lijekovi as l join Rezervacije as re on re.Lijekovi=l.idLijekovi where re.idRezervacije='".$rezervacije."'";
        $rez= $baza->selectDB($upit);
        if ($rez->num_rows != 0) {
        $lijekovi= mysqli_fetch_row($rez);
        }
        else{
            $greska="greska kod dohvacanja naziva lijeka <br>";
        }
        
         //Dohvati novu cijenu iz akcije
        $upit1 = "select l.cijena from Lijekovi as l join Rezervacije as re on re.Lijekovi=l.idLijekovi join Akcije as a on a.Lijekovi=l.idLijekovi "
                . " where re.idRezervacije='".$rezervacije."'";
        $rezultat1 = $baza->selectDB($upit1);
        if ($rezultat1->num_rows != 0) {
            $cijena = mysqli_fetch_row($rezultat1);
        }
        else{
            $greska="greska kod dohvacanja nove cijene lijeka";
        }
        //upis stavkis
        $upit3 = "insert into Stavke values ('".$racuni."','".$rezervacije."','".$lijekovi[0]."',$cijena[0])";
        if($baza->updateDB($upit3)){
            $greska="Uspjesno ste dodali stavku na novi racun";
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
$radnja = "Kreiranje stavki ";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
$baza->selectDB($dnevnik);

            
            //update table racuni (iznos) i suma
            $sume="select sum(s.cijena) from Stavke as s join Racuni as r on s.Racuni=r.idRacuni where r.idRacuni='".$racuni."'";
            $pod=$baza->selectDB($sume);
            $svota= mysqli_fetch_row($pod);
            
            $update="update Racuni set iznos='".$svota[0]."' where idRacuni='".$racuni."'";
            $pod = $baza->updateDB($update); 
            header("refresh:3; url=dodavanjeStavki.php");
        } else {
            $greska.= "Greska kod dodavanja stavki <br> ";
        }
    }
}
require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('ispis', $ispis);
$smarty->assign('print', $print);

$smarty->display('_header.tpl');
$smarty->display('dodavanjeStavki.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  