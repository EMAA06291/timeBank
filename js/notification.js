console.log(`notification.js is connected`);

let user_ID = null;

document.addEventListener(`DOMContentLoaded`, function () {
  fetch(`../php/get_session.php`)
    .then(res => res.json())
    .then(data => {
      if (data.logged_in) {
        user_ID = data.user_id;
        console.log(`User ID from session: ${user_ID}`);
        document.getElementById(`user_id`).textContent = user_ID;

        fetchNotifications(user_ID);
      } else {
        window.location.href = `login.html`;
      }
    })
    .catch(err => {
      console.error(`Session check error:`, err);
    });
});

function fetchNotifications(userId) {
  fetch(`../get_notifications.php`)
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data)) {
        data.forEach(notification => {
          displayNotification(notification);
        });
      } else {
        console.error(`Error fetching notifications: ${data.message}`);
      }
    })
    .catch(err => {
      console.error(`Fetch error:`, err);
    });
}

function displayNotification(notification) {
  const container = document.getElementById(`notifications-container`);

  if (!container) {
    console.error(`Notification container not found.`);
    return;
  }

  const notificationDiv = document.createElement(`div`);
  notificationDiv.className = `notification`;

  notificationDiv.innerHTML = `
    <div class="icon"><i class="fas fa-bell"></i></div>
    <div class="text">${notification.Message}</div>
    <div class="time">${formatDate(notification.Created_at)}</div>
  `;

  container.appendChild(notificationDiv);
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleString();
}
