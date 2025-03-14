

function exportEventsCSV() {
    window.location.href = 'export_csv.php'; 
}

document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.sidebar ul li a');
    const contentSections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            navLinks.forEach(navLink => {
                navLink.classList.remove('active');
            });
            this.classList.add('active');
            const targetId = this.getAttribute('href');

    
            contentSections.forEach(section => {
                section.classList.remove('active');
            });

            document.querySelector(targetId).classList.add('active');
        });
    });
});


document.getElementsByTagName('Form').addEventListener('submit', function(event) {t
    event.preventDefault();

    // Prepare the form data
    var formData = new FormData(this);

  
    fetch('create_event.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(data => {
        if (data.success) {
           
            alert("Event created successfully!");
        } else {
           
            alert("Failed to create event. Please try again.");
        }
    })
    .catch(error => {
       
        alert("An error occurred: " + error);
    });
});

