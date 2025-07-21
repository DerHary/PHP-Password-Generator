<?php

/*
#### PHP Password Generator ####
A secure, customizable password generator built with PHP and Bootstrap.

- Fast local tool, no backend
- Great for generating multiple strong passwords on the fly
- Clean UI, mobile-friendly, and language-aware

GitHub: https://github.com/DerHary/PHP-Password-Generator

#### PHP Password Generator ####
*/

session_start();

if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function getInt($key, $default, $min, $max) {
    if (!isset($_POST[$key])) { return $_SESSION[$key] ?? $default; }
    $val = intval($_POST[$key]);
    $val = max($min, min($max, $val));
    $_SESSION[$key] = $val;
    return $val;
}

if (isset($_POST['lang']) && in_array($_POST['lang'], ['en', 'de', 'fr', 'es', 'tr'])) {
    $_SESSION['lang'] = $_POST['lang'];
}
$lang = $_SESSION['lang'] ?? 'en';

$translations = [
    'de' => [
        'title' => '🔐 Passwort Generator',
        'generate' => 'Generieren',
        'reset' => 'Zurücksetzen',
        'pw_count' => 'Anzahl Passwörter',
        'pw_length' => 'Passwortlänge',
        'pw_numbers' => 'Zahlen im Passwort',
        'pw_upper' => 'Großbuchstaben im Passwort',
        'pw_specials' => 'Sonderzeichen im Passwort',
        'special_select' => 'Sonderzeichen Auswahl',
        'warn_sum' => '⚠ Hinweis: Die Summe der gewählten Zeichenarten überschreitet die Passwortlänge.',
        'warn_length' => '⚠ Achtung: Eine Passwortlänge unter 8 gilt als unsicher.',
        'warn_special' => '⚠ Achtung: Passwörter ohne Sonderzeichen gelten als schwächer.',
        'score' => 'Bewertung',
        'infotext' => 'Es wird empfohlen komplexe Passwörter zu verwenden.<br>Die Passwörter werden von der Seite grob auf Sicherheit bewertet und es sollten nur Passwörter mit einem Score von 100% verwendet werden.<br><a href="https://www.bsi.bund.de/EN/Themen/Verbraucherinnen-und-Verbraucher/Informationen-und-Empfehlungen/Cyber-Sicherheitsempfehlungen/Accountschutz/Sichere-Passwoerter-erstellen/sichere-passwoerter-erstellen_node.html" target="_blank">HIER</a> gibt es weitergehende Informationen.<br>Wenn du Änderungswünsche oder Bugs einmelden willst, klicke <a href="https://github.com/DerHary/PHP-Password-Generator/issues" target="_blank">HIER</a> (GitHub)'
    ],
    'en' => [
        'title' => '🔐 Password Generator',
        'generate' => 'Generate',
        'reset' => 'Reset',
        'pw_count' => 'Number of Passwords',
        'pw_length' => 'Password Length',
        'pw_numbers' => 'Numbers in Password',
        'pw_upper' => 'Uppercase Letters in Password',
        'pw_specials' => 'Special Characters in Password',
        'special_select' => 'Special Character Selection',
        'warn_sum' => '⚠ Warning: Sum of selected character types exceeds password length.',
        'warn_length' => '⚠ Warning: Passwords shorter than 8 characters are considered insecure.',
        'warn_special' => '⚠ Warning: Passwords without special characters are considered weaker.',
        'score' => 'Score',
        'infotext' => 'It is recommended to use complex passwords.<br>The passwords are roughly evaluated for security by the site, and only passwords with a score of 100% should be used.<br>Further information is available <a href="https://www.bsi.bund.de/EN/Themen/Verbraucherinnen-und-Verbraucher/Informationen-und-Empfehlungen/Cyber-Sicherheitsempfehlungen/Accountschutz/Sichere-Passwoerter-erstellen/sichere-passwoerter-erstellen_node.html" target="_blank">HERE</a>.<br>If you want to report bugs or request changes, click <a href="https://github.com/DerHary/PHP-Password-Generator/issues" target="_blank">HERE</a> (GitHub)'

    ],
    'fr' => [
        'title' => '🔐 Générateur de mots de passe',
        'generate' => 'Générer',
        'reset' => 'Réinitialiser',
        'pw_count' => 'Nombre de mots de passe',
        'pw_length' => 'Longueur du mot de passe',
        'pw_numbers' => 'Chiffres dans le mot de passe',
        'pw_upper' => 'Lettres majuscules dans le mot de passe',
        'pw_specials' => 'Caractères spéciaux dans le mot de passe',
        'special_select' => 'Sélection des caractères spéciaux',
        'warn_sum' => '⚠ Attention : La somme des types de caractères dépasse la longueur du mot de passe.',
        'warn_length' => '⚠ Attention : Un mot de passe de moins de 8 caractères est considéré comme peu sûr.',
        'warn_special' => '⚠ Attention : Les mots de passe sans caractères spéciaux sont considérés comme plus faibles.',
        'score' => 'Évaluation',
        'infotext' => 'Il est recommandé d’utiliser des mots de passe complexes.<br>Les mots de passe sont évalués de manière approximative par le site, et seuls ceux ayant un score de 100 % devraient être utilisés.<br>Plus d’informations <a href="https://www.bsi.bund.de/EN/Themen/Verbraucherinnen-und-Verbraucher/Informationen-und-Empfehlungen/Cyber-Sicherheitsempfehlungen/Accountschutz/Sichere-Passwoerter-erstellen/sichere-passwoerter-erstellen_node.html" target="_blank">ICI</a>.<br>Pour signaler un bug ou faire une suggestion, cliquez <a href="https://github.com/DerHary/PHP-Password-Generator/issues" target="_blank">ICI</a> (GitHub)'

    ],
    'es' => [
        'title' => '🔐 Generador de contraseñas',
        'generate' => 'Generar',
        'reset' => 'Restablecer',
        'pw_count' => 'Cantidad de contraseñas',
        'pw_length' => 'Longitud de la contraseña',
        'pw_numbers' => 'Números en la contraseña',
        'pw_upper' => 'Letras mayúsculas en la contraseña',
        'pw_specials' => 'Caracteres especiales en la contraseña',
        'special_select' => 'Selección de caracteres especiales',
        'warn_sum' => '⚠ Advertencia: La suma de los tipos de caracteres excede la longitud de la contraseña.',
        'warn_length' => '⚠ Advertencia: Contraseñas con menos de 8 caracteres se consideran inseguras.',
        'warn_special' => '⚠ Advertencia: Contraseñas sin caracteres especiales son más débiles.',
        'score' => 'Evaluación',
        'infotext' => 'Se recomienda utilizar contraseñas complejas.<br>Las contraseñas se evalúan aproximadamente en cuanto a seguridad en este sitio, y solo deben usarse aquellas con una puntuación del 100%.<br>Más información <a href="https://www.bsi.bund.de/EN/Themen/Verbraucherinnen-und-Verbraucher/Informationen-und-Empfehlungen/Cyber-Sicherheitsempfehlungen/Accountschutz/Sichere-Passwoerter-erstellen/sichere-passwoerter-erstellen_node.html" target="_blank">AQUÍ</a>.<br>Si deseas reportar errores o proponer cambios, haz clic <a href="https://github.com/DerHary/PHP-Password-Generator/issues" target="_blank">AQUÍ</a> (GitHub)'

    ],
    'tr' => [
        'title' => '🔐 Şifre Oluşturucu',
        'generate' => 'Oluştur',
        'reset' => 'Sıfırla',
        'pw_count' => 'Şifre sayısı',
        'pw_length' => 'Şifre uzunluğu',
        'pw_numbers' => 'Şifredeki rakamlar',
        'pw_upper' => 'Şifredeki büyük harfler',
        'pw_specials' => 'Şifredeki özel karakterler',
        'special_select' => 'Özel karakter seçimi',
        'warn_sum' => '⚠ Uyarı: Karakter türlerinin toplamı şifre uzunluğunu aşıyor.',
        'warn_length' => '⚠ Uyarı: 8 karakterden kısa şifreler güvenli değildir.',
        'warn_special' => '⚠ Uyarı: Özel karakter içermeyen şifreler daha zayıf kabul edilir.',
        'score' => 'Değerlendirme',
        'infotext' => 'Karmaşık şifreler kullanmanız önerilir.<br>Şifreler bu site tarafından güvenlik açısından kabaca değerlendirilir ve yalnızca %100 puan alan şifreler kullanılmalıdır.<br>Daha fazla bilgi için <a href="https://www.bsi.bund.de/EN/Themen/Verbraucherinnen-und-Verbraucher/Informationen-und-Empfehlungen/Cyber-Sicherheitsempfehlungen/Accountschutz/Sichere-Passwoerter-erstellen/sichere-passwoerter-erstellen_node.html" target="_blank">BURAYA</a> tıklayın.<br>Hata bildirmek veya öneride bulunmak istiyorsanız <a href="https://github.com/DerHary/PHP-Password-Generator/issues" target="_blank">BURAYA</a> tıklayın (GitHub)'

    ]
];

