<?php
header('Content-Type: text/html; charset=utf-8');
$frmKorisIme = "";
$greska = "";


include_once '../baza.class.php';
$baza = new Baza();
$upit="SELECT * FROM Korisnik as k";
$rezultat = $baza->selectDB($upit);
if ($rezultat->num_rows != 0) {

$table="";
$table.= "<table>
                <tr>
                    <th>ID korisnika</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Korisnicko ime</th>
                    <th>Lozinka</th>
                    <th>Email</th>
                    <th>Status korisnika</th>
                    <th>Tip korisnika</th>
                    <th>Zakljucan</th>
                </tr>";
while($row = mysqli_fetch_assoc($rezultat)){
    $table.= "<tr>";
    $table.= "<td>" . $row['id_korisnik'] . "</td>";
    $table.= "<td>" . $row['ime'] . "</td>";
    $table.= "<td>" . $row['prezime'] . "</td>";
    $table.= "<td>" . $row['korisnickoIme'] . "</td>";
    $table.= "<td>" . $row['lozinka'] . "</td>";
    $table.= "<td>" . $row['email'] . "</td>";
    $table.= "<td>" . $row['statusKorisnika'] . "</td>";
    $table.= "<td>" . $row['tipKorisnika'] . "</td>";
    $table.= "<td>" . $row['zakljucan'] . "</td>";
    $table.= "</tr>";
}
$table.= "</table>";
}
else{
    $greska="Nije moguce dohvatiti";
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Projekt ljekarna</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="application-name" content="Ljekarna">
        <meta name="author" content="Petar Crnjak">
        <meta name="description" content="Datum izrade: 5.6.2016"> 
        <link rel="stylesheet" type="text/css" href="../css/pcrnjak.css"/>
        <style type="text/css">
            label {     font-style: initial;}
            input {     
                color: initial;
                letter-spacing: normal;
                word-spacing: normal;
                text-indent: 0px;
                font-style: italic;
                background-color: #E4433c;
                font: 13.3333px Arial;}
            </style>
        </head>
        <body>
            <div id="header">
            <figure>
                <h1 id="pocetak">Web dizajn i programiranje</h1>
                <p>Zadaća 4</p>
                <ul>
                    <li> <a  href='odjava.php ' class="mail-link"> Odjava </a> </li> 
                </ul>
            </figure>   
        </div>
        <aside><nav>
                <ul>
                    <li><a href="../index.php" >Indeks</a></li>
                    <li><a href="../registracija.php" >Registracija</a></li>
                    <li><a href="../prijava.php">Prijava</a></li>
                    <li><a href="../rezervacija1.php">Rezervacija</a></li>
                    <li><a href="../registracija.php">Registracija</a></li>
                    <li><a href="../moji_racuni.php">Moji racuni</a></li>
                    <li><a href="slike.php">Moje slike</a></li>
                    <li><a href="../definiranjeAkcija.php">Definiranje akcija</a></li>
                    <li><a href="../definiranjeLijekova.php">Definiranje lijekova</a></li>

                </ul>
            </nav>
        </aside>
<div>
        <section class="sadrzaj">
            <h2 style="text-align: center" >Sadrzaj</h2>

            <div id="table-container">
                <?=$table ?>
            </div>

    </section>
</div>
             <footer>
        <div id="footer">
            <p>Vrijeme potrebno za rješavanja zadatka: 58h</p>

            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2015%2Fzadaca_04%2Fpcrnjak%2Fprijava.php" target="_blank">
                <img class="slike" src="https://barka.foi.hr/WebDiP/2015/materijali/zadace/HTML5.png" alt="html" width="50" height="50">
            </a>

            <a href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2015%2Fzadaca_04%2Fpcrnjak%2Fprijava.php&profile=css3&usermedium=all&warning=1&vextwarning=&lang=en" target="_blank">
                <img class="slike" src="https://barka.foi.hr/WebDiP/2015/materijali/zadace/CSS3.png" alt="css" width="50" height="50">
            </a>
            <p>&copy; 2016 P. Crnjak</p>
        </div>
    </footer>
</body>
</html>
