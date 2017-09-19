
<div class="reg"> 

<section class="reg1"">
        <form id="registracija" name="form1" id="registracija" action="registracija.php" method="POST" enctype='multipart/form-data' onsubmit="return provjeri1();">
            <h1>Registracija</h1>
        <p><label for="ime">Ime: </label>
            <input type="text" id="ime" name="ime"  size="20" placeholder="Ime"><br>
                <label for="prez">Prezime: </label>
                <input type="text" id="prez" name="prez"  size="20" placeholder="Prezime"><br>
                <label for="korime">Korisničko ime: </label>
                <input type="text" id="korime" name="korime"  size="20" placeholder="Korisnicko ime"><br>
                <label for="lozinka1">Lozinka: </label>
                <input type="password" id="lozinka1" name="lozinka1"  size="20" placeholder="Lozinka"><br>
                <label for="lozinka2">Potvrda lozinke: </label>
                <input type="password" id="lozinka2" name="lozinka2"  size="20" placeholder="Potvrda lozinke"><br>
                              
                <label for="dan">Rođendan: dan</label>
                <input type="number" id="dan" name="dan" placeholder="dan"><br>
                <label for="mjesec">mjesec</label>
                <select id="mjesec" name="mjesec">
                        <option value="1" >siječanj</option>
                        <option value="2" >veljača</option>
                        <option value="3" >ožujak</option>
                        <option value="4" >travanj</option>
                        <option value="5" >svibanj</option>
                        <option value="6" >lipanj</option>
                        <option value="7" >srpanj</option>
                        <option value="8" >kolovoz</option>
                        <option value="9" >rujan</option>
                        <option value="10" >listopad</option>
                        <option value="11" >studeni</option>
                        <option value="12" >prosinac</option>
                 </select><br>  
                <label for="godina">godina</label>
                <input type="number" id="godina" name="godina" placeholder="godina"><br>
                <label for="spol">Spol </label>
                <select id="spol" multiple="multiple" name="spol">
                    <option value="1">Muski</option>
                    <option value="2">Zenski</option>
                </select><br>
                <label for="drzava">Drzava</label>
                <select id="drzava" name="drzava">
                        <option value="1" >Hrvatska</option>
                        <option value="2" >Mađarska</option>
                        <option value="3" >Slovenija</option>
                        <option value="4" >Austrija</option>
                </select><br>  
                <label for="telefon">Mobilni telefon:</label>
                <input type="tel" id="telefon" name="telefon" maxlength="10" size="20" placeholder="Telefon"><br>
                <label for="email">Email adresa: </label>
                <input type="email" id="email" name="email" placeholder="Email"><br>
               
           <div style="margin-left: 0%;width:40" class="g-recaptcha" data-sitekey="{$siteKey}"></div>
                  <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=eng">
                        
                     </script>
                     <br>
              
                <input id="submit" type="submit" value=" Registriraj se ">       
                <input type="reset" name="reset" id="reset" value="Ponovni unos">
                
                 <article>
        <br>{$greska}
    </article>
        </form>
</section>
                </div>