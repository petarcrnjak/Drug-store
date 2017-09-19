  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Kreiranje nove poslovnice</h1>
    <article>
        <form name="rezervacija" id="rezervacija" method="POST" enctype='multipart/form-data' action="poslovnice.php">

            <label for="broj">broj: </label>
            <input type="txt" id="broj" name="broj" placeholder="broj poslovnice"><br><br>
            <label for="ulica">ulica: </label>
            <input type="txt" id="ulica" name="ulica" placeholder="ulica poslovnice"><br><br>
            <label for="grad">grad: </label>
            <input type="txt" id="grad" name="grad" placeholder="grad poslovnice"><br><br>
            <label for="drzava">drzava: </label>
            <input type="txt" id="drzava" name="drzava" placeholder="drzava poslovnice"><br><br>
            <label for="radno">radno vrijeme: </label>
            <input type="txt" id="radno" name="radno" placeholder="radno vrijeme poslovnice"><br><br>


            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


            </section> 
        </form>
    </article>