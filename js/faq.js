$(".faq-question").click(function () {
  $(this).parent().next(".answer-fqa").slideToggle();
});
console.log("js is connected");
document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector(".question-box form")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const questionInput = this.querySelector("input");
      const question = questionInput.value.trim();

      if (question === "") {
        alert("Please enter a question.");
        return;
      }

      const user_ID = 1;

      fetch("../php/submit_question_Faq.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `question=${encodeURIComponent(question)}&user_ID=${user_ID}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            alert("Your question has been submitted successfully!");
            questionInput.value = "";
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch((err) => {
          console.error(err);
          alert("Something went wrong.");
        });
    });
});
