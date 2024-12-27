<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sticky Header & Footer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Sticky Header */
    header {
      position: sticky;
      top: 0;
      z-index: 1020;
      background-color: #f8f9fa;
      padding: 1rem;
      border-bottom: 1px solid #dee2e6;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      max-height: 50px;
    }

    .menu {
      display: none;
    }

    @media (min-width: 768px) {
      .menu {
        display: flex;
        gap: 1rem;
      }
    }

    .buttons-box {
      display: flex;
      gap: 0.5rem;
    }

    /* Sticky Footer */
    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 1020;
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      transition: height 0.3s ease-in-out;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    footer.collapsed {
      height: 50px;
    }

    footer.expanded {
      height: 75vh;
    }

    footer .footer-content {
      overflow: hidden;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    footer .always-visible {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 100%;
      padding: 0.5rem;
      background-color: #e9ecef;
      border-bottom: 1px solid #dee2e6;
      gap: 0.5rem; /* Space between rows */
    }

    footer .row {
      margin: 0; /* Remove default margin */
    }

    footer .hidden-content {
      display: none;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100%;
      width: 100%;
    }

    footer.expanded .hidden-content {
      display: flex;
    }

    body {
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .container {
      flex: 1;
    }

    @media (min-width: 768px) {
      footer {
        display: none;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="https://via.placeholder.com/150" alt="Logo">
    </div>
    <nav class="menu">
      <a href="#" class="text-decoration-none">Home</a>
      <a href="#" class="text-decoration-none">About</a>
      <a href="#" class="text-decoration-none">Services</a>
      <a href="#" class="text-decoration-none">Contact</a>
    </nav>
    <div class="buttons-box">
      <button class="btn btn-primary">Login</button>
      <button class="btn btn-secondary">Sign Up</button>
    </div>
  </header>

  <div class="container mt-3">
    <div class="row">
      <div class="col-md-7 col-12 mb-3">
        <div class="p-3 border bg-light">Column 1</div>
      </div>
      <div class="col-md-5 d-none d-md-block mb-3">
        <div class="p-3 border bg-light">Column 2</div>
      </div>
    </div>
  </div>

  <footer class="collapsed">
    <div class="always-visible">
      <div class="row w-100 text-center">
        <div class="col-12">
            <button id="footerToggle" class="btn btn-primary">Toggle Footer</button>
        </div>
        <div class="col-6 border-end">
          <p>Row 1, Column 1</p>
        </div>
        <div class="col-6">
          <p>Row 1, Column 2</p>
        </div>
      </div>
      <div class="row w-100 text-center">
        <div class="col-6 border-end">
          <p>Row 2, Column 1</p>
        </div>
        <div class="col-6">
          <p>Row 2, Column 2</p>
        </div>
      </div>
    </div>
    <div class="hidden-content">
      <p>This is hidden content visible only when the footer is expanded.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const footer = document.querySelector('footer');
    const footerToggle = document.getElementById('footerToggle');

    footerToggle.addEventListener('click', () => {
      if (footer.classList.contains('collapsed')) {
        footer.classList.remove('collapsed');
        footer.classList.add('expanded');
      } else {
        footer.classList.remove('expanded');
        footer.classList.add('collapsed');
      }
    });
  </script>
</body>
</html>
