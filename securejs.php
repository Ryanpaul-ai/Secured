<?php
session_start();

if (!isset($_SESSION["first_name"])) {
    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta
    http-equiv="Content-Security-Policy"
    content="default-src 'none'; style-src 'unsafe-inline'; script-src 'self'; base-uri 'none'; form-action 'self'"
  >
  <title>Secure XSS Training Lab</title>

  <style>
:root {
    --navy: #26374a;
    --blue: #648f8b;
    --purple: #648f8b;
    --text: #293746;
    --muted: #718096;
    --border: #e1e8ee;
    --surface: #ffffff;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    padding: 32px 18px 44px;
    font-family: "Segoe UI", Arial, sans-serif;
    color: var(--text);
    background: #f4f7fa;
}

.page-shell {
    width: 100%;
    max-width: 760px;
    margin: 0 auto;
}

.page-header {
    padding: 27px 30px;
    margin-bottom: 20px;
    color: #ffffff;
    border: 1px solid #33485c;
    border-radius: 16px;
    background: var(--navy);
    box-shadow: 0 5px 16px rgba(38, 55, 74, 0.08);
}

.eyebrow {
    margin: 0 0 8px;
    color: #c6d8d5;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
}

.page-title {
    margin: 0;
    color: #ffffff;
    font-size: clamp(27px, 4vw, 36px);
    letter-spacing: -0.5px;
    line-height: 1.25;
}

.subtitle {
    margin: 10px 0 0;
    color: #d6e0e8;
    line-height: 1.6;
}

.intro {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.intro .welcome-message,
.intro .warning {
    margin: 0;
}

.card {
    width: 100%;
    padding: 23px 24px;
    margin-bottom: 16px;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--surface);
    box-shadow: 0 4px 16px rgba(38, 55, 74, 0.045);
    transition: box-shadow 0.2s ease;
}

.welcome-message {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    padding: 15px 17px;
    border: 1px solid #d8e9e5;
    border-radius: 12px;
    background: #edf5f3;
    color: #365e59;
    line-height: 1.6;
}

.welcome-icon {
    display: grid;
    place-items: center;
    flex: 0 0 30px;
    height: 30px;
    border-radius: 50%;
    background: #d5ebeb;
    font-weight: 800;
}

.warning {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    padding: 14px 16px;
    margin: 0 0 22px;
    border: 1px solid #e5d9b5;
    border-radius: 12px;
    background: #fffaf0;
    color: #80652b;
    line-height: 1.6;
}

.card-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 20px;
}

.step-icon {
    display: grid;
    place-items: center;
    flex: 0 0 44px;
    height: 44px;
    border: 1px solid #dcebe7;
    border-radius: 12px;
    color: #456f6a;
    background: #edf5f3;
    font-size: 18px;
    font-weight: 700;
}

h2 {
    margin: 0 0 5px;
    color: var(--navy);
    font-size: 20px;
}

.card-description {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.5;
}

label {
    display: block;
    margin-bottom: 9px;
    color: #3e4d5c;
    font-weight: 600;
    font-size: 14px;
}

textarea {
    display: block;
    width: 100%;
    min-height: 112px;
    padding: 14px 15px;
    resize: vertical;
    border: 1px solid var(--border);
    border-radius: 10px;
    outline: none;
    background: #fbfdff;
    color: var(--text);
    font: inherit;
    line-height: 1.6;
    transition: border-color 0.2s, box-shadow 0.2s;
}

textarea::placeholder {
    color: #94a3b8;
}

textarea:focus {
    border-color: var(--teal);
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(90, 162, 168, 0.14);
}

button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 13px;
    padding: 11px 18px;
    border: 1px solid #557f7a;
    border-radius: 9px;
    background: var(--blue);
    color: #ffffff;
    font: inherit;
    font-weight: 600;
    cursor: pointer;
    box-shadow: none;
    transition: background 0.2s ease, transform 0.2s ease;
}

button:hover {
    background: #507b76;
  }

button:focus-visible {
    outline: 3px solid #b7d3cf;
    outline-offset: 3px;
}

button:active {
    transform: translateY(1px);
}

.output {
    min-height: 52px;
    margin-top: 16px;
    padding: 14px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: #f7f9fb;
    color: #526171;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
    line-height: 1.6;
}

.output:empty::before {
    content: "Your displayed message will appear here.";
    color: #98a5b3;
    font-style: italic;
}

.status-panel {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px 16px;
    border: 1px solid #d8e5e3;
    border-radius: 12px;
    background: #f0f6f5;
}

.status-dot {
    flex: 0 0 10px;
    height: 10px;
    margin-top: 6px;
    border-radius: 50%;
    background: #648f8b;
}

.success {
    margin: 0;
    color: #456f6a;
    font-weight: 600;
    line-height: 1.6;
}

.page-footer {
    padding: 8px 0 0;
    color: #8794a2;
    font-size: 12px;
    text-align: center;
}

@media (max-width: 600px) {
body {
  padding: 22px 13px 35px;
}
.page-header {
  padding: 23px 20px;
  border-radius: 15px;
}
.card {
  padding: 20px 17px;
  border-radius: 14px;
}
.card-heading {
  align-items: flex-start;
}
button {
  width: 100%;
}
}
  </style>
</head>

<body>
<main class="page-shell">
  <header class="page-header">
    <p class="eyebrow">Security practice environment</p>
    <h1 class="page-title">Secure XSS Training Lab</h1>
    <p class="subtitle">Learn how to handle user input safely with simple interactive examples.</p>
  </header>
  <div class="intro">
<p class="welcome-message">
    Welcome,
    <strong>
        <?php echo htmlspecialchars(
            $_SESSION["first_name"],
            ENT_QUOTES,
            "UTF-8"
        ); ?>
    </strong>!
    You are successfully logged in.
</p>

  <p class="warning">
    <strong>Safety note:</strong> This page treats all user input as text. It does not intentionally execute JavaScript.
  </p>
  </div>

  <section class="card">
    <div class="card-heading">
      <span class="step-icon" aria-hidden="true">01</span>
      <div>
        <h2>Safe Reflected-Style Output</h2>
        <p class="card-description">Enter a message and display it as plain text.</p>
      </div>
    </div>

    <form id="messageForm">
      <label for="messageInput">Enter a message:</label>
      <textarea id="messageInput" maxlength="500" placeholder="Type your message here..."></textarea>
      <button type="submit">Display Message <span aria-hidden="true">&#8594;</span></button>
    </form>

    <div id="messageOutput" class="output"></div>
  </section>

  <section class="card">
    <div class="card-heading">
      <span class="step-icon" aria-hidden="true">02</span>
      <div>
        <h2>Safe DOM Output</h2>
        <p class="card-description">Practice displaying text safely in the page.</p>
      </div>
    </div>

    <label for="domInput">Enter another message:</label>
    <textarea id="domInput" maxlength="500" placeholder="Type another message here..."></textarea>
    <button id="domButton" type="button">Display Safely <span aria-hidden="true">&#8594;</span></button>

    <div id="domOutput" class="output"></div>
  </section>

  <section class="card">
    <div class="card-heading">
      <span class="step-icon" aria-hidden="true">03</span>
      <div>
        <h2>Validation Result</h2>
        <p class="card-description">Check the current input-handling status.</p>
      </div>
    </div>

    <div class="status-panel">
      <span class="status-dot" aria-hidden="true"></span>
      <p id="status" class="success">No input has been submitted yet.</p>
    </div>
  </section>

  <footer class="page-footer">Secure XSS Training Lab · Safe input demonstration</footer>
</main>

  <script src="app.js" defer></script>
</body>
</html>