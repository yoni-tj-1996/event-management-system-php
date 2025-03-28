<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Management System</title>
    <link rel="stylesheet" href="homepage.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>
    <!-- Persistent Header -->
    <header>
      <nav>
        <ul>
          <li><a class="hover" href="Homepage.php">Home</a></li>

          <li>
            <a class="hover" href="loginphp.php"
              ><i class="fa-solid fa-user"></i> Login</a
            >
          </li>
          <li>
            <a class="hover" href="Interface_register.php" class="cta-button"
              >Sign Up</a
            >
          </li>
        </ul>
      </nav>
    </header>

    <!-- Dynamic Content Container -->
    <div id="content-container">
      <!-- Default Home Content -->
      <section id="hero">
        <div class="hero-content">
          <h1>EVENT MANAGEMENT SYSTEM</h1>
          <center>
            <p>
              <!-- Transform your events with our intuitive platform. From
              registration to feedback, we handle the details, so you can focus
              on creating unforgettable moments. -->
            </p>
          </center>
          <div class="hero-buttons">
            <a href="loginphp.php" class="btn btn-primary">Get Started</a>
          </div>
        </div>
        <!-- <div class="hero-image">
          <img src="./pexels-cottonbro-9694216.jpg" alt="Event Image" />
        </div> -->
      </section>

      <section id="features">
        <h2>Key Features</h2>
        <div class="features-grid">
          <div class="feature-item">
            <i class="fas fa-calendar-alt fa-3x"></i>
            <h3>Centralized Event Hub</h3>
            <p>Manage every detail in one place, from venues to speakers.</p>
          </div>
          <div class="feature-item">
            <i class="fas fa-user-plus fa-3x"></i>
            <h3>Simplified Registration</h3>
            <p>
              Effortless online registration with secure payment processing.
            </p>
          </div>
          <div class="feature-item">
            <i class="fas fa-envelope fa-3x"></i>
            <h3>Automated Communication</h3>
            <p>Keep attendees informed with timely reminders and updates.</p>
          </div>
          <div class="feature-item">
            <i class="fas fa-clock fa-3x"></i>
            <h3>Smart Scheduling</h3>
            <p>Create dynamic agendas with drag-and-drop session management.</p>
          </div>
        </div>
      </section>
    </div>

    <!-- Persistent Footer -->
    <footer>
      <div class="footer-content">
        <p>&copy; SWE 3rd year vision group.All rights reserved</p>
        <nav>
          <ul>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="term.html" target="_blank">Privacy Policy</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </nav>
      </div>
    </footer>

    <!-- JavaScript -->
    <script src="script.js"></script>
  </body>
</html>
