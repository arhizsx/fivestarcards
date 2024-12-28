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

    @media (min-width: 992px) {
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

    @media (min-width: 992px) {
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
      <a href="#" class="text-decoration-none">Grader</a>
      <a href="#" class="text-decoration-none">Service Level</a>
      <a href="#" class="text-decoration-none">Item Entry</a>
      <a href="#" class="text-decoration-none">Shipping</a>
      <a href="#" class="text-decoration-none">Confirmation</a>
    </nav>
    <div class="buttons-box">
      <button class="btn btn-primary">Save & Exit</button>
    </div>
  </header>

  <div class="container mt-3">
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-12 mb-3">

            <div class="header_box">
                <H2 class="header_title">Enter Items</H2>
                <p class="header_subtitle">Add items you want to submit to PSA for grading</p>
            </div>

            <div class="upper_box mt-5">
                <div class="add_item_box">
                    <form class="form">
                        <div class="row">
                            <div class="col">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control">
                            </div>
                            <div class="col">
                                <label>Year</label>
                                <input type="number" name="year" class="form-control">
                            </div>
                            <div class="col">
                                <label>Brand</label>
                                <input type="text" name="brand" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label>Player / Subject</label>
                                <input type="text" name="player_subject" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label>Card Number</label>
                                <input type="number" name="card_number" class="form-control">
                            </div>
                            <div class="col">
                                <label>Attribute / SN</label>
                                <input type="number" name="attribute" class="form-control">
                            </div>
                            <div class="col">
                                <label>Declared Value</label>
                                <input type="number" name="dv" class="form-control">
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="middle_box mt-5">

                <div class="card">
                    <div class="card-header">
                        Items
                    </div>
                    <div class="card-body">
                    </div>
                </div>

            </div>

            <div class="lower_box">


            </div>
        </div>
        <div class="col-xl-4 col-lg-4 d-none d-lg-block mb-3">
            <div class="card">
                <div class="card-body" style="font-size: .85rem;">
                    <H3>Summary</H3>
                    <div class="row py-3">
                        <div class="col">
                            Item Type
                        </div>
                        <div class="col text-end">
                            Trading Cards
                        </div>
                    </div>
                    <div class="row py-3">
                        <div class="col">
                            Submission Type
                        </div>
                        <div class="col text-end">
                            Grading
                        </div>
                    </div>
                    <div class="row py-3">
                        <div class="col">
                            Service Level
                        </div>
                        <div class="col text-end">
                            Value (1980-Present)
                        </div>
                    </div>
                    <div class="row py-3">
                        <div class="col">
                            Price
                        </div>
                        <div class="col text-end">
                            $19.99/item
                        </div>
                    </div>
                    <div class="row py-3">
                        <div class="col">
                            Max Ins. Value
                        </div>
                        <div class="col text-end">
                            $500/item
                        </div>
                    </div>
                    <div class="row py-3">
                        <div class="col">
                            Items x 1
                        </div>
                        <div class="col text-end">
                            $19.99
                        </div>
                    </div>
                </div>
                <div class="card-footer p-4">
                    <button class="btn btn-primary form-control py-2">
                        Proceed to Checkout
                    </button>
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
