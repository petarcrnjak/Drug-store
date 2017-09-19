  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Dodavanje stavki</h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="dodavanjeStavki.php">

            
            <label for="racuni">  Izaberite ID računa: </label><br><br>
           <select name="racuni">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].idRacuni}">{$ispis[i].idRacuni}</option>
                {/section}
            </select><br><br>

            <label for="rezervacije">  Izaberite ID rezervacije: </label><br><br>
           <select name="rezervacije">
                {section name=i loop=$print}
                    <option value="{$print[i].idRezervacije}">{$print[i].idRezervacije}</option>
                {/section}
            </select><br><br>
            
            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   

       
            </section> 
        </form>
    </article>