<section id="sadrzaj">  
    <article id="greske"> {$greska} </article>
 
<article>
    <form id="dodavanje" name="dodavanje" enctype="multipart/form-data" action="dodavanjeSlika.php" method="post">
      
         <label for="kategorija"><a href='upload.php'>Upload slika</a></label><br>
        
       
        <br><br>
        <label for="oznake">Dodajte oznake</label><br>

        <label for="id">ID slike</label>
        <select name="id">
            {section name=i loop=$ispis}
                <option value="{$ispis[i].idSlike}">{$ispis[i].idSlike}</option>
            {/section}
        </select><br>
        <label for="oznake"> oznaka:  </label>
        <input type="txt" id="oznake" name="oznake" placeholder="dodajte oznaku "><br><br>

        <input type="checkbox" name ="chbx_tag" id="chbx_tag" ><label for="sortiranje">Sortiranje po oznaci: </label><br>
        <select name="tag">
            {section name=i loop=$ispis1}
                <option value="{$ispis1[i].oznake}">{$ispis1[i].oznake}</option>
            {/section} <br/>
        </select><br><br>
        <input type="submit" value="Pošalji" />
    </form>
</article>
</section>
