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
      display: flex;
      flex-direction: column;
    }

    .always-visible {
      padding: 0.5rem;
      background-color: #e9ecef;
      border-bottom: 1px solid #dee2e6;
    }

    .hidden-content {
      display: none;
      flex-grow: 1;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    footer.expanded .hidden-content {
      display: flex;
    }

    .pinned-visible {
      background-color: #e9ecef;
      border-top: 1px solid #dee2e6;
      padding: 0.5rem;
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
  <header class="bg-black">
    <div class="logo">
      <img src="https://5starcards.com/wp-content/uploads/2023/09/5-star-cards-logo.png" alt="Logo">
    </div>
    <nav class="menu text-white">
      <a href="#" class="text-decoration-none">Home</a>
      <a href="#" class="text-decoration-none">About</a>
      <a href="#" class="text-decoration-none">Services</a>
      <a href="#" class="text-decoration-none">Contact</a>
    </nav>
    <div class="buttons-box">
      <button class="btn btn-primary">Save & Exit</button>
    </div>
  </header>

  <div class="container mt-3">
    <div class="row">
      <div class="col-md-7 col-12 mb-3">
        <H2>Enter Items</H2>
        <p>Add items you want to submit to PSA for grading</p>

      </div>
      <div class="col-md-5 d-none d-md-block mb-3">
        <div class="p-3 border rounded bg-white">
            <H3>Summary</H3>
            <div class="row pt-3">
                <div class="col">
                    Item Type
                </div>
                <div class="col text-end">
                    Trading Cards
                </div>
            </div>
            <div class="row pt-3">
                <div class="col">
                    Submission Type
                </div>
                <div class="col text-end">
                    Grading
                </div>
            </div>
            <div class="row pt-3">
                <div class="col">
                    Service Level
                </div>
                <div class="col text-end">
                    Value (1980-Present)
                </div>
            </div>
            <div class="row pt-3">
                <div class="col">
                    Price
                </div>
                <div class="col text-end">
                    $19.99/item
                </div>
            </div>
            <div class="row pt-3">
                <div class="col">
                    Max Ins. Value
                </div>
                <div class="col text-end">
                    $500/item
                </div>
            </div>
            <div class="row pt-3">
                <div class="col">
                    Items x 1
                </div>
                <div class="col text-end">
                    $19.99
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="collapsed">
    <!-- 1st Row: Always Visible -->
    <div class="always-visible">
        <div class="d-flex justify-content-between p-0">
            <div class="">
                <strong>Summary <span>(0 item)</span></strong>
            </div>
            <button id="footerToggle" class="btn">
                <span id="toggleIcon" class="bi bi-caret-down-fill"></span>
            </button>                
        </div>
    </div>

    <!-- 2nd Row: Always Hidden -->
    <div class="hidden-content">
      <p>This is hidden content visible only when the footer is expanded.</p>
    </div>

    <!-- 3rd Row: Always Visible and Pinned -->
    <div class="pinned-visible">
        <div class="d-flex justify-content-between px-0 py-2">
            <button id="footerBack" class="btn btn-outline-dark">Back</button>
            <button id="footerContinue" class="btn btn-primary">Continue</button>
        </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const footer = document.querySelector('footer');
    const footerToggle = document.getElementById('footerToggle');
    const toggleIcon = document.getElementById('toggleIcon');

    footerToggle.addEventListener('click', () => {
      if (footer.classList.contains('collapsed')) {
        footer.classList.remove('collapsed');
        footer.classList.add('expanded');
        toggleIcon.classList.replace('bi-caret-down-fill', 'bi-caret-up-fill');
      } else {
        footer.classList.remove('expanded');
        footer.classList.add('collapsed');
        toggleIcon.classList.replace('bi-caret-up-fill', 'bi-caret-down-fill');
      }
    });
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
