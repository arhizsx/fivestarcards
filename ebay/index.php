<!doctype html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style>
            .boxsizingBorder {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                        box-sizing: border-box;
                width: 100%;
            }           
            label {
                font-size: 12px;
            } 
        </style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    </head>
    <body>
        <div class="container">
        <H1>5 Star Cards eBay Integration</H1>
        <hr>
        <div>
            <a href="https://auth.ebay.com/oauth2/authorize?client_id=Fernando-5starcar-PRD-a81fdd189-a762dfc7&response_type=code&redirect_uri=Fernando_Salvad-Fernando-5starc-qxmeny&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/sell.reputation https://api.ebay.com/oauth/api_scope/sell.reputation.readonly https://api.ebay.com/oauth/api_scope/commerce.notification.subscription https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly https://api.ebay.com/oauth/api_scope/sell.stores https://api.ebay.com/oauth/api_scope/sell.stores.readonly">
                Connect To Ebay
            </a>
        </div>

        <?php 
        $auth_code = urldecode($_GET["code"]);

        $client_id = "Fernando-5starcar-PRD-a81fdd189-a762dfc7";
        $client_secret = "PRD-81fdd189cbf1-f472-4131-93a2-1990";
        $redirect_uri  = "Fernando_Salvad-Fernando-5starc-qxmeny";
        $apiURL = "https://api.ebay.com/identity/v1/oauth2/token";
        $grant_type = "authorization_code";
        
        if(isset($_GET["code"]) === false){ ?>
        <div  style="display: none;">
            <br><br><label>Access Token</label><br>
            <textarea class="boxsizingBorder"  style="display: none;" rows="5"></textarea>
        </div>
        <div  style="display: none;">
            <br><br><label>Refresh Token</label><br>
            <textarea class="boxsizingBorder"  style="display: none;"></textarea>
        </div>

        <?php } else { ?>

        <?php 

        $post_data = [
            'grant_type' => $grant_type,
            'code' => $auth_code,
            'redirect_uri' => $redirect_uri
        ];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $apiURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($post_data),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
                ]
            ]
        );

        $response = curl_exec($curl);
        $status = curl_getinfo($curl);

        curl_close($curl);

        $results = json_decode($response, true);

        ?>


        <div  style="display: none;">
            <br><br><label>Access Token</label><br>
            <textarea class="boxsizingBorder" rows="5"><?php print_r($results["access_token"]); ?></textarea>
        </div>
        <div  style="display: none;">
            <br><br><label>Refresh Token</label><br>
            <textarea class="boxsizingBorder" style="display: none;"><?php print_r($results["refresh_token"]);?></textarea>
        </div>


        <?php } ?>

        <?php 

        $grant_type = "refresh_token";
        $refresh_token = $response["refresh_token"];
        $scope = "https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/buy.order.readonly https://api.ebay.com/oauth/api_scope/buy.guest.order https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.marketplace.insights.readonly https://api.ebay.com/oauth/api_scope/commerce.catalog.readonly https://api.ebay.com/oauth/api_scope/buy.shopping.cart https://api.ebay.com/oauth/api_scope/buy.offer.auction https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.email.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.phone.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.address.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.name.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.status.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/sell.item.draft https://api.ebay.com/oauth/api_scope/sell.item https://api.ebay.com/oauth/api_scope/sell.reputation https://api.ebay.com/oauth/api_scope/sell.reputation.readonly https://api.ebay.com/oauth/api_scope/commerce.notification.subscription https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly https://api.ebay.com/oauth/api_scope/sell.stores https://api.ebay.com/oauth/api_scope/sell.stores.readonly";
        
        $post_data = [
            'grant_type' => $grant_type,
            'refresh_token' => "v^1.1#i^1#r^1#p^3#I^3#f^0#t^Ul4xMF83OkZFMDQ5NTk4NkI0MzgyRjVFQTY2QzQ3NEIxNDY0RkMxXzBfMSNFXjI2MA==",
            // 'scope' => $scope
        ];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $apiURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($post_data),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
                ]
            ]
        );

        $response = curl_exec($curl);
        $status = curl_getinfo($curl);

        curl_close($curl);

        $results = json_decode($response, true);

        ?>


        <div>
            <br><br><label>Refreshed Access Token</label><br>
            <textarea class="boxsizingBorder" rows="5"><?php print_r($results["access_token"]);?></textarea>
        </div>
        <hr>

        <?php  
        $access_token = $results["access_token"];
        $apiURL = "https://api.ebay.com/ws/api.dll";
        $per_page = 20;
        $page_number = 1;
        
        $post_data = 
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
        '<RequesterCredentials>' .
          '<eBayAuthToken>' . $access_token . '</eBayAuthToken>' .
            '</RequesterCredentials>' .
          '<ErrorLanguage>en_US</ErrorLanguage>' .
            '<WarningLevel>High</WarningLevel>' .
            '<ActiveList>' .
          '<Sort>TimeLeft</Sort>' .
            '<Pagination>' .
            '<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
              '<PageNumber>' . $page_number . '</PageNumber>' .
              '</Pagination>' .
            '</ActiveList>' .
        '</GetMyeBaySellingRequest> ';

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $apiURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$post_data,
                CURLOPT_HTTPHEADER => [
                    'X-EBAY-API-SITEID:0',
                    'X-EBAY-API-COMPATIBILITY-LEVEL:967',
                    'X-EBAY-API-CALL-NAME:GetMyeBaySelling',
                ]
            ]
        );

        $response = curl_exec($curl);
        $status = curl_getinfo($curl);

        curl_close($curl);

        $xml=simplexml_load_string($response) or die("Error: Cannot create object");
        $json = json_decode(json_encode($xml), true);

        foreach($json["ActiveList"]["ItemArray"]["Item"] as $item){
        ?>
            <div class="row border-bottom py-3">
                <div class="col-xl-3">
                    <img src="" data-itemID="" class="itemImage">
                </div>
                <div class="col-xl-9">
                    <div class="row">
                        <div class="col-xl-12">
                            <label>Title</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["Title"] ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <label>ItemID</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["ItemID"] ?>"/>
                        </div>
                        <div class="col-xl-6">                              
                            <label>StartTime</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["ListingDetails"]["StartTime"] ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <label>StartPrice</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["StartPrice"] ?>"/>
                        </div>
                        <div class="col-xl-6">
                            <label>CurrentPrice</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["SellingStatus"]["CurrentPrice"] ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <label>BidCount</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["SellingStatus"]["BidCount"] ?>"/>
                        </div>
                        <div class="col-xl-6">
                            <label>SKU</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["SKU"] ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <label>ListingType</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["ListingType"] ?>"/>
                        </div>
                        <div class="col-xl-6">
                            <label>ListingDuration</label>
                            <input class="form-control mb-2" type="text" value="<?php echo $item["ListingDuration"] ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        $get_item_body = '<?xml version="1.0" encoding="utf-8"?><GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents"><ErrorLanguage>en_US</ErrorLanguage><WarningLevel>High</WarningLevel><ItemID>256380588293</ItemID></GetItemRequest>';

        ?>
    
        </div>

        <script>
        var xml = '';

        $.ajax({

            url: "https://api.ebay.com/buy/browse/v1/item/get_item_by_legacy_id?legacy_item_id=266676499755",
            method: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: {},
            headers: {
                "Authorization": 'Bearer v^1.1#i^1#f^0#I^3#p^3#r^0#t^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'
            },
            beforeSend:function(){
            },
            success: function (resp){

                console.log(resp);

            },
            complete: function(){
            },
            error: function (resp){

            }
        });


        </script>

    </body>
</html>