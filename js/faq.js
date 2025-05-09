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

closeNav();

function openNav() {
  $(".side-nav").animate({ left: 0 }, 500);

  $(".manue-i").addClass("fa-xmark");
  $(".manue-i").removeClass("fa-bars");
  $(".ul-nav li ").eq(0).animate({ top: 0 }, 500);
  $(".ul-nav li ").eq(1).animate({ top: 0 }, 600);
  $(".ul-nav li ").eq(2).animate({ top: 0 }, 700);
  $(".ul-nav li ").eq(3).animate({ top: 0 }, 800);
  $(".ul-nav li ").eq(4).animate({ top: 0 }, 900);
  $(".ul-nav li ").eq(5).animate({ top: 0 }, 1000);
  $(".ul-nav li ").eq(6).animate({ top: 0 }, 1100);
  $(".ul-nav li ").eq(7).animate({ top: 0 }, 1200);
  $(".ul-nav li ").eq(8).animate({ top: 0 }, 1300);
  $(".ul-nav li ").eq(9).animate({ top: 0 }, 1400);
  $(".ul-nav li ").eq(10).animate({ top: 0 }, 1500);
  $(".ul-nav li ").eq(11).animate({ top: 0 }, 1600);
}
function closeNav() {
  let navWidth = $(".rightSide").outerWidth();

  $(".side-nav").animate({ left: -navWidth }, 500);
  $(".manue-i").removeClass("fa-xmark");
  $(".manue-i").addClass("fa-bars");
  $(".ul-nav li ").animate({ top: 500 }, 500);
}
$(".manue-i").click(() => {
  if ($(".side-nav").css("left") == "0px") {
    closeNav();
  } else {
    openNav();
  }
});
