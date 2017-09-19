  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Definiranje lijekova</h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="definiranjeLijekova.php">

            <label for="kategorija">Kategorije lijekova</label><br>

            <select name="kategorija">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].idKategorije}">{$ispis[i].naziv}</option>
                {/section}
            </select><br><br>
            <input name="potvrda" id="potvrda" type="submit" value="Izaberi" class="gumb"><br> <br><br>  


            <label for="lijekovi">Unesite naziv novog lijeka: </label>
            <input type="txt" id="lijekovi" name="lijekovi" placeholder="novi lijek"><br><br>

            <label for="cijena">Unesite cijenu novog lijeka: </label>
            <input type="number" id="cijena" name="cijena" placeholder="cijena novog lijeka"><br><br>


            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   

            <br><br>
               <label for="sortiranje">Sortiranje kolona</label><br>  
         <input type="checkbox" name ="chbx_naziv" id="chbx_naziv" ><label for="chbx_naziv"> po nazivu </label>  <br/>
         <input type="checkbox" name ="chbx_cijena" id="chbx_cijena" ><label for="chbx_cijena"> po cijeni </label>  <br/>
         
            </section> 
        </form>
    </article>