$t = $translations[$lang];
$passwords = [];
$all_specials = ['!' => '!', '?' => '?', '@' => '@', '$' => '$', '%' => '%', '#' => '#', '-' => '-', '_' => '_', '+' => '+'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $count        = getInt('count', 1, 1, 50);
    $length       = getInt('length', 16, 3, 50);
    $num_numbers  = getInt('num_numbers', 2, 0, $length);
    $num_upper    = getInt('num_upper', 2, 0, $length);
    $num_specials = getInt('num_specials', 2, 0, $length);

    $enabled_specials = [];
    foreach ($all_specials as $char) {
        if (isset($_POST["special_$char"])) {
            $enabled_specials[] = $char;
        }
    }
    $_SESSION['special_chars'] = $enabled_specials;
} else {
    $count        = $_SESSION['count']        ?? 21;
    $length       = $_SESSION['length']       ?? 16;
    $num_numbers  = $_SESSION['num_numbers']  ?? 2;
    $num_upper    = $_SESSION['num_upper']    ?? 2;
    $num_specials = $_SESSION['num_specials'] ?? 2;
    $enabled_specials = $_SESSION['special_chars'] ?? array_keys($all_specials);
}

$numbers = '0123456789';
$lower   = 'abcdefghijklmnopqrstuvwxyz';
$upper   = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
$special_pool = implode('', $enabled_specials ?? []);

