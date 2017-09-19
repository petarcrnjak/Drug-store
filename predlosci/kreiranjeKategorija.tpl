  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Kreiranje kategorija</h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="kreiranjeKategorija.php">

            
            <label for="moderator">Dodjeli moderatora</label><br>

            <select name="moderator">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].id_korisnik}">{$ispis[i].korisnickoIme}</option>
                {/section}
            </select><br><br>
             <label for="kategorija">Unesite naziv nove kategorije: </label>
            <input type="txt" id="kategorija" name="kategorija" placeholder="naziv nove kategorije"><br><br>


            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   

       
            </section> 
        </form>
    </article>