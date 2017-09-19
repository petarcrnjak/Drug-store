<?php
session_start();
ob_start();


header('Content-Type: text/html; charset=utf-8');
$frmKorisIme = "";
$greska = "";
if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
if (isset($_SESSION['korisnickoIme'])) { //ako je već ulogiran a došo je get metodom hiti ga natrag na index
    $greska= "Vec ste prijavljeni";
    header("Location:index.php");
}

if (isset($_COOKIE['korisnickoIme'])) {
    $frmKorisIme = $_COOKIE['korisnickoIme'];
}
include_once './baza.class.php';
//include_once './pomak.php';
$greska = "";
$baza = new Baza();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnicko_ime = $_POST['korime'];
    $lozinka = $_POST['lozinka'];

    if (empty($_POST['korime']) || empty($_POST['lozinka'])) {
        $greska.= "Unesite sve vrijenosti<br>";
    } else {
        $upit3 = "select * from Korisnik where korisnickoIme='" . $korisnicko_ime . "' and  lozinka='" . $lozinka . "'";
        $rezultat = $baza->selectDB($upit3);

        if ($rezultat->num_rows == 1) {
            $podaci = mysqli_fetch_array($rezultat);
            //prvo provjeri jel blokiran ako je ispiši poruku
            $status = $podaci['statusKorisnika'];
            $zabrana = $podaci['zakljucan'];
            if ($status == 2) {
                $greska = 'Nažalost, Vaš račun je blokiran od više strane. Kontaktirajte administratora';
            }
            if ($status == 0) {
                $greska = 'Račun još nije aktiviran. Aktivirajte ga putem mail-a (provjerite vaš poštanski sandučić).';
            } else if ($zabrana == 4) {
                $greska = "Vaš račun je zaključan, javite se administratoru (4 netočne prijave)";
                $upitK = "select k.id_korisnik from Korisnik as k where k.korisnickoIme='" . $korisnicko_ime . "'";
                $rezultatK = $baza->selectDB($upitK);
                if ($rezultatK->num_rows != 0) {
                    $korisnikK = mysqli_fetch_row($rezultatK);
                    //dnevnik

                    $datum = date("Y-m-d ");
                    $vrijeme = date("H:i:s");
                    $url = $_SERVER['PHP_SELF'];
                    //$upitdn = "";
                    $radnja = "Zakljucan korisnicki racuna";
                    $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
                            . " ('{$korisnikK[0]}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
                    $baza->selectDB($dnevnik);
                }
            } else {
                //kreiranje sesije
                $_SESSION['tipKorisnika'] = $podaci['tipKorisnika'];
                $_SESSION['id_korisnik'] = $podaci['id_korisnik'];
                $_SESSION['email'] = $podaci['email'];
                $_SESSION['vrijemeZadnje'] = $podaci['zadnjaPrijava'];
                $_SESSION['Ime'] = $podaci['ime'];
                $_SESSION['Prezime'] = $podaci['prezime'];
                $_SESSION['korisnickoIme'] = $podaci['korisnickoIme'];
                $_SESSION['Status'] = $podaci['statusKorisnika'];
                $_SESSION['zadnjaPrijava'] = $podaci['zadnjaPrijava'];

                //kreiranje cookiea ako ne postoji
                if (isset($_POST['chbx_pamti']) && !isset($_COOKIE['korisnickoIme'])) {
                    $imeKolacica = "korisnickoIme";
                    $vrijednostKolacica = $_SESSION['korisnickoIme'];
                    setcookie($imeKolacica, $vrijednostKolacica, time() + (3600)); //nakon 1h istice
                }
                //brisanje cookiea ako nije checkirano
                if (!isset($_POST['chbx_pamti']) && isset($_COOKIE['korisnickoIme'])) {
                    setcookie("korisnickoIme", "", time() - 3600);
                }
                //u polje stavi
                $upit = "update Korisnik set  zakljucan='0' where korisnickoIme='" . $korisnicko_ime . "' ";
                $baza->updateDB($upit);


                $upit = "update Korisnik set zadnjaPrijava=NOW() where korisnickoIme='" . $korisnicko_ime . "' ";
                $baza->updateDB($upit);

                $greska = "Uspjesno kreirana prijava";
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
$radnja = "Prijava";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnik}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);

                header("refresh:3; url=index.php");
            }
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $korisnicko_ime = $_POST['korime'];
                $upit3 = "select zakljucan from Korisnik where korisnickoIme='" . $korisnicko_ime . "'";
                $rezultat = $baza->selectDB($upit3);
                if ($rezultat->num_rows != 0) {
                    $podaci = mysqli_fetch_array($rezultat);
                    $zabrana = $podaci['zakljucan'];
                    if ($zabrana == 0) {
                        $upit = "update Korisnik set zakljucan='1' where korisnickoIme='" . $korisnicko_ime . "' ";
                        $baza->updateDB($upit);
                    }
                    if ($zabrana == 1) {
                        $upit = "update Korisnik set zakljucan='2' where korisnickoIme='" . $korisnicko_ime . "' ";
                        $baza->updateDB($upit);
                    }
                    if ($zabrana == 2) {
                        $upit = "update Korisnik set zakljucan='3' where korisnickoIme='" . $korisnicko_ime . "' ";
                        $baza->updateDB($upit);
                    }
                    if ($zabrana == 3) {
                        $upit3 = "update Korisnik set zakljucan='4' where korisnickoIme='" . $korisnicko_ime . "' ";
                        $baza->updateDB($upit3);
                        $greska = "Unesli ste 4 puta netočnu lozinku, korisnički račuun je zaključan!";
                        $upitK = "select k.id_korisnik from Korisnik as k where k.korisnickoIme='" . $korisnicko_ime . "'";
                        $rezultatK = $baza->selectDB($upitK);
                        if ($rezultatK->num_rows != 0) {
                            $korisnikK = mysqli_fetch_row($rezultatK);
                            $datum = date("Y-m-d ");
                            $vrijeme = date("H:i:s");
                            $url = $_SERVER['PHP_SELF'];
                            $upitdn = str_replace("'", "", $upit3);
                            $radnja = "Zakljucavanje korisnickog racuna";
                            $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                                    . " ('{$korisnikK[0]}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                            $baza->updateDB($dnevnik);
                        }
                    }
                    //dnevnik
                    $upitK = "select k.id_korisnik from Korisnik as k where k.korisnickoIme='" . $korisnicko_ime . "'";
                    $rezultatK = $baza->selectDB($upitK);
                    if ($rezultatK->num_rows != 0) {
                        $korisnikK = mysqli_fetch_row($rezultatK);
                       
$upit = "SELECT pomak FROM Vrijeme WHERE idVrijeme = '1'";
$rezultat = $baza->selectDB($upit);
$pomak = mysqli_fetch_array($rezultat);
$time = time() + ($pomak[0] * 3600);
//dnevnik

$datum = date("Y-m-d ",$time);
$vrijeme = date("H:i:s", $time);
$url = $_SERVER['PHP_SELF'];
$upitdn = str_replace("'", "", $upit3);
$radnja = "Odjava";
$dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,radnja) values "
        . " ('{$korisnikK[0]}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$radnja}')";
$baza->selectDB($dnevnik);
                    }
                }

                $greska = "Netocno uneseno korisnicko ime i/ili lozinka";
            } else {
                $greska = "greska";
            }
        }
    }
}
//smarty podaci
require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);

$smarty->assign('cookieKorisnicko', $frmKorisIme);
$smarty->assign('greska', $greska);

$smarty->display('_header.tpl');
$smarty->display('prijava.tpl');
$smarty->display('_footer.tpl');
ob_end_flush();
?>
