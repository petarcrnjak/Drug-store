<?php

ob_start();
header('Content-Type: text/html; charset=utf-8');

include_once './baza.class.php';
include_once "recaptcha/AutoLoad.php";
$baza = new Baza();
$siteKey = '6LdlByITAAAAAFr0Zr7S6Oyxa-7zCe_DZq9xL0t8';
$secret = '6LdlByITAAAAAF0Vj2fevBXFp8xtziE1ezb2dUTA';

$greska = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ime = $_POST['ime'];
    $prez = $_POST['prez'];
    $korime = $_POST['korime'];
    $lozinka1 = $_POST['lozinka1'];
    $lozinka2 = $_POST['lozinka2'];
    $dan = $_POST['dan'];
    $mjesec = $_POST['mjesec'];
    $godina = $_POST['godina'];
    $drzava = $_POST['drzava'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];


//korime

if (!preg_match('^[a-z]{1}^', $korime)) {
    $greska.="Korisnicko ime ne pocinje malom slovom <br>";
}
$provjera = '([_,-,!,#,$,?]{2})';
if (!preg_match($provjera, $korime)) {
    $greska.="Korisnicko ime mora sadrzavati 2 spec znaka <br>";
}

//lozinka
if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/", $lozinka1)) {
    $greska .= "Lozinka mora sadrzavati velika i mala slova i brojeve <br>";
}
$provjera2 = '([_,-,!,#,$,?]{2})';
if (!preg_match($provjera2, $lozinka1)) {
    $greska.="Lozinka mora sadrzavati najmanje 2 spec znaka <br>";
}
if ($lozinka2 == '') {
    $greska.="Potvrda lozinke nije unesena <br>";
}

//email

if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/", $email)) {
    $greska.="Email nije dobro unesen (nesto@nesto.nesto)<br>";
}

$recaptcha = new recaptcha\ReCaptcha($secret);
$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

if (!$resp->isSuccess()) {
    $greska .= "Recaptcha krivo unesena.</br>";
}
if (empty($greska)) {
    if ($_POST['spol'] == 1) {
        $spol = "musko";
    } else {
        $spol = "zensko";
    }
    if ($_POST['drzava'] == 1) {
        $drzava = "Hrvatska";
    }
    if ($_POST['drzava'] == 2) {
        $drzava = "Madjarska";
    }
    if ($_POST['drzava'] == 3) {
        $drzava = "Slovenija";
    } 
    if ($_POST['drzava'] == 4) {
        $drzava = "Austrija";
    }
    $telefon = $_POST['telefon'];
    $aktivacijskikod = md5($korime . time());
    $oblik = "Y-m-d H:i:s";
    $vrijeme1 = new DateTime(date($oblik));
    $vrijeme2 = $vrijeme1->format($oblik);
   $upit3 = "INSERT into Korisnik (id_korisnik,ime,prezime,korisnickoIme,lozinka,potvrdaLozinke,dan,mjesec,godina,spol,drzava,telefon,email,aktivacijskiKod,vrijemeRegistracije,tipKorisnika) "
                    . "values(default, '{$ime}', '{$prez}', '{$korime}', '{$lozinka1}', '{$lozinka2}', '{$dan}', '{$mjesec}', '{$godina}', '{$spol}', '{$drzava}', '{$telefon}', '{$email}', '{$aktivacijskikod}', "
                    . "'{$vrijeme2}','0');";
        
            
                 if($baza->updateDB($upit3)){
                     $primatelj=$email;
                     $naslov = "Aktivacija korisnickog racuna";
                     $poruka = "Postovani,\n\nmolimo vas da aktivirate svoj korisnicki racun putem aktivacijskog linka: https://barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x012/aktivacija.php?aktivacijskiKod={$aktivacijskikod}";
                     mail($primatelj,$naslov,$poruka);
                     $greska.="Uspjesno ste se registirrali, provjerite mail za aktivaciju";
                     
               //dnevnik
                    $upit="select id_korisnik from Korisnik where Korisnik.email='".$email."'";
                    $podaci=$baza->selectDB($upit);
                    $korisnik = mysqli_fetch_row($podaci);
                    $datum = date("Y-m-d ");
                    $vrijeme = date("H:i:s");
                    $url = $_SERVER['PHP_SELF'];
                    $upitdn = str_replace("'", "", $upit3);
                    $radnja = "Registracija";
                    $dnevnik = "insert into Dnevnici (Korisnik,datum,vrijeme,url,poruka,upit,radnja) values "
                            . " ('{$korisnik[0]}','{$datum}','{$vrijeme}','{$url}','{$greska}','{$upitdn}','{$radnja}')";
                    $baza->selectDB($dnevnik);
                     header( "refresh:3; url=prijava.php" ); 
                 }
                  else {
                         $greska.= "Greska pri radu s bazom podataka <br>";
                     }
                     
                     }
}
   
require_once 'vanjske_biblioteke/smarty/libs/Smarty.class.php';
require_once 'ukljuciSmarty.php';
$smarty = new Smarty();
$obj = new UkljuciSmarty($smarty);
$smarty->assign('greska', $greska);
$smarty->assign('siteKey', $siteKey);
$smarty->display('_header.tpl');
$smarty->display('registracija.tpl');
$smarty->display('_footer.tpl');
ob_end_flush();
?>
