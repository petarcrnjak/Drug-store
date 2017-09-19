  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Dnevnik aktivnosti</h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="dnevnik.php">

            <label for="kategorija">Datum</label>
            <select name="kategorija">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].datum}">{$ispis[i].datum}</option>
                {/section}
            </select><br>
              <input type="checkbox" name ="chbx_svi" id="chbx_svi" ><label for="chbx_svi"> Svi korisnici </label>  <br/>
            <input name="potvrda" id="potvrda" type="submit" value="Izaberi" class="gumb"><br>  


            <label for="korisnici">Pojedinačni pregled, korisnicko ime: </label>
           
 <select name="korisnici">
                {section name=i loop=$ispis1}
                    <option value="{$ispis1[i].id_korisnik}">{$ispis1[i].korisnickoIme}</option>
                {/section}
            </select><br>
             <input type="checkbox" name ="chbx_datum" id="chbx_datum" ><label for="chbx_datum"> Svi datumi</label>  <br/>
            <input type="checkbox" name ="chbx_radnja" id="chbx_radnja" ><label for="chbx_radnja"> Sortiranje po radnji</label>  <br/>
        
            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


            </section> 
        </form>
    </article>