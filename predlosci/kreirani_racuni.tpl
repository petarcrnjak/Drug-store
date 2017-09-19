<selection>
    <div id="greske">  {$greska} </div>
</selection>
<section id="sadrzaj">
    <h1>Sortiranje kolona</h1>

    <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="kreirani_racuni.php">


        <label for="sortiranje"></label><br>  
        <input type="checkbox" name ="chbx_datum" id="chbx_datum" ><label for="chbx_datum"> po datumu </label>  <br/>
        <input type="checkbox" name ="chbx_iznos" id="chbx_iznos" ><label for="chbx_iznos"> po iznosu </label>  <br/>
        <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb"> 
        </section> 
    </form>
