  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Posjecenost stranica </h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="posjecenost.php">

            <label for="kategorija">Datum</label>
            <select name="kategorija">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].datum}">{$ispis[i].datum}</option>
                {/section}
            </select><br>
            <input type="checkbox" name ="chbx_svi" id="chbx_svi" ><label for="chbx_svi">Prijava </label>  <br/>
            <input type="checkbox" name ="chbx_odjava" id="chbx_odjava" ><label for="chbx_odjava">Odjava </label>  <br/>
            <input type="checkbox" name ="chbx_treca" id="chbx_treca" ><label for="chbx_treca">Statistika svidjanja </label>  <br/>
            <input type="checkbox" name ="chbx_aplikativna" id="chbx_aplikativna" ><label for="chbx_aplikativna">Aplikativna statistika </label>  <br/>
            <input type="checkbox" name ="chbx_akcije" id="chbx_akcije" ><label for="chbx_akcije">Definiranje lijekova </label>  <br/>

            <br><label for="chbx_"> Sortiranje</label> 
            <input type="checkbox" name ="chbx_sort" id="chbx_sort" ><label for="chbx_sort"> Sortiranje po korisnicima</label>  <br/>
    <label for="upiti"><a href='upiti.php'>Statistika upiti</a></label><br>
            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


            </section> 
        </form>
    </article>