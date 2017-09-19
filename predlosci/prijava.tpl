  <section id="sadrzaj"> 
	 <article id="greske"> {$greska} </article>
            <article>
                 <form name="prijava" id="prijava" method="POST" enctype='multipart/form-data' action="prijava.php">
                     <label for="korime"> Korisničko ime: </label>  <input type="text" name="korime" id="korime" autofocus  placeholder="Korisnicko ime" value="{$cookieKorisnicko}"> <br/>
                     <label for="lozinka"> Lozinka:</label>   <input type="password" name="lozinka" id="lozinka"  placeholder="Lozinka"><br/><br>
                     <input type="checkbox" name ="chbx_pamti" id="chbx_pamti" ><label for="chbx_pamti"> Zapamti me? </label>  <br/>
                     <input name="potvrda" id="potvrda" type="submit" value="Prijavi se" class="gumb">   
                     <a href="zaboravljena.php">Zaboravljena lozinka?</a>
                </form>
             </article>
        </section>  