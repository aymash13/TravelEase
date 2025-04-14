document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        if (!validateForm()) {
            event.preventDefault();
        }
    });

    function validateForm() {
        const password = passwordInput.value;
        let errorBox = document.querySelector(".password-error");
        if (!errorBox) {
            errorBox = document.createElement("div");
            errorBox.classList.add("password-error");
            passwordInput.parentNode.insertBefore(errorBox, passwordInput.nextSibling);
        }
        if (password.length < 8) {
            errorBox.textContent = "Password should be minimum 8 digit";
            errorBox.style.color = "#ff3d81";
            errorBox.style.marginTop = "5px";
            return false;
        } else {
            errorBox.textContent = "";
        }
        return true;
    }
});
