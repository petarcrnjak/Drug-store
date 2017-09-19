<?php

ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$greska = "";
$ispis = "";
$ispis1 = "";
$baza = new Baza();

$idLijekovi = $_GET['idLijekovi'];
$ispis1 = "'" . $idLijekovi . "'";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $naziv = $_POST['naziv'];
    $cijena = $_POST['cijena'];
   
    if (isset($_POST['naziv'])) {
        $upitAkcija = "updat Lijekovi set naziv='" . $naziv . "' "
                . " where idLijekovi='$idLijekovi' ";
        if ($baza->updateDB($upitAkcija)) {
            $greska.= "Naziv lijeka je uspjesno promjenjen!<br>";
            // header("Location:index_unos.php?idLijekovi='.$idLijekovi.'");
            header("refresh:3; url=index.php");
        } else {
            $greska = "greska kod update";
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
$smarty->display('index_unos.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  
