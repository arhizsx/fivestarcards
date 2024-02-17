<html>
    <head>
        <style>
            .boxsizingBorder {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                        box-sizing: border-box;
                width: 100%;
            }            
        </style>
    </head>
    <body>
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
        $per_page = 200;
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
            <hr>
            <label>ItemID</label><br>
            <input type="text" value="<?php echo $item["ItemID"] ?>"/><br><br>
            <label>Title</label><br>
            <input type="text" value="<?php echo $item["Title"] ?>"/><br><br>
            <label>StartPrice</label><br>
            <input type="text" value="<?php echo $item["StartPrice"] ?>"/><br><br>
            <label>CurrentPrice</label><br>
            <input type="text" value="<?php echo $item["SellingStatus"]["CurrentPrice"] ?>"/><br><br>
            <label>BidCount</label><br>
            <input type="text" value="<?php echo $item["SellingStatus"]["BidCount"] ?>"/><br><br>
            <label>SKU</label><br>
            <input type="text" value="<?php echo $item["SKU"] ?>"/><br><br>
        <?php
        }
        ?>


    </body>
</html>