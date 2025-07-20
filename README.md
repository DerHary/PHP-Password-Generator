# 🔐 PHP Password Generator

A secure, customizable password generator built with PHP and Bootstrap.  
Fully local, no dependencies, multi-language support, and instant copy-to-clipboard functionality.

## ✨ Features

- 🔢 Adjustable number and length of passwords
- 🔠 Fine-tune number of digits, uppercase letters, and special characters
- ✅ Enable/disable specific special characters: `! ? @ $ % # - _ +`
- 🌍 Language selector: English, German, French, Spanish, Turkish (session-based)
- 📋 Click-to-copy passwords with animation and ✔️ visual feedback
- 📊 Each generated password is evaluated with a custom strength algorithm and displayed as a color-coded progress bar:
  - Strength calculation considers:
    - Password length
    - Character variation (uppercase, lowercase, digits, special characters)
    - Repetition and case switches
    - Numeric distribution (not too few, not too many)
  - Visual indicator:
    - 🔴 Red: Weak (< 50%)
    - 🟠 Orange: Medium (50–89%)
    - 🟡 Yellow: Strong (90–99%)
    - 🟢 Green: Very strong (100%)
  - The bar is shown at the bottom of each password card and animates into view
- ⚠ Real-time warnings for weak configurations:
  - Length < 8 characters
  - No special characters selected
  - Sum of types (digits, uppercase, specials) exceeds total length

![Screenshot](https://raw.githubusercontent.com/DerHary/PasswordGenerator/refs/heads/main/img/screenshot.jpg)

## 💡 Usage

1. Deploy on a PHP-enabled web server.
2. Open in your browser.
3. Adjust sliders, special character checkboxes, and language – then click **Generate**.
4. Click on any password to copy it to the clipboard with visual feedback.

## 🛠 Technologies Used

- PHP (no database, no tracking)
- HTML5 + Bootstrap 5.3
- Vanilla JavaScript

## 🧩 Why This Project?

- Fast local tool for secure password generation
- No backend, no internet required – works 100% locally
- Clean, responsive UI with language awareness and user feedback

## 📄 License

This project is licensed under the [MIT License](LICENSE).
