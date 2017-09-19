<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

    <head>

        <title>View Records</title>

    </head>

    <body>
        <?php
        include_once './baza.class.php';
        $baza = new Baza();
        $page_name = "stranicenje.php";

        $per_page = 10;

        $upit3 = "select * from Lijekovi";
        $podaci = $baza->selectDB($upit3);
        $total_results=mysqli_num_rows($podaci);
        $total_pages = mysqli_num_rows($podaci/10);
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $show_page = $_GET['page'];

            if ($show_page > 0 && $show_page <= $total_pages) {

                $start = ($show_page - 1) * $per_page;

                $end = $start + $per_page;
            } else {
                $start = 0;

                $end = $per_page;
            }
        } else {
            $start = 0;
            $end = $per_page;
        }

        if ($podaci->num_rows != 0) {
            // $greska = "Pregled statistike lajkova po lijekovima";
            echo "<table border=1 class=db-table>";
            echo "<caption>Aplikativna statistika po lijekovima </caption>";
            echo "<thead>"
            . "<th>id_lijek</th>"
            . "<th>id_korisnik</th>"
            . "<th>like ili dislike (L/D)</th>"
            . "<th>datum nastanka</th>"
            . "</thead>";
            for ($i = $start; $i < $end; $i++) {
                if ($i == $total_results) {
                    break;
                }
                while ($red = $podaci->fetch_array()) {

                    echo "<tr>"
                    . "<td>$red[0]</td>"
                    . "<td>$red[1]</td>"
                    . "<td>$red[2]</td>"
                    . "<td>$red[3]</td>"
                    . "</td>";

                    echo "</tr>";
                }
            }} else {
                echo "Birana kategorija je prazna <br> ";
            }
        
        ?>
    </body>