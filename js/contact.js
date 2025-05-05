document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contactForm");
    const responseMessage = document.getElementById("responseMessage");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const message = document.getElementById("message").value.trim();

        fetch("../php/contact.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ name, email, message }),
        })
        .then((res) => {
            if (!res.ok) {
                throw new Error("Network response was not ok");
            }
            return res.json();
        })
        .then((data) => {
            if (data.status === "success") {
                responseMessage.style.color = "green";
                responseMessage.textContent = data.message;
                form.reset();
            } else {
                responseMessage.style.color = "red";
                responseMessage.textContent = data.message;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            responseMessage.style.color = "red";
            responseMessage.textContent = "Something went wrong!";
        });
    });
});

