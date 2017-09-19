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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$korisnickoIme = $_SESSION['korisnickoIme'];
$korisnik = $_SESSION['id_korisnik'];
$target_dir = "./img/slike/";
$target_file = $target_dir . basename($_FILES["slike"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// provjera jel je slika
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["slike"]["tmp_name"]);
    if ($check !== false) {
        $greska = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $greska = "File is not an image.";
        $uploadOk = 0;
    }
}
if (file_exists($target_file)) {
    $greska = "Sorry, file already exists.";
    $uploadOk = 0;
}
if ($_FILES["slike"]["size"] > 500000) {
    $greska = "Sorry, your file is too large.";
    $uploadOk = 0;
}
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    $greska = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    $greska = "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["slike"]["tmp_name"], $target_file)) {
        $greska = "The file " . basename($_FILES["slike"]["name"]) . " has been uploaded.<br>";

        //rad s bazom
        $url = "https://barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x012/img/slike/" . $_FILES["slike"]["name"];
        $naziv = basename($_FILES["slike"]["name"]);

        $upit = "insert into Slike (Korisnik,url,naziv) values ('" . $korisnik . "','" . $url . "','" . $naziv . "')";
        $baza->updateDB($upit);
    } else {
        $greska = "Sorry, there was an error uploading your file.<br>";
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
$smarty->display('upload.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  

