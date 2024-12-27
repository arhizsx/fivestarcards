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

    body {
      margin-bottom: 50px; /* Ensure content does not overlap with footer */
    }
  </style>
</head>
<body>
  <header class="text-center">
    <h1>Sticky Header</h1>
  </header>

  <div class="container mt-3">
    <div class="row">
      <div class="col-md-6 col-12 mb-3">
        <div class="p-3 border bg-light">Column 1</div>
      </div>
      <div class="col-md-6 d-none d-md-block mb-3">
        <div class="p-3 border bg-light">Column 2</div>
      </div>
    </div>
  </div>

  <footer class="collapsed">
    <div class="footer-content">
      <button id="footerToggle" class="btn btn-primary">Toggle Footer</button>
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
