function hashPassword() {
    var passwordField = document.getElementById('uspass');
    var password = passwordField.value;
    var hashedPassword = CryptoJS.SHA256(password).toString();
    passwordField.value = hashedPassword;
    return true; // Permitir que el formulario se envíe
}
