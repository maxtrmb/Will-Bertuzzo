<?php
// form-handler.php

// Zieladresse für Kontaktformular-Anfragen
$recipient = 'maximilian.von.truembach@web.de';

// Erwartete Felder
$fields = [
    'subject',
    'vorname',
    'nachname',
    'email',
    'telefon',
    'leistung',
    'nachricht',
];

// Eingaben aus POST sicher lesen
$data = [];
foreach ($fields as $field) {
    $data[$field] = isset($_POST[$field]) ? trim($_POST[$field]) : '';
}

// Minimum validieren
$errors = [];
if ($data['vorname'] === '') {
    $errors[] = 'Bitte geben Sie Ihren Vornamen an.';
}
if ($data['nachname'] === '') {
    $errors[] = 'Bitte geben Sie Ihren Nachnamen an.';
}
if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse an.';
}
if ($data['leistung'] === '') {
    $errors[] = 'Bitte wählen Sie eine gewünschte Leistung aus.';
}
if ($data['nachricht'] === '') {
    $errors[] = 'Bitte schreiben Sie uns eine Nachricht.';
}

if (!empty($errors)) {
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><title>Fehler</title></head><body>';
    echo '<h1>Kontaktformular: Fehler</h1>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul>';
    echo '<p><a href="index.html">Zurück zum Formular</a></p>';
    echo '</body></html>';
    exit;
}

// E-Mail inhalt erstellen
$emailSubject = 'Kontaktanfrage von der Website';
$emailBody  = "Eine neue Kontaktanfrage wurde gesendet:\n\n";
$emailBody .= "Vorname: " . $data['vorname'] . "\n";
$emailBody .= "Nachname: " . $data['nachname'] . "\n";
$emailBody .= "E-Mail: " . $data['email'] . "\n";
$emailBody .= "Telefon: " . $data['telefon'] . "\n";
$emailBody .= "Gewünschte Leistung: " . $data['leistung'] . "\n\n";
$emailBody .= "Nachricht:\n" . $data['nachricht'] . "\n";

$headers  = 'From: no-reply@will-bertuzzo.de' . "\r\n";
$headers .= 'Reply-To: ' . $data['email'] . "\r\n";
$headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";

$sent = mail($recipient, $emailSubject, $emailBody, $headers);

if ($sent) {
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><title>Danke</title></head><body>';
    echo '<h1>Vielen Dank!</h1>';
    echo '<p>Ihre Nachricht wurde erfolgreich versendet. Wir melden uns bald bei Ihnen.</p>';
    echo '<p><a href="index.html">Zurück zur Startseite</a></p>';
    echo '</body></html>';
    exit;
}

// Fehler beim Versand
echo '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><title>Fehler</title></head><body>';
echo '<h1>Fehler beim Versand</h1>';
echo '<p>Die Nachricht konnte leider nicht versendet werden. Bitte versuchen Sie es später erneut.</p>';
echo '<p><a href="index.html">Zurück zum Formular</a></p>';
echo '</body></html>';
exit;
