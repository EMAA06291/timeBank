document.addEventListener("DOMContentLoaded", function () {
  fetch("../learning_api.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch data");
      }
      return response.json();
    })
    .then((data) => {
      if (data.error) {
        console.error("❌ Error:", data.error);
        alert("❌ Access denied: Please log in first");
        return;
      }

      console.log("✅ Data fetched successfully", data);
      alert("✅ Learning data loaded successfully");

      // Optional: Display data in the page
      const container = document.getElementById("learning-container");
      if (container) {
        data.forEach((item) => {
          const div = document.createElement("div");
          div.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
            <hr/>
          `;
          container.appendChild(div);
        });
      }
    })
    .catch((error) => {
      console.error("❌ Fetch error:", error.message);
      alert("❌ An error occurred while loading data");
    });
});
