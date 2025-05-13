console.log("Hello from Login JS!");



// document.getElementById("loginForm").addEventListener("submit", async (e) => {
//   e.preventDefault(); // Formular‑Reload verhindern
document
  .getElementById("loginForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault(); // Formular‑Reload verhindern

    // ► Eingabewerte aus den Feldern holen
    const loginInfo = document.querySelector("#user-email").value.trim();
    const password = document.querySelector("#password").value;


    console.log("LoginInfo ist:", loginInfo);
    console.log("Password ist:", password);



    // FormData füllt PHPs $_POST automatisch
    const formData = new FormData();
    formData.append("loginInfo", loginInfo);
    formData.append("password", password);



    // Fetch
    try {
      const res = await fetch("api/login.php", {
        method: "POST",
        body: formData,
      });
      const reply = await res.text(); // register.php schickt nur Klartext zurück
      console.log("Antwort vom Server:\n" + reply);
      alert(reply);


      if (reply === "Login erfolgreich!") {
        // Redirect to the protected page
        window.location.href = "protected.html";
      }


    } catch (err) {
      console.error("Fehler beim Senden:", err);
    }

  });