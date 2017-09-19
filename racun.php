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
    // header("Location:prijava.php");
    header("refresh:2; url=prijava.php");
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
if (1 == 1) {
                        $upit = "insert into Racuni (idRacuni,Korisnik,datum)"
                                . " values (default,'" . $korisnik . "','" . $vrijeme2 . "')";

                        if ($baza->updateDB($upit)) {

                            //priprema za stavke    
                            $upitStavke = "select r.idRacuni from Racuni as r where r.datum='" . $vrijeme2 . "' ";
                            $prenos = $baza->selectDB($upitStavke);

                            $idRacuni = mysqli_fetch_row($prenos);

                            if ($prenos->num_rows == 0) {
                                $greska.= "Greska kod idracuna";
                            } else {

                                $provjeriDatum = "select a.traje_do from Akcije as a join Lijekovi as l on a.Lijekovi=l.idLijekovi "
                                        . " join Rezervacije as r on r.Lijekovi=l.idLijekovi where l.idLijekovi='" . $lijekovi[0] . "' ";
                                $rezultat = $baza->selectDB($provjeriDatum);

                                if ($rezultat->num_rows == 0) {
                                    $greska = "nema akcije za lijek";
                                } else {
                                    $trajeDo = mysqli_fetch_row($rezultat);
                                    if (strtotime($trajeDo[0]) > strtotime('$vrijeme2')) {

                                        $provjeriCijenu = "select a.nova_cijena from Akcije as a join Lijekovi as l on a.Lijekovi=l.idLijekovi "
                                                . " join Rezervacije as r on r.Lijekovi=l.idLijekovi where l.idLijekovi='" . $lijekovi[0] . "' ";
                                        $podaci = $baza->selectDB($provjeriCijenu);

                                        if ($podaci->num_rows != 0) {
                                            $akcijska[0] = mysqli_fetch_row($podaci);
                                            $upitStavke = "select r.idRacuni from Racuni as r where r.datum='" . $vrijeme2 . "' ";
                                            $prenos = $baza->selectDB($upitStavke);

                                            $idRacuni = mysqli_fetch_row($prenos);
                                            $upit1 = "insert into Stavke (Recuni,Rezervacije,naziv,cijena)"
                                                    . " values ('" . $idRacuni[0] . "','" . $idRezervacije . "','" . $lijek . "','" . $akcijska[0] . "')";
                                            $podaci1 = $baza->updateDB($upit1);

                                            if ($podaci1->num_rows == 0) {
                                                $greska.= "Greska kod stvaranja stavke";
                                            }

                                            //header("Location:kreiranjeRacuna.php");
                                            header("refresh:2; url=kreiranjeRacuna.php");
                                        }
                                    } else {
                                        $upit1 = "insert into Stavke (Recuni,Rezervacije,naziv,cijena)"
                                                . " values ('" . $idRacuni[0] . "','" . $idRezervacije . "','" . $lijek . "','" . $cijena[5] . "')";
                                        $podaci1 = $baza->updateDB($upit1);
                                    }
                                    if ($podaci1->num_rows == 0) {
                                        $greska.= "Greska kod stvaranja stavke";
                                    }
                                }
                            }
                        }
                    } else {
                        $greska.= "Greska pri radu s bazom podataka <br>";
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