$warn_sum     = ($num_numbers + $num_upper + $num_specials) > $length;
$warn_length  = $length < 8;
$warn_special = $num_specials == 0;

for ($j = 0; $j < $count; $j++) {
    $pw_chars = [];

    for ($i = 0; $i < $num_numbers; $i++) {
        $pw_chars[] = $numbers[random_int(0, strlen($numbers) - 1)];
    }
    for ($i = 0; $i < $num_upper; $i++) {
        $pw_chars[] = $upper[random_int(0, strlen($upper) - 1)];
    }
    for ($i = 0; $i < $num_specials && !empty($special_pool); $i++) {
        $pw_chars[] = $special_pool[random_int(0, strlen($special_pool) - 1)];
    }
    while (count($pw_chars) < $length) {
        $pw_chars[] = $lower[random_int(0, strlen($lower) - 1)];
    }

    shuffle($pw_chars);
    $passwords[] = implode('', $pw_chars);
}

/* Make a Check of the generated Passwrd */
function PassStrength($Password) {
    // length check
    $numCount = 0;
    // initial strength
    $W = min(strlen($Password) * 1.5, 30);
    if (is_numeric(substr($Password, 0, 1))) {
        $numCount += 1; // note first character is numeric
    }
    for ($i=1; $i<strlen($Password); $i++) {
        // if previous char was another one this is good, otherwise bad
        $t = substr($Password, $i, 1); // this
        $p = substr($Password, $i-1, 1); // previous
        if ($t != $p) { $W = $W + 6; } else { $W = $W - 1; }
        // check, if previous char was other case the current (good)
        $upper =  ($t == strtoupper($t)); $lower =  ($t == strtolower($t)); $pupper = ($p == strtoupper($p)); $plower = ($p == strtolower($p));
        // good if previous case is different than current
        if ($upper != $pupper || $lower != $plower) { $W = $W + 3; }
        // check if value is used multiple times - bad
        $occurences = explode($t, $Password); if (count($occurences) > 3) { $W = $W - 1; }
        // count number of numeric characters
        if (is_numeric($t)) { $numCount = $numCount + 2; }
    }
    // extra points if number of numeric characters is between 20 and 70 percent
    if ($numCount > strlen($Password) * 0.2 && $numCount < strlen($Password) * 0.7) { $W = $W + 5; }
    // not good if password is more than 50% numbers
    if ($numCount > strlen($Password) * 0.5) { $W = $W - 5; }
    // other checks
    if (!preg_match('/[A-Z]/', $Password)) { $W -= 20; } // no upper letters
    if (!preg_match('/[a-z]/', $Password)) { $W -= 20; } // no small letters
    if (!preg_match('/[0-9]/', $Password)) { $W -= 20; } // no digits
    if (!preg_match('/[\W_]/', $Password)) { $W -= 40; } // no special chars
    // no negative results, also no zero value (must be 1-100)
    if ($W < 0) { $W = 1; } elseif ($W > 100) { $W = 100; }
    // return rounded result
    return round($W);
}

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
<meta charset="UTF-8">
<title><?= $t['title'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include_once"img/base64_favicon.txt"; ?>
<style>
.card-copied { background-color: #d1e7dd !important; color: #0f5132 !important; border: 1px solid #0f5132; }
.password-card {
  cursor: pointer;
  opacity: 0;
  filter: blur(10px);
  transform: scale(0.8) rotateY(45deg) translateY(40px);
  transition:
    opacity 0.5s ease-out,
    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1),
    filter 0.6s ease-out;
  transform-origin: center top;
}

.password-card.visible {
  opacity: 1;
  filter: blur(0);
  transform: scale(1) rotateY(0deg) translateY(0);
}
code { color: inherit !important; }
.btn-wrap { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.secbarbg-red       { background-color:#FF0000; }
.secbarbg-orange    { background-color: #fd7e14; }
.secbarbg-yellow    { background-color: #FFF500; }
.secbarbg-green     { background-color: #22FF00; }
</style>
</head>
<body class="bg-dark text-light">
<div class="container py-4">
<div class="card bg-secondary text-light">
<div class="card-body">
<form method="POST">
<div class="d-flex justify-content-between align-items-start">
<h5 class="card-title mb-3"><?= $t['title'] ?></h5>
<select name="lang" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
<option value="de" <?= $lang === 'de' ? 'selected' : '' ?>>Deutsch</option>
<option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
<option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>Français</option>
<option value="es" <?= $lang === 'es' ? 'selected' : '' ?>>Español</option>
<option value="tr" <?= $lang === 'tr' ? 'selected' : '' ?>>Türkçe</option>
</select>
</div>
<?php if ($warn_sum): ?><div class="alert alert-warning text-dark"><?= $t['warn_sum'] ?></div><?php endif; ?>
<?php if ($warn_length): ?><div class="alert alert-warning text-dark"><?= $t['warn_length'] ?></div><?php endif; ?>
<?php if ($warn_special): ?><div class="alert alert-warning text-dark"><?= $t['warn_special'] ?></div><?php endif; ?>
<div class="row">
<div class="col-md-6">
<?php
function slider($name, $label, $value, $min, $max) {
  echo "<label class='form-label'>$label: <strong>$value</strong></label>";
  echo "<input type='range' class='form-range' name='$name' min='$min' max='$max' value='$value' oninput='this.previousElementSibling.querySelector(\"strong\").innerText = this.value'>";
}
slider("count", $t['pw_count'], $count, 1, 50);
slider("length", $t['pw_length'], $length, 3, 50);
slider("num_numbers", $t['pw_numbers'], $num_numbers, 0, 15);
slider("num_upper", $t['pw_upper'], $num_upper, 0, 15);
slider("num_specials", $t['pw_specials'], $num_specials, 0, 15);
?>
</div>
<div class="col-md-6">
<label class="form-label"><?= $t['special_select'] ?>:</label><br>
<?php foreach ($all_specials as $char): ?>
<div class="form-check form-check-inline">
<input class="form-check-input" type="checkbox" name="special_<?= $char ?>" id="special_<?= $char ?>" value="1" <?= in_array($char, $enabled_specials) ? 'checked' : '' ?>>
<label class="form-check-label" for="special_<?= $char ?>"><?= htmlspecialchars($char) ?></label>
</div>
<?php endforeach; ?>
<div class="card bg-info text-dark mt-3">
  <div class="card-body p-2">
    <small><?= $t['infotext'] ?></small>
  </div>
</div>
</div>
</div>
<div class="btn-wrap mt-4">
<button type="submit" class="btn btn-warning"><?= $t['generate'] ?></button>
<button type="submit" name="reset" value="1" class="btn btn-outline-light"><?= $t['reset'] ?></button>
</div>
</form>
</div>
</div>
<?php if (!empty($passwords)): ?>
<div class="row mt-4">
<?php foreach ($passwords as $index => $pw): ?>
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card bg-secondary text-light h-100 password-card" id="card_<?= $index ?>">
            <div class="card-body d-flex justify-content-between align-items-center password-content" data-index="<?= $index ?>">
            <span class="position-absolute top-0 end-0 p-1 small text-white-50" title="<?= $t['score'] ?>"><?= PassStrength($pw) ?>%</span>
            <code id="pw_<?= $index ?>" style="white-space: nowrap;"><?= htmlspecialchars($pw) ?></code>
            <?php $percent = PassStrength($pw); $barClass = 'secbarbg-red'; if ($percent >= 100) { $barClass = 'secbarbg-green'; } elseif ($percent >= 90) { $barClass = 'secbarbg-yellow'; } elseif ($percent >= 50) { $barClass = 'secbarbg-orange'; } elseif ($percent >= 0) { $barClass = 'secbarbg-red'; } else { $barClass = 'secbarbg-red'; } ?>
            <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 3px;">
                <div class="progress-bar <?= $barClass ?>" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="text-success ms-2" id="check_<?= $index ?>" style="display:none;">✔</span>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
<footer class="text-center text-white mt-5">
  <div class="container py-3 border-top border-light">
    PHP Password Generator @Github <a href="https://github.com/DerHary/PHP-Password-Generator/" target="_blank" class="text-warning text-decoration-none">DerHary</a>
  </div>
</footer>


<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.password-card').forEach((el, i) => {
    setTimeout(() => el.classList.add('visible'), i * 60);
  });
  document.querySelectorAll('.password-content').forEach(el => {
    el.addEventListener('click', () => {
      const index = el.dataset.index;
      const pwText = document.getElementById('pw_' + index).innerText;
      const card = document.getElementById('card_' + index);
      const check = document.getElementById('check_' + index);
      navigator.clipboard.writeText(pwText).then(() => {
        card.classList.add('card-copied');
        check.style.display = 'inline';
      });
    });
  });
});
</script>
</body>
</html>
