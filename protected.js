console.log("Hello from Protected JS!");

// Login prüfen und Benutzername anzeigen
fetch("api/protected.php")
  .then((res) => res.json())
  .then((data) => {
    if (data.status === "error") {
      window.location.href = "login.html";
    } else {
      document.getElementById("welcome-message").innerHTML =
        "Willkommen " + data.username;
    }
  })
  .catch((err) => {
    console.error("Fehler beim Abrufen der geschützten Daten:", err);
  });

// Tag-Kürzel und Monatsnamen
const tageKurz = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];
const monateLang = [
  "Januar", "Februar", "März", "April", "Mai", "Juni",
  "Juli", "August", "September", "Oktober", "November", "Dezember"
];

// Datumsauswahl generieren
function generiereDatumsliste() {
  const heute = new Date();
  const datumAnzeigen = document.getElementById("heute-datum");
  const tageContainer = document.getElementById("wochentage-liste");

  // Heutiges Datum anzeigen
  const wochentag = tageKurz[heute.getDay()];
  const tag = heute.getDate();
  const monat = monateLang[heute.getMonth()];
  datumAnzeigen.textContent = `Heute: ${wochentag}, ${tag}. ${monat}`;

  // Nächste 7 Tage-Buttons generieren
  for (let i = 0; i < 7; i++) {
    const datum = new Date();
    datum.setDate(datum.getDate() + i);

    const tagText = `${tageKurz[datum.getDay()]} ${datum.getDate()}.${datum.getMonth() + 1}`;
    const button = document.createElement("button");
    button.textContent = tagText;
    button.dataset.datum = datum.toISOString().split("T")[0];

    button.addEventListener("click", () => {
      const gewähltesDatum = button.dataset.datum;
      ladeMedikamenteFür(gewähltesDatum);
    });

    tageContainer.appendChild(button);

    // Heute automatisch laden
    if (i === 0) {
      ladeMedikamenteFür(button.dataset.datum);
    }
  }
}

// Medikamente laden und anzeigen
async function ladeMedikamenteFür(datum) {
  const container = document.getElementById("medikamentenliste");
  container.innerHTML = ""; // Vorherige Einträge leeren

  try {
    const res = await fetch(`api/medis_liste.php?datum=${datum}`);
    const data = await res.json();

    if (data.status === "success" && data.medikamente.length > 0) {
      data.medikamente.forEach((entry) => {
        const box = document.createElement("div");
        const zeit = entry.time?.slice(0, 5); // z. B. "08:00"

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.checked = entry.taken == 1;

        checkbox.addEventListener("change", async () => {
          try {
            await fetch("api/update_schedule.php", {
              method: "POST",
              body: new URLSearchParams({
                schedule_id: entry.schedule_id,
                taken: checkbox.checked,
              }),
            });

            box.style.backgroundColor = checkbox.checked ? "#d4f7d4" : "white";
            box.style.textDecoration = checkbox.checked ? "line-through" : "none";
          } catch (err) {
            console.error("Fehler beim Aktualisieren:", err);
          }
        });

        box.innerHTML = `<strong>${entry.name}</strong><br>um ${zeit}`;
        box.appendChild(document.createElement("br"));
        box.appendChild(checkbox);

        box.style.border = "1px solid #aaa";
        box.style.padding = "10px";
        box.style.marginBottom = "10px";
        box.style.backgroundColor = entry.taken == 1 ? "#d4f7d4" : "white";
        box.style.textDecoration = entry.taken == 1 ? "line-through" : "none";

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

// Beim Laden der Seite starten
document.addEventListener("DOMContentLoaded", generiereDatumsliste);











// Reminder‑Checker: alle 60 Sekunden
setInterval(async () => {
  try {
    const res = await fetch("api/reminder_check.php");
    const data = await res.json();

    if (data.status === "success" && data.reminder.length > 0) {
      data.reminder.forEach((r) => {
        if (r.zu_spaet) {
          alert(`❗️Du hast das Medikament "${r.name}" zu spät eingenommen.`);
        } else {
          alert(`💊 Zeit für: "${r.name}"! Bitte jetzt einnehmen.`);
        }
      });
    }

  } catch (err) {
    console.error("Fehler beim Reminder-Abruf:", err);
  }
}, 60000); // alle 60 Sekunden































document.getElementById("logout-button").addEventListener("click", async () => {
  try {
    const res = await fetch("api/logout.php");
    const data = await res.json();

    if (data.status === "success") {
      window.location.href = "login.html"; // zurück zur Login-Seite
    }
  } catch (err) {
    console.error("Fehler beim Logout:", err);
  }
});