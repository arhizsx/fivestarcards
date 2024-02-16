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

        <?php if(isset($_GET["code"]) === false){ ?>
            <div>
            <br><br><label>Access Token</label><br>
            <textarea class="boxsizingBorder" rows="20"></textarea>
        </div>
        <div>
            <br><br><label>Refresh Token</label><br>
            <textarea class="boxsizingBorder"></textarea>
        </div>

        <?php } else { ?>

        <?php 

        $auth_code = urldecode($_GET["code"]);

        $client_id = "Fernando-5starcar-PRD-a81fdd189-a762dfc7";
        $client_secret = "PRD-81fdd189cbf1-f472-4131-93a2-1990";
        $redirect_uri  = "Fernando_Salvad-Fernando-5starc-qxmeny";
        $apiURL = "https://api.ebay.com/identity/v1/oauth2/token";
        $grant_type = "authorization_code";

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


        <div>
            <br><br><label>Access Token</label><br>
            <textarea class="boxsizingBorder" rows="20"><?php print_r($results["access_token"]); ?></textarea>
        </div>
        <div>
            <br><br><label>Refresh Token</label><br>
            <textarea class="boxsizingBorder"><?php print_r($results["refresh_token"]);?></textarea>
        </div>

        <?php 

        $grant_type = "refresh_token";
        $refresh_token = $response["refresh_token"];
        $scope = "https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/buy.order.readonly https://api.ebay.com/oauth/api_scope/buy.guest.order https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.marketplace.insights.readonly https://api.ebay.com/oauth/api_scope/commerce.catalog.readonly https://api.ebay.com/oauth/api_scope/buy.shopping.cart https://api.ebay.com/oauth/api_scope/buy.offer.auction https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.email.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.phone.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.address.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.name.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.status.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/sell.item.draft https://api.ebay.com/oauth/api_scope/sell.item https://api.ebay.com/oauth/api_scope/sell.reputation https://api.ebay.com/oauth/api_scope/sell.reputation.readonly https://api.ebay.com/oauth/api_scope/commerce.notification.subscription https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly https://api.ebay.com/oauth/api_scope/sell.stores https://api.ebay.com/oauth/api_scope/sell.stores.readonly";
        
        $post_data = [
            'grant_type' => $grant_type,
            'refresh_token' => "v^1.1#i^1#f^0#p^3#I^3#r^1#t^Ul4xMF8xMToyOENGODYzNEU0NDNBMTEwMjJCNTMzNjkyNjc2REQ5Q18wXzEjRV4yNjA=",
            'scope' => $scope
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
            <br><br><label>Refresh Token</label><br>
            <textarea class="boxsizingBorder" rows="5"><?php print_r($results);?></textarea>
        </div>

        <?php } ?>
    </body>
</html>