<?php
session_start();

function getInt($key, $default, $min, $max) {
    if (!isset($_POST[$key])) return $_SESSION[$key] ?? $default;
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
        'pw_count' => 'Anzahl Passwörter',
        'pw_length' => 'Passwortlänge',
        'pw_numbers' => 'Zahlen im Passwort',
        'pw_upper' => 'Großbuchstaben im Passwort',
        'pw_specials' => 'Sonderzeichen im Passwort',
        'special_select' => 'Sonderzeichen Auswahl',
        'warn_sum' => '⚠ Hinweis: Die Summe der gewählten Zeichenarten überschreitet die Passwortlänge.',
        'warn_length' => '⚠ Achtung: Eine Passwortlänge unter 8 gilt als unsicher.',
        'warn_special' => '⚠ Achtung: Passwörter ohne Sonderzeichen gelten als schwächer.'
    ],
    'en' => [
        'title' => '🔐 Password Generator',
        'generate' => 'Generate',
        'pw_count' => 'Number of Passwords',
        'pw_length' => 'Password Length',
        'pw_numbers' => 'Numbers in Password',
        'pw_upper' => 'Uppercase Letters in Password',
        'pw_specials' => 'Special Characters in Password',
        'special_select' => 'Special Character Selection',
        'warn_sum' => '⚠ Warning: Sum of selected character types exceeds password length.',
        'warn_length' => '⚠ Warning: Passwords shorter than 8 characters are considered insecure.',
        'warn_special' => '⚠ Warning: Passwords without special characters are considered weaker.'
    ],
    'fr' => [
        'title' => '🔐 Générateur de mots de passe',
        'generate' => 'Générer',
        'pw_count' => 'Nombre de mots de passe',
        'pw_length' => 'Longueur du mot de passe',
        'pw_numbers' => 'Chiffres dans le mot de passe',
        'pw_upper' => 'Lettres majuscules dans le mot de passe',
        'pw_specials' => 'Caractères spéciaux dans le mot de passe',
        'special_select' => 'Sélection des caractères spéciaux',
        'warn_sum' => '⚠ Attention : La somme des types de caractères dépasse la longueur du mot de passe.',
        'warn_length' => '⚠ Attention : Un mot de passe de moins de 8 caractères est considéré comme peu sûr.',
        'warn_special' => '⚠ Attention : Les mots de passe sans caractères spéciaux sont considérés comme plus faibles.'
    ],
    'es' => [
        'title' => '🔐 Generador de contraseñas',
        'generate' => 'Generar',
        'pw_count' => 'Cantidad de contraseñas',
        'pw_length' => 'Longitud de la contraseña',
        'pw_numbers' => 'Números en la contraseña',
        'pw_upper' => 'Letras mayúsculas en la contraseña',
        'pw_specials' => 'Caracteres especiales en la contraseña',
        'special_select' => 'Selección de caracteres especiales',
        'warn_sum' => '⚠ Advertencia: La suma de los tipos de caracteres excede la longitud de la contraseña.',
        'warn_length' => '⚠ Advertencia: Contraseñas con menos de 8 caracteres se consideran inseguras.',
        'warn_special' => '⚠ Advertencia: Contraseñas sin caracteres especiales son más débiles.'
    ],
    'tr' => [
        'title' => '🔐 Şifre Oluşturucu',
        'generate' => 'Oluştur',
        'pw_count' => 'Şifre sayısı',
        'pw_length' => 'Şifre uzunluğu',
        'pw_numbers' => 'Şifredeki rakamlar',
        'pw_upper' => 'Şifredeki büyük harfler',
        'pw_specials' => 'Şifredeki özel karakterler',
        'special_select' => 'Özel karakter seçimi',
        'warn_sum' => '⚠ Uyarı: Karakter türlerinin toplamı şifre uzunluğunu aşıyor.',
        'warn_length' => '⚠ Uyarı: 8 karakterden kısa şifreler güvenli değildir.',
        'warn_special' => '⚠ Uyarı: Özel karakter içermeyen şifreler daha zayıf kabul edilir.'

    ]
];

$t = $translations[$lang];
$passwords = [];
$all_specials = ['!' => '!', '?' => '?', '@' => '@', '$' => '$', '%' => '%', '#' => '#', '-' => '-', '_' => '_', '+' => '+'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $count        = getInt('count', 1, 1, 50);
    $length       = getInt('length', 16, 5, 30);
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
$upper   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['title'] ?></title>
    <link rel="icon" type="image/png" href="favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-copied {
            background-color: #d1e7dd !important;
            color: #0f5132 !important;
            border: 1px solid #0f5132;
        }
        .password-card {
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
            opacity: 0;
        }
        .password-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        code {
            color: inherit !important;
        }
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

                <?php if ($warn_sum): ?>
                    <div class="alert alert-warning text-dark"><?= $t['warn_sum'] ?></div>
                <?php endif; ?>
                <?php if ($warn_length): ?>
                    <div class="alert alert-warning text-dark"><?= $t['warn_length'] ?></div>
                <?php endif; ?>
                <?php if ($warn_special): ?>
                    <div class="alert alert-warning text-dark"><?= $t['warn_special'] ?></div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <?php
                        function slider($name, $label, $value, $min, $max) {
                            echo "<label class='form-label'>$label: <strong>$value</strong></label>";
                            echo "<input type='range' class='form-range' name='$name' min='$min' max='$max' value='$value' oninput='this.previousElementSibling.querySelector(\"strong\").innerText = this.value'>";
                        }

                        slider("count", $t['pw_count'], $count, 1, 50);
                        slider("length", $t['pw_length'], $length, 5, 30);
                        slider("num_numbers", $t['pw_numbers'], $num_numbers, 0, $length);
                        slider("num_upper", $t['pw_upper'], $num_upper, 0, $length);
                        slider("num_specials", $t['pw_specials'], $num_specials, 0, $length);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><?= $t['special_select'] ?>:</label><br>
                        <?php foreach ($all_specials as $char): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="special_<?= $char ?>" id="special_<?= $char ?>" value="1"
                                    <?= in_array($char, $enabled_specials) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="special_<?= $char ?>"><?= htmlspecialchars($char) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning mt-4"><?= $t['generate'] ?></button>
            </form>
        </div>
    </div>

    <?php if (!empty($passwords)): ?>
        <div class="row mt-4">
            <?php foreach ($passwords as $index => $pw): ?>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card bg-secondary text-light h-100 password-card" id="card_<?= $index ?>">
                        <div class="card-body d-flex justify-content-between align-items-center password-content" data-index="<?= $index ?>">
                            <code id="pw_<?= $index ?>"><?= htmlspecialchars($pw) ?></code>
                            <span class="text-success ms-2" id="check_<?= $index ?>" style="display:none;">✔</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

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
