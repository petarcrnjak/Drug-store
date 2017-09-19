  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Potvrda rezervacija</h1>
    <article>
        <form name="rezervacije" id="rezervacija" method="POST" enctype='multipart/form-data' action="potvrdaRezervacije.php">

          
        <select name="idRezervacije">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].idRezervacije}">{$ispis[i].idRezervacije}</option>
                {/section}
            </select><br><br>              
            
        <input type="checkbox" name ="chbx_potvrdi" id="chbx_potvrdi" ><label for="chbx_potvrdi"> Potvrdi </label>  <br/>
         <input type="checkbox" name ="chbx_odbi" id="chbx_odbi" ><label for="chbx_odbi"> Odbi </label>  <br/>
                   
            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   
            <br>
               <label for="sortiranje">Sortiranje kolona</label><br>  
         <input type="checkbox" name ="chbx_naziv" id="chbx_naziv" ><label for="chbx_naziv"> po nazivu </label>  <br/>
         <input type="checkbox" name ="chbx_cijena" id="chbx_cijena" ><label for="chbx_cijena"> po cijeni </label>  <br/>
         <input type="checkbox" name ="chbx_potvrda" id="chbx_potvrda" ><label for="chbx_potvrda"> po potvrdi </label>  <br/>

            </section> 
        </form>
    </article>