<?php

ob_start();
header('Content-Type: text/html; charset=utf-8');
include_once './baza.class.php';
$greska = "";
$ispis = "";

$baza = new Baza();

$upitAkcija = "select idKategorije,naziv from Kategorije";
$podaci = $baza->selectDB($upitAkcija);
while ($red = mysqli_fetch_array($podaci)) {
    $ispis[] = $red;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategorija = $_POST['kategorija'];
    if (empty($_POST['kategorija'])) {
        $greska.= "Morate unesti kategorije! <br>";
    } 
    else {
        $upitAkcija = "select distinct l.idLijekovi,l.naziv,a.nova_cijena from Lijekovi as l join Kategorije as k on l.Kategorije=k.idKategorije join Akcije as a on a.Lijekovi=l.idLijekovi where k.idKategorije='" . $kategorija . "' order by a.traje_do limit 3";
        $podaci = $baza->selectDB($upitAkcija);
        if ($podaci->num_rows != 0) {
            echo "<table border=1 class=db-table>";
            echo "<caption>Lijekovi </caption>";
            echo "<thead>"
           . "<th>ID lijeka</th>"
            . "<th>naziv</th>"
            . "<th>cijena (kn)</th>"
            . "</thead>";
            while ($red = $podaci->fetch_array()) {
                echo "<tr>"
                . "<td><a href='index_unos.php?idLijekovi=$red[0]'>$red[0]</a></td>"
                . "<td>$red[1]</td>"
                . "<td>$red[2]</td>"
                . "</td>";
            }
        } else {
            $greska.= "Kategorija '$kategorija' je prazna";
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
$smarty->display('index.tpl');
$smarty->display('_footer.tpl');

ob_end_flush();
?>  