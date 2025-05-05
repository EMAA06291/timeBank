document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const emailInput = form.querySelector("input[name='email']");
    const passwordInput = form.querySelector("input[name='password']");
    const submitButton = form.querySelector("button[type='submit']");
  
    if (!emailInput || !passwordInput || !submitButton) {
      console.error("Form fields or submit button not found");
      return;
    }
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
  
      let email = emailInput.value.trim();
      let password = passwordInput.value.trim();
  
      if (email === "" || password === "") {
        alert("Please fill in both fields.");
        return;
      }
  
      const data = {
        email: email,
        password: password
      };
  
      fetch("http://localhost/timebank/php/login_api.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          window.location.href = "dashboard.html";
        } else {
          alert(data.message); 
        }
      })
      .catch(error => {
        console.error("Login error:", error);
        alert("Something went wrong. Please try again.");
      });
    });
  });
  
  