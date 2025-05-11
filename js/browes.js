document.addEventListener("DOMContentLoaded", () => {
  const servicesContainer = document.getElementById("services-container");

  fetch("../browes_services.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((services) => {
      if (!Array.isArray(services)) {
        throw new Error("Invalid data format");
      }

      servicesContainer.innerHTML = ""; // Clear the container

      if (services.length === 0) {
        servicesContainer.innerHTML = "<p>No services found.</p>";
        return;
      }

      services.forEach((service) => {
        const card = document.createElement("div");
        card.className = "card m-2 p-3 border border-dark";
        card.innerHTML = `
          <h4>${service.name}</h4>
          <p><strong>Description:</strong> ${service.description}</p>
          <p><strong>Category:</strong> ${service.category}</p>
          <p><strong>Price:</strong> ${service.price}</p>
        `;
        servicesContainer.appendChild(card);
      });

      console.log("✅ Services loaded successfully");
    })
    .catch((error) => {
      console.error("❌ Error loading services:", error);
      if (servicesContainer)
        servicesContainer.innerHTML = `<p style="color:red;">Failed to load services.</p>`;
    });
});
