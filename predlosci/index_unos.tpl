<selection>
    <div id="greske">  {$greska} </div>
</selection>
<section id="sadrzaj">
    <h1>Početna stranica</h1>
    <article>
        <form name="prijava" id="prijava" method="POST" enctype='multipart/form-data' action="index_unos.php">
 
            <label for="kategorija">Izmjena</label><br>

            <label for="naziv"> naziv:  </label>
            <input type="txt" id="naziv" name="naziv" placeholder="naziva lijeka "><br><br>
            <label for="cijena"> cijena:  </label>
            <input type="number" id="cijena" name="cijena" placeholder="cijena lijeka "><br><br>

            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


        </form>
    </article> 
</section>
