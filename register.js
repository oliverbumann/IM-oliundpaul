console.log("Hello from Register JS!");

document
  .getElementById("registerForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();

    const username = document.querySelector("#username").value.trim();
    const email = document.querySelector("#email").value.trim();
    const password = document.querySelector("#password").value;

    const formData = new FormData();
    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);

    try {
      const res = await fetch("api/register.php", {
        method: "POST",
        body: formData,
      });

      const reply = await res.json(); // ✅ JSON-Antwort verarbeiten

      console.log("Antwort vom Server:", reply);
      alert(reply.message); // Zeigt z. B. „Benutzer erfolgreich registriert.“

      if (reply.status === "success") {
        window.location.href = "login.html";
      }

    } catch (err) {
      console.error("Fehler beim Senden:", err);
    }
  });
