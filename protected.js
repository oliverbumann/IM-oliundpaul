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