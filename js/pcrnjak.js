function provjeri1() {
    var ime = document.form1.ime.value;
    var prez = document.form1.prez.value;
    var korime = document.form1.korime.value;
    var spol = document.form1.spol.value;
    var lozinka1 = document.form1.lozinka1.value;
    var lozinka2 = document.form1.lozinka2.value;
    var dan = parseInt(document.form1.dan.value);
    var godina = parseInt(document.form1.godina.value);
    var mjesec = document.form1.mjesec.value;
    var telefon = parseInt(document.form1.telefon.value);
    var email = document.form1.email.value;



    if (ime === null || ime === "") {
        alert("Morate unesti ime");
        return false;
    }
    if (prez === null || prez === "") {
        alert("Morate unesti prezime");
        return false;
    }
    if (korime === null || korime === "") {
        alert("Morate unesti korisničko ime");
        return false;
        }
        if (document.form1.korime.value.length < 10)
        {
            alert("Korisničko ime mora sadržavati barem 10 znakova!");
            return false;
        }
        if (korime.toLowerCase() === korime) {
            alert("Korisničko ime ne sadrzi jedno veliko slovo");
            return false;
        }
        if (lozinka1 === null || lozinka1 === "") {
            alert("Morate unesti lozinku");
            return false;
        }
        if (document.form1.lozinka1.value.length < 8)
        {
            alert("Lozinka mora sadržavati barem 8 znakova!");
            return false;
        }
        if (lozinka1.toLowerCase() === lozinka1) {
            alert("Lozinka ne sadrzi jedno veliko slovo");
            return false;
            if (lozinka1.toUpperCase() === lozinka1) {
                alert("Lozinka ne sadrzi jedno malo slovo");
                return false;
            }
        }
        if (lozinka1 !== lozinka2)
        {
            alert("Lozinke nisu identične!");
            return false;
            s
        }
        if (isNaN(dan))
        {
            alert("Morate unesti dan!");
            return false;
        }
        if (dan < 1)
        {
            alert("Dan sadrzi negativne brojeve ili 0");
            return false;
        }
        if (mjesec === null || mjesec === "") {
            alert("Morate unesti mjesec");
            return false;
        }
        if (document.getElementById("mjesec").tagNameS === "DATALIST") {
            alert("Mjesec nije tipa datalist");
            return false;
        }
        if (isNaN(godina))
        {
            alert("Morate unesti godinu!");
            return false;
        }
        if (godina < 1)
        {
            alert("Godina sadrzi negativne brojeve ili nulu");
            return false;
        }
        if (godina < 1930 || godina > 2015)
        {
            alert("Godina nije između 1930 i 2015");
            return false;
        }
        if (spol === null || spol === "") {
            alert("Morate unesti spol");
            return false;
        }
        if (isNaN(telefon))
        {
            alert("Morate unesti brojeve telefona!");
            return false;
        } else if (telefon === document.form1.telefon.value) {
            alert("Niste upisali samo broj telefona!");
            return false;
        } else if (document.form1.telefon.value.length !== 10)
        {
            alert("Telefon nije ispravno unesen (XXX XXXXXXX)");
            return false;
        }
        if (email === null || email === "")
        {
            alert("Morate unesti email");
            return false;
        } else
            return true;
}