document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    if (!form) {
      console.error("Form not found");
      return;
    }
  
    const nameInput = form.querySelector("input[name='Name']");
    const emailInput = form.querySelector("input[name='email']");
    const passwordInput = form.querySelector("input[name='password']");
    const confirmPasswordInput = form.querySelector("input[name='Confirm_Password']");
    const submitButton = form.querySelector("button[type='submit']");
  
    console.log(nameInput, emailInput, passwordInput, confirmPasswordInput, submitButton);
  
    if (!nameInput || !emailInput || !passwordInput || !confirmPasswordInput || !submitButton) {
      console.error("Form fields or submit button not found");
      return;
    }
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
  
      const name = nameInput.value.trim();
      const email = emailInput.value.trim();
      const password = passwordInput.value.trim();
      const confirmPassword = confirmPasswordInput.value.trim();
  
      if (!name || !email || !password || !confirmPassword) {
        alert("Please fill in all fields.");
        return;
      }
  
      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }
  
      const data = new URLSearchParams();
      data.append("Name", name);
      data.append("email", email);
      data.append("password", password);
      data.append("confirm_password", confirmPassword);
  
      fetch("http://localhost/timebank/php/signup-api.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: data
      })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
          if (data.success) {
            window.location.href = "login.html";
          }
        })
        .catch(error => {
          console.error("Signup error:", error);
          alert("Something went wrong. Please try again.");
        });
    });
  });
  
  