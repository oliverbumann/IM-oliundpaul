document.getElementById("medForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
const frequency = document.getElementById("frequency").value;
const startDateTime = document.getElementById("start_date").value;
const [startDate, startTime] = startDateTime.split("T");
const urgency = document.getElementById("urgency").value;

const formData = new FormData();
formData.append("name", name);
formData.append("frequency", frequency);
formData.append("start_date", startDate);
formData.append("start_time", startTime);
formData.append("urgency", urgency);

  try {
    const res = await fetch("api/medis_hinzufügen.php", {
      method: "POST",
      body: formData,
    });

    const reply = await res.json();
    alert(reply.message);

    if (reply.status === "success") {
      window.location.href = "protected.html"; // oder eine andere Seite
    }

  } catch (err) {
    console.error("Fehler beim Senden:", err);
  }
});
