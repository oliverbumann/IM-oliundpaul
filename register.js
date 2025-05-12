console.log("Hello from Register JS!");

document
  .getElementById("registerForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault(); // Formular‑Reload verhindern

    // ► Eingabewerte aus den Feldern holen
    const username = document.querySelector("#username").value.trim();
    const email = document.querySelector("#email").value.trim();
    const password = document.querySelector("#password").value;




    // FormData füllt PHPs $_POST automatisch
    const formData = new FormData();
    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);

    // Fetch
    try {
      const res = await fetch("api/register.php", {
        method: "POST",
        body: formData,
      });
      const reply = await res.text(); // register.php schickt nur Klartext zurück
      console.log("Antwort vom Server:\n" + reply);
    } catch (err) {
      console.error("Fehler beim Senden:", err);
    }
  });