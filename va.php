<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTeam</title>
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
        #progress {
            font-size: 5rem;
        }
        .progress-circle {
            position: relative;
            width: 150px;
            height: 150px;
            margin: auto;
        }
        .progress-circle svg {
            transform: rotate(-90deg);
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
            stroke-dasharray: 440; /* This should be 2 * π * radius */
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 0.3s;
        }
        #startButton, #folderId {
            display: block;
        }
        .hidden {
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#startButton').click(function() {
                const folderId = $('#folderId').val();

                $.ajax({
                    url: 'run_script.php',
                    type: 'POST',
                    data: { folder_id: folderId },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });

                // Hide input and button
                $('#startButton').addClass('hidden');
                $('#folderId').addClass('hidden');

                // Poll for progress
                const intervalId = setInterval(function() {
                    $.ajax({
                        type: 'GET',
                        url: 'status_script.php',
                        success: function(response) {
                            const progress = parseFloat(response.progress);
                            $('#progress').text(progress.toFixed(2) + "%");

                            // Update circular progress indicator
                            const circle = $('#progress-circle .front');
                            const dashOffset = 440 - (progress / 100 * 440);
                            circle.css('stroke-dashoffset', dashOffset);

                            if (progress >= 100) {
                                clearInterval(intervalId);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#progress').text('AJAX Error: ' + textStatus + ' - ' + errorThrown);
                            clearInterval(intervalId);
                        }
                    });
                }, 2000); // Poll every 2 seconds
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div id="progress-circle" class="progress-circle">
            <svg width="150" height="150">
                <circle class="behind" cx="75" cy="75" r="70"></circle>
                <circle class="front" cx="75" cy="75" r="70"></circle>
            </svg>
        </div>
        <p id="progress">Paid Out</p>
        <input type="text" id="folderId" class="form-control" placeholder="Enter Google Drive Folder ID" />
        <button id="startButton" class="btn form-control btn-primary mt-3">GO</button>
    </div>
</body>
</html>
