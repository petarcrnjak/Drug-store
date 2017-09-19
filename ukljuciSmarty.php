<?php

class UkljuciSmarty{ 
   function __construct(&$smarty) {
    $smarty->setTemplateDir('predlosci/');
  $smarty->setCompileDir('templates_c/');
  $smarty->setCacheDir('vanjske_biblioteke/smarty/cache/');
  $smarty->setConfigDir('vanjske_biblioteke/smarty/configs/');
    //$smarty->assign('tip', $_SESSION['tipKorisnika']);
   // $smarty->assign('korisnickoIme',  $_SESSION['email']);
   // $smarty->assign('Ime',  $_SESSION['Ime']);
   // $smarty->assign('Prezime', $_SESSION['Prezime']);
  //  $smarty->assign('zadnjaPrijava', $_SESSION['vrijemeZadnje']);
  //  $smarty->assign('id', $_SESSION['idKorisnika']);
   }
}

?>