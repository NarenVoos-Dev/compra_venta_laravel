
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.getElementById("password-addon");
    const passwordIcon = document.getElementById("password-icon");

    passwordToggle.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.classList.remove("ri-eye-fill");
            passwordIcon.classList.add("ri-eye-off-fill");
        } else {
            passwordInput.type = "password";
            passwordIcon.classList.remove("ri-eye-off-fill");
            passwordIcon.classList.add("ri-eye-fill");
        }
    });
});
