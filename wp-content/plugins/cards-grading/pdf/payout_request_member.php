<?php
$payout = $data["payout"];
?>

<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                margin: 0cm;
            }
            body {
                margin: 0cm;
                padding: 0cm;
                font-size: 8pt; /* Set global font size */
            }
        </style>        
    </head>
    <body>
        <div class="container-fluid m-0 p-0">
            <div class="row mb-3 border-bottom p-3">
                <div class="col-12">
                    <img src="https://5starcards.com/wp-content/uploads/2023/09/5-star-cards-logo.png" width="150px;" alt="5 Star Cards">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <H1>Payment Request</H1>
                </div>
                <div class="col-12">
                    <table class="table table-bordered tabled-striped">
                        <thead>
                            <tr>
                                <th class="text-start">Payment ID</th>
                                <th class="text-center">Cards</th>
                                <th class="text-start">Request Date</th>
                                <th class="text-end">Amount Requested</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">Payment ID</td>
                                <td class="text-center">Cards</td>
                                <td class="text-start">Request Date</td>
                                <td class="text-end">Amount Requested</td>
                                <td class="text-end">Status</td>
                            </tr>
                            <tr>
                                <td class="text-start">Payment ID</td>
                                <td class="text-center">Cards</td>
                                <td class="text-start">Request Date</td>
                                <td class="text-end">Amount Requested</td>
                                <td class="text-end">Status</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>