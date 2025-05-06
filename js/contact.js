document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (name === '' || email === '' || message === '') {
        alert('All fields are required!');
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('message', message);

    fetch('../php/Contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(data => {
        if (data.status === 'success') {
            alert('Your message has been sent successfully!');
            document.getElementById('contactForm').reset(); 
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error); 
        alert('There was an error with the request.');
    });
});