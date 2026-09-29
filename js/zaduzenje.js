function dodajStavku() {

    const stavke = document.getElementById("stavke");
    const prvaStavka = document.querySelector(".stavka");

    if (!prvaStavka) {
        return;
    }

    const novaStavka = prvaStavka.cloneNode(true);

    const brojStavki =
        stavke.querySelectorAll(".stavka").length;

    novaStavka
        .querySelectorAll("input, select, textarea")
        .forEach(function (element) {

            const name = element.getAttribute("name");

            if (name) {

                const noviName = name.replace(
                    /stavke\[\d+\]/,
                    "stavke[" + brojStavki + "]"
                );

                element.setAttribute(
                    "name",
                    noviName
                );
            }
        });

    novaStavka
        .querySelectorAll("input")
        .forEach(function (input) {

            input.value = "1";
        });

    novaStavka
        .querySelectorAll("select")
        .forEach(function (select) {

            select.selectedIndex = 0;
        });

    novaStavka
        .querySelectorAll("textarea")
        .forEach(function (textarea) {

            textarea.value = "";
        });

    stavke.appendChild(novaStavka);
}


document
    .getElementById("dodaj-stavku")
    .addEventListener(
        "click",
        dodajStavku
    );


document
    .querySelector("form")
    .addEventListener(
        "submit",
        function (e) {

            const kolicine =
                document.querySelectorAll(
                    "input[name*='[kolicina]']"
                );

            for (let kolicina of kolicine) {

                if (
                    kolicina.value === "" ||
                    parseInt(kolicina.value) <= 0
                ) {

                    alert(
                        "Količina mora biti veća od 0."
                    );

                    kolicina.focus();

                    e.preventDefault();

                    return;
                }
            }
        }
    );