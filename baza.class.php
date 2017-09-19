   <?php
        // put your code here
        class Baza{
            const server = "localhost";
            const baza ="WebDiP2015x012";
            const korisnik ="WebDiP2015x012";
            const lozinka = "admin_TY2Y";
       
           private function spojiDB(){
                $mysqli=new mysqli(self::server, self::korisnik, self::lozinka,self::baza);             
                 if($mysqli->connect_errno) {
                            echo"Neuspješno spajanje na bazu:". $mysqli->connect_errno .",".$mysqli->connect_error;
                            
                        }
                         if (!$mysqli->set_charset("utf8")) {
                    echo("Greška u postavljanju utf8 encodinga:".$mysqli->error);
                     } 
                        return $mysqli;
            }
            function zatvoriVezuDB($veza){
                  $uspjelo= mysqli_close($veza);
                  return $uspjelo;
            }
            
            function selectDB($upit){
                            $veza = self::spojiDB();
                            $rezultat = $veza->query($upit) or trigger_error("Greska kod upita:($upit)-" 
                            . "Greška" . $veza->error . " " . E_USER_ERROR);
                            if(!$rezultat){ 
                                 $rezultat=null;
                            }
                            self::zatvoriVezuDB($veza);
                            return $rezultat;
                     }
           function updateDB($upit, $skripta="") //skripta je opcionalan parametar (deklarirao se sa ="")
                {
                    $veza = self::spojiDB();
                    $rezultat = $veza->query($upit); //za select vraća sam objekt a za ostale (INSERT/UPDATE/DELETE) true/false
                    if($rezultat){ 
                       self::zatvoriVezuDB($veza);
                        if($skripta != ""){
                           header("Location: $skripta"); //preusmjerava lokaciju na adresu "skripta"
                        }
                        return $rezultat;
                    }
                    else {
                        echo "Pogreška :" .$veza->error;
                       self::zatvoriVezuDB($veza);
                        return $rezultat;
                    }
                 }
}
   ?>
