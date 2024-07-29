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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            $.ajax({
                type: 'GET',
                url: 'status_script.php',
                success: function(response) {
                    const progress = parseFloat(response.progress);

                    $('.loading').addClass('hidden');

                    if (progress < 100) {
                        $('.still_running').removeClass('hidden');
                        $(".still_progress").html("<h1>" + progress.toFixed(2) + "%</h1>");
                        console.log("Script still running please wait");

                        const intervalId = setInterval(function() {
                            location.reload();
                        }, 5000); // Poll every 5 seconds

                    } else {
                        $('.controls').removeClass('hidden');
                        $(".progress-box").addClass("hidden");
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#progress').text('AJAX Error: ' + textStatus + ' - ' + errorThrown);
                    clearInterval(intervalId);
                }
            });

            $(document).on("click", ".ebayintegration-btn", function(){
                console.log( $(this).data() ); 
            });

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
                        // Hide progress box and show table with items
                        $(".progress-box").addClass("hidden");

                        $('body, html').css("display", "block");

                        createTable(response.items);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });

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
                            const dashOffset = 534 - (progress / 100 * 534);
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

            function createTable(items) {
                let table = '<div><H1>Pending Payout Items</H1>';
                    table += '<table class="table table-bordered mt-4">';
                    table += '<thead>';
                    table += '<tr>';
                    table += '<th>Filename</th>';
                    table += '<th>Description</th>';
                    table += '<th>Action</th>';
                    table += '</tr>';
                    table += '</thead>';
                    table += '<tbody>';
                items.forEach(item => {
                    if( item.description != 'Description' ){
                        table += '<tr><td>' + item.filename + '</td>';
                        table += '<td>' + item.description + '</td>';
                        table += '<td>';
                        table += '<button class="btn btn-primary ebayintegration-btn" data-action="paidOutQuick" data-id="' + item.id+ '">Paid Out Queue</button>';
                        table += '</td>';
                        table += '</tr>';
                    }
                });
                table += '</tbody></table></div>';
                $('.container').append(table);


            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="progress-box hidden">
            <div id="progress-circle" class="progress-circle">
                <svg viewBox="0 0 200 200">
                    <circle class="behind" cx="100" cy="100" r="85"></circle>
                    <circle class="front" cx="100" cy="100" r="85"></circle>
                </svg>
                <p id="progress" class="progress-text">0%</p>
            </div>
            <div>Getting items from excel files in the specified Google Drive folder</div>
        </div>
        <div class="controls hidden">
            <h1 class="mb-4">Process Paid Outs</h1>
            <label>Google Drive Folder ID</label>
            <input type="text" id="folderId" class="form-control" placeholder="Enter Google Drive Folder ID" />
            <small>Please input the Google Drive's Folder ID before clicking on GET FILES</small>
            <button id="startButton" class="btn btn-primary mt-3 form-control">GET FILES</button>
        </div>
        <div class="loading text-center">
            Loading please wait...
        </div>
        <div class="still_running hidden text-center">
            Script still running. Retry after a few minutes
            <div class="still_progress"></div>
        </div>
    </div>
</body>
</html>
