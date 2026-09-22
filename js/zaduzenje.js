
function dodajStavku() {
    const stavke = document.getElementById("stavke");
    const prvaStavka = document.querySelector(".stavka");
    const novaStavka = prvaStavka.cloneNode(true);

    novaStavka.querySelectorAll("input").forEach(function (input) {
        input.value = "";
    });

    novaStavka.querySelectorAll("select").forEach(function (select) {
        select.selectedIndex = 0;
    });

    stavke.appendChild(novaStavka);
}

document.querySelector("form").addEventListener("submit", function (e) {
    const kolicine = document.querySelectorAll("input[name='kolicina[]']");

    for (let kolicina of kolicine) {
        if (kolicina.value === "" || parseInt(kolicina.value) <= 0) {
            alert("Količina mora biti veća od 0.");
            kolicina.focus();
            e.preventDefault();
            return;
        }
    }
});

