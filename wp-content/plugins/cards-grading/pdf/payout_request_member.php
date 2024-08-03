
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
                font-size: 9pt; /* Set global font size */
            }

            th, td {
                padding: 2px; /* Reduced padding */
            }

        </style>        
    </head>
    <body>
        <div class="container-fluid m-0 p-0">


            <div class="row mb-3 border-bottom">
                <div class="col-12  p-3">
                    <img src="https://5starcards.com/wp-content/uploads/2023/09/5-star-cards-logo.png" width="150px;" alt="5 Star Cards">
                </div>
            </div>
            <div class="row">
                <div class="col-12  p-3">
                    <H5>Payment Request</H5>
                    <table class="table table-bordered tabled-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-start" width="50%">Item</th>
                                <th class="text-end">Price Sold</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Fees</th>
                                <th class="text-end">Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($data["cards"] as $card){

                                $card_data = json_decode($card->data, true);
                            ?>  
                            <tr>
                                <td>
                                <?php
                                print_r($card_data["Item"]["Title"]);
                                ?>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <?php    
                            }                        
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>