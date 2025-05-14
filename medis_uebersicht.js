console.log("Hello from medis_uebersicht.js");

// Daten laden
async function ladeMedikamente() {
  const container = document.getElementById("mediliste");
  container.innerHTML = "";

  try {
    const res = await fetch("api/medis_uebersicht.php");
    const data = await res.json();

    if (data.status === "success" && data.medikamente.length > 0) {
      data.medikamente.forEach(medi => {
        const box = document.createElement("div");
        box.className = "medi-box";

        box.innerHTML = `
          <strong>${medi.name}</strong><br>
          Start: ${medi.start_date} um ${medi.start_time.slice(0, 5)}<br>
          Häufigkeit: ${medi.frequency}<br>
          Zeitkritisch: ${medi.urgency}<br>
          <button class="delete-btn" data-id="${medi.id}">❌ Entfernen</button>
        `;

        container.appendChild(box);
      });

      // Event Listener nachträglich setzen
      document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
          const id = btn.dataset.id;
          if (confirm("Möchtest du dieses Medikament wirklich löschen?")) {
            await deleteMedi(id);
            ladeMedikamente(); // Seite neu laden
          }
        });
      });

    } else {
      container.textContent = "Keine zukünftigen Medikamente gefunden.";
    }

  } catch (err) {
    console.error("Fehler beim Laden der Medikamente:", err);
    container.textContent = "Fehler beim Laden.";
  }
}

async function deleteMedi(id) {
  try {
    const res = await fetch("api/medis_delete.php", {
      method: "POST",
      body: new URLSearchParams({ id })
    });

    const reply = await res.json();
    alert(reply.message);
  } catch (err) {
    console.error("Fehler beim Löschen:", err);
  }
}

// Laden starten
document.addEventListener("DOMContentLoaded", ladeMedikamente);
