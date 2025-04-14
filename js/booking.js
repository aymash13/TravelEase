document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("bookingForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let phoneInput = document.getElementById("phone");
        let phone = phoneInput.value.trim();
        let successMessage = document.getElementById("successMessage");

        
        if (!/^\d{10}$/.test(phone)) {
            phoneInput.setCustomValidity("Phone number must be exactly 10 digits.");
            phoneInput.reportValidity();
            return;
        } else {
            phoneInput.setCustomValidity("");
        }

        
        if (!successMessage) {
            successMessage = document.createElement("p");
            successMessage.id = "successMessage";
            successMessage.style.color = "green";
            document.getElementById("bookingForm").appendChild(successMessage);
        }
        successMessage.textContent = "Form is submitted successfully!";

        alert("Form is submitted successfully!");
        this.reset();
    });

    document.getElementById("phone").addEventListener("input", function () {
        this.setCustomValidity("");
    });
});
