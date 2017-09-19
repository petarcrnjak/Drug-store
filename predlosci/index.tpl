<selection>
    <div id="greske">  {$greska} </div>
</selection>
<section id="sadrzaj">
     <h1>Početna stranica</h1>
      <article>
               <form name="prijava" id="prijava" method="POST" enctype='multipart/form-data' action="index.php">
                    
        <label for="kategorija">Kategorije lijekova</label><br>
              
        <select name="kategorija">
        {section name=i loop=$ispis}
        <option value="{$ispis[i].idKategorije}">{$ispis[i].naziv}</option>
        {/section}
        </select><br><br>
        
                <input name="potvrda" id="potvrda" type="submit" value="Potvrdi" class="gumb">   
                       
                     
                </form>
             </article> 
</section>
       