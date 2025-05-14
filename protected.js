console.log("Hello from Protected JS!");

// fetch
fetch("api/protected.php")
  .then((res) => res.json()) // ✅ Rückgabe des JSON
  .then((data) => {
    console.log(data);

    if (data.status === "error") {
      // redirect to login page
        window.location.href = "login.html";
    } else {
      // write hello username to html
      document.getElementById("welcome-message").innerHTML = "Willkommen " + data.username;
    }


  })
  .catch((err) => {
    console.error("Fehler beim Abrufen der geschützten Daten:", err);
  });












  // Heute + 6 Folgetage anzeigen
const tageKurz = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];
const monateLang = ["Januar", "Februar", "März", "April", "Mai", "Juni",
                    "Juli", "August", "September", "Oktober", "November", "Dezember"];

function generiereDatumsliste() {
  const heute = new Date();
  const datumAnzeigen = document.getElementById("heute-datum");
  const tageContainer = document.getElementById("wochentage-liste");

  // Heutiges Datum anzeigen
  const wochentag = tageKurz[heute.getDay()];
  const tag = heute.getDate();
  const monat = monateLang[heute.getMonth()];
  datumAnzeigen.textContent = `Heute: ${wochentag}, ${tag}. ${monat}`;

  // Nächste 7 Tage generieren
  for (let i = 0; i < 7; i++) {
    const datum = new Date();
    datum.setDate(datum.getDate() + i);

    const tagText = `${tageKurz[datum.getDay()]} ${datum.getDate()}.${datum.getMonth() + 1}`;
    const button = document.createElement("button");
    button.textContent = tagText;
    button.dataset.datum = datum.toISOString().split("T")[0]; // z. B. 2025-05-14

    button.addEventListener("click", () => {
  const gewähltesDatum = button.dataset.datum;
  ladeMedikamenteFür(gewähltesDatum);
});



async function ladeMedikamenteFür(datum) {
  const container = document.getElementById("medikamentenliste");
  container.innerHTML = ""; // vorherige Einträge leeren

  try {
    const res = await fetch(`api/medis_liste.php?datum=${datum}`);
    const data = await res.json();

    if (data.status === "success" && data.medikamente.length > 0) {
      data.medikamente.forEach(medi => {
        const box = document.createElement("div");
const datetimeString = `${medi.start_date}T${medi.start_time}`; // z. B. "2025-05-14T08:30"
const zeit = new Date(datetimeString).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
});
        box.innerHTML = `<strong>${medi.name}</strong><br>um ${zeit}`;
        box.style.border = "1px solid #aaa";
        box.style.padding = "10px";
        box.style.marginBottom = "10px";
        container.appendChild(box);
      });
    } else {
      container.textContent = "Keine Medikamente für diesen Tag.";
    }

  } catch (err) {
    console.error("Fehler beim Laden:", err);
    container.textContent = "Fehler beim Laden der Daten.";
  }
}


    tageContainer.appendChild(button);
  }
}

// Beim Laden starten
document.addEventListener("DOMContentLoaded", generiereDatumsliste);