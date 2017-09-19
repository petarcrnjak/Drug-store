  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Upravljanje korisničkim računima</h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="otkljucavanje.php">

            <label for="korisnik"> ID korisnika: </label><br>
           <select name="korisnik">
                {section name=i loop=$ispis}
                    <option value="{$ispis[i].id_korisnik}">{$ispis[i].id_korisnik}</option>
                {/section}
            </select><br><br>   

            <input type="checkbox" name ="chbx_otklj" id="chbx_otklj" ><label for="chbx_otklj"> otkljucaj </label>  <br/>
            <input type="checkbox" name ="chbx_zaklj" id="chbx_zaklj" ><label for="chbx_zaklj"> zakljucaj </label>  <br/>

            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


            </section> 
        </form>
    </article>