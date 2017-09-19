  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Posjecenost stranica </h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="upiti.php">

            <label for="kategorija">Upiti datum</label>
            <select name="kategorija">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].Korisnik}">{$ispis[i].korisnickoIme}</option>
                {/section}
            </select><br>
            <input type="checkbox" name ="chbx_svi" id="chbx_svi" ><label for="chbx_svi">Potvrda rezervacije </label>  <br/>
            <input type="checkbox" name ="chbx_odjava" id="chbx_odjava" ><label for="chbx_odjava">Insert stavke </label>  <br/>
            <input type="checkbox" name ="chbx_treca" id="chbx_treca" ><label for="chbx_treca">Inesrt poslovnice </label>  <br/>
            <input type="checkbox" name ="chbx_aplikativna" id="chbx_aplikativna" ><label for="chbx_aplikativna">Insert lajkanje </label>  <br/>
            <input type="checkbox" name ="chbx_akcije" id="chbx_akcije" ><label for="chbx_akcije">Insert akcije </label>  <br/>

            <br><label for="chbx_"> Sortiranje</label> 
            <input type="checkbox" name ="chbx_sort" id="chbx_sort" ><label for="chbx_sort"> Sortiranje po vremenu</label>  <br/>
   
            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


            </section> 
        </form>
    </article>