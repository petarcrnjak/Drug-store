  
<article class="greske"> {$greska} </article>

<section id="sadrzaj">
    <h1>Aplikativna statistika</h1>
    <article>
        <form name="rezervacije" id="rezervacija" method="POST" enctype='multipart/form-data' action="aplikativnaStatistika.php">

          
            <label for="idRezervacije">Pregled sviđanja/nesviđanja: </label> <br><br> 
           
        <input type="checkbox" name ="chbx_kategorije" id="chbx_kategorije" ><label for="chbx_kategorije"> po kategorijama </label>  <br/>
         <input type="checkbox" name ="chbx_lijekovi" id="chbx_lijekovi" ><label for="chbx_lijekovi"> po lijekovima </label>  <br/>
         <br>
 <input type="checkbox" name ="chbx_vrijeme" id="chbx_lijekovi" ><label for="chbx_vrijeme"> Filtriranje po vremenu </label>  <br/>         
            <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   


            </section> 
        </form>
    </article>