# IM4-oliundpaul
 
Seniorenfreundliche Medikamenten-Erinnerungs-App

Zweck der Anwendung:

Diese Webanwendung wurde entwickelt, um älteren Menschen zu helfen, ihre Medikamente zuverlässig und selbstständig einzunehmen. Sie unterstützt dabei nicht nur die Erinnerung an die Einnahmezeiten, sondern bietet auch eine einfache Benutzerführung, die speziell auf die Bedürfnisse älterer Personen zugeschnitten ist.






Funktionsübersicht:

Registrierung & Login

    Nutzer:innen können sich über ein einfaches Formular registrieren.

    Beim Anmelden werden persönliche Daten (z. B. Name, E-Mail, Passwort) sicher in einer Datenbank gespeichert.

    Nach dem Login wird der Nutzer zur personalisierten Übersicht weitergeleitet.


Medikamentenverwaltung

Sobald ein Benutzer eingeloggt ist, stehen folgende Funktionen zur Verfügung:
Tägliche Medikamentenübersicht

    Die Startseite zeigt eine übersichtliche Liste aller Medikamente, aufgeschlüsselt nach Wochentagen.

    Der Nutzer kann durch Abhaken markieren, welche Medikamente bereits eingenommen wurden.


Neue Medikamente hinzufügen

Beim Hinzufügen eines Medikaments gibt der Benutzer folgende Informationen ein:

    Name des Medikaments

    Häufigkeit der Einnahme

    Uhrzeit der Einnahme

    Ob das Medikament zeitkritisch ist (muss innerhalb eines bestimmten Zeitraums eingenommen werden)


Erinnerungen für zeitkritische Medikamente

    Ist ein Medikament als zeitkritisch markiert, werden innerhalb des festgelegten Zeitfensters drei Reminder gesendet (per Pop-up).

    Wenn der Einnahmezeitraum überschritten wird, erscheint ein besonderes Pop-up mit einer Warnung, dass der Arzt kontaktiert werden sollte.

    Wichtig: Aufgrund der Server-Performance können Erinnerungen bis zu 5 Minuten verzögert angezeigt werden.


Medikamenten-Übersichtsseite

    In der Übersichtsliste werden alle gespeicherten Medikamente inkl. aller Angaben (Name, Zeit, Häufigkeit, Zeitkritikalität) angezeigt.

    Dort können Medikamente auch dauerhaft gelöscht werden.







Designprinzipien für ältere Menschen:

    Grosse, gut sichtbare Buttons: Alle interaktiven Elemente wie Buttons und Checkboxen sind überdurchschnittlich gross gestaltet, damit sie leicht erkannt und bedient werden können – auch bei eingeschränkter Sehfähigkeit oder motorischer Präzision.

    Intuitive Navigation: Die Benutzeroberfläche ist bewusst einfach gehalten. Nur relevante Informationen und Funktionen werden sichtbar gemacht, um Überforderung zu vermeiden.






Technische Hinweise:

    Backend: Speicherung der Nutzerdaten und Medikamenteninformationen erfolgt über eine Datenbank (z. B. MongoDB, MySQL oder Firebase – je nach Implementierung).

    Reminder-Logik: Reminder werden über eine serverseitige Logik mit Timern und Benachrichtigungen umgesetzt.

    Verzögerung bei Benachrichtigungen: Bitte beachten: Aufgrund der Latenzzeit des Servers können Erinnerungen bis zu 5 Minuten verzögert erscheinen.







Fazit:

Diese App bietet älteren Menschen ein praktisches und barrierefreies Werkzeug zur Medikamentenerinnerung. Sie verbindet Benutzerfreundlichkeit mit sinnvoller Funktionalität und trägt somit zur Selbstständigkeit und Gesundheitsvorsorge bei.