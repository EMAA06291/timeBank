document.addEventListener("DOMContentLoaded", function () {
  fetch("../services_api.php")
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        const services = data.data;

        console.log("My Services:", services);

        const container = document.getElementById("servicesContainer");
        services.forEach(service => {
          const card = document.createElement("div");
          card.className = "service-card";
          card.innerHTML = `
            
            <h2>${service.Title}</h2>
            <p>${service.Description}</p>
            <div class="service-details">
              <span><i class="fa-regular fa-clock"></i> ${service.Hours} Hours</span>
              <span class="rating">
                ${generateStars(service.Rating)}
              </span>
              <span><i class="fa-solid fa-users"></i> ${service.Views}K</span>
            </div>
            <button class="btn manage-btn">Manage</button>
          `;
          container.appendChild(card);
        });
      } else {
        alert("No services found.");
      }
    })
    .catch(error => {
      console.error("Error fetching services:", error);
    });
});

function generateStars(rating) {
  let stars = '';
  for (let i = 0; i < rating; i++) {
    stars += `<i class="fa-solid fa-star"></i>`;
  }
  return stars;
}
