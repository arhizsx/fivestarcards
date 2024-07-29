<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Progress</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#startButton').click(function() {
                const folderId = $('#folderId').val();

                $.ajax({
                    url: 'run_script.php',
                    type: 'POST',
                    data: { folder_id: folderId },
                    dataType: 'json',
                    success: function(response) {
                        console.log( response )
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });


                // Poll for progress
                const intervalId = setInterval(function() {
                    $.ajax({
                        type: 'GET',
                        url: 'run_script.php',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
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
    <h1>Process Progress</h1>
    <input type="text" id="folderId" placeholder="Enter Google Drive Folder ID" />
    <button id="startButton">Start Process</button>
    <p id="progress">Progress will be displayed here.</p>
</body>
</html>
