<?php
// va.php

// Function to check if the Python script is running
function isScriptRunning() {
    $output = shell_exec("pgrep -f paidout.py");
    return !empty($output);
}

// Function to fetch items from for_paid_out table
function fetchPaidOutItems() {
    // MySQL configuration
    $host = 'localhost';
    $user = 'python';
    $password = 'YchangThird1!';
    $database = 'wordpress';

    $connection = new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Database connection failed')));
    }

    $query = "SELECT filename, description, final_price FROM for_paid_out";
    $result = $connection->query($query);

    $items = array();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    $connection->close();
    return $items;
}

$scriptRunning = isScriptRunning();
$items = [];

if (!$scriptRunning) {
    $items = fetchPaidOutItems();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Progress</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .container {
            text-align: center;
        }
        .progress-circle {
            position: relative;
            width: 200px;
            height: 200px;
            margin: auto;
        }
        .progress-circle svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }
        .progress-circle circle {
            fill: none;
            stroke-width: 15;
        }
        .progress-circle .behind {
            stroke: #e9ecef;
        }
        .progress-circle .front {
            stroke: #007bff;
            stroke-dasharray: 534;
            stroke-dashoffset: 534;
            transition: stroke-dashoffset 0.3s;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            margin: 0;
        }
        #startButton, #folderId {
            display: block;
        }
        .hidden {
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#startButton').click(function() {
                // Hide input and button immediately
                $('.controls').addClass('hidden');
                $(".progress-box").removeClass("hidden");

                const folderId = $('#folderId').val();

                $.ajax({
                    url: 'run_script.php',
                    type: 'POST',
                    data: { folder_id: folderId },
                    success: function(response) {
                        console.log("Script started successfully.");
                        location.reload(); // Reload the page to update the status
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });

            function createTable(items) {
                let table = '<div><h1>Pending Payout Items</h1>';
                table += '<table class="table table-bordered mt-4">';
                table += '<thead>';
                table += '<tr>';
                table += '<th>Filename</th>';
                table += '<th>Description</th>';
                table += '<th>Price</th>';
                table += '</tr>';
                table += '</thead>';
                table += '<tbody>';
                items.forEach(item => {
                    if (item.description !== 'Description') {
                        table += '<tr>';
                        table += '<td>' + item.filename + '</td>';
                        table += '<td>' + item.description + '</td>';
                        table += '<td>' + item.final_price + '</td>';
                        table += '</tr>';
                    }
                });
                table += '</tbody></table></div>';
                $('.container').append(table);
            }

            <?php if (!$scriptRunning) { ?>
                createTable(<?php echo json_encode($items); ?>);
            <?php } ?>
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="progress-box <?php if (!$scriptRunning) echo 'hidden'; ?>">
            <div id="progress-circle" class="progress-circle">
                <svg viewBox="0 0 200 200">
                    <circle class="behind" cx="100" cy="100" r="85"></circle>
                    <circle class="front" cx="100" cy="100" r="85"></circle>
                </svg>
                <p id="progress" class="progress-text">0%</p>
            </div>
            <div>Getting items from Excel files in the specified Google Drive folder</div>
        </div>
        <div class="progress-db hidden">
            <p class="progress-text">Hang in there!</p>
            Cross checking items in the database...
        </div>
        <div class="controls <?php if ($scriptRunning) echo 'hidden'; ?>">
            <h1 class="mb-4">Process Paid Outs</h1>
            <label>Google Drive Folder Link</label>
            <input type="text" id="folderId" class="form-control" placeholder="Enter Google Drive Folder Link" />
            <small>Please input the Google Drive's Folder Link before clicking on GET FILES</small>
            <button id="startButton" class="btn btn-primary mt-3 form-control">GET FILES</button>
        </div>
        <div class="still_running <?php if (!$scriptRunning) echo 'hidden'; ?> text-center">
            Script still running. Retry after a few minutes
            <div class="still_progress"></div>
        </div>
    </div>
</body>
</html>
