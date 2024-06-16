<?php 
	public function GetItemPages(){

		$per_page = 2;
		$page_number = 1;

		$apiURL = "https://api.ebay.com/ws/api.dll";

		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
			'<eBayAuthToken>' . $this->access_token  . '</eBayAuthToken>' .
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
		
		if(array_key_exists( "Ack", $json )){


			if($json["Ack"] == "Failure"){

				if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
					
					return array("error" => true, "data"=> "Refresh Access Token");

				} else {

					return array("error" => true, "data"=> $json);

				}
	
			} else {
				
				if( count($json["ActiveList"]["ItemArray"]["Item"]) == 2){
	
					$entries = $json["ActiveList"]["PaginationResult"]["TotalNumberOfEntries"];
					$pages = ceil($entries / $this->per_page);
	
					return array("error" => false, "data"=> $pages);
					
				} else {

					return array("error" => true, "data"=> $json);
				}
			}
	
		} else {
			return "Not Valid JSON";
		}

	}				

	public function getItems($page_number = null,  $per_page = null){


		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		if($page_number == null){
			$page_number = 1;
		}

		if($per_page == null){
			$per_page = $this->per_page;
		}

		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		  '<RequesterCredentials>' .
			'<eBayAuthToken>' . get_option("wpt_access_token")  . '</eBayAuthToken>' .
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
			'<SoldList>' .
				'<Include>true</Include>' .
				'<Pagination>' .
					'<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
			  		'<PageNumber>' . $page_number . '</PageNumber>' .
			  	'</Pagination>' .
				'<DurationInDays>60</DurationInDays>' .
			'</SoldList>' .
			'<UnsoldList>' .
				'<Include>true</Include>' .
				'<Pagination>' .
					'<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
			  		'<PageNumber>' . $page_number . '</PageNumber>' .
			  	'</Pagination>' .
				'<DurationInDays>60</DurationInDays>' .
			'</UnsoldList>' .
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
		
		if(array_key_exists( "Ack", $json )){

			if($json["Ack"] == "Failure"){

				if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
					
					return array("error" => true, "data"=> "Refresh Access Token");

				} else {

					return array("error" => true, "data"=> $json);

				}
	
			} else {

				foreach($json["ActiveList"]["ItemArray"]["Item"] as $item){

					$this->wpdb->replace("ebay", array(
						"item_id" => $item["ItemID"],
						"sku" => $item["SKU"],
						"data" => json_encode($item),
						"status" => "active"
					));
				}
		
				return array("error" => false, "page" => $page_number, "data"=> $json, "items" => $json["ActiveList"]["ItemArray"]["Item"] );
				
			}
	
		} else {
			return "Not Valid JSON";
		}
		
	}




public function refreshTransaction(){


    $results = $this->wpdb->get_results("
        SELECT * FROM ebay where status = 'active'
    ");
    
    $items = [];

    foreach($results as $result){					
        if($result->transaction == null){
            array_push($items, $result->item_id);
        }
    }

    $apiURL = "https://api.ebay.com/ws/api.dll";

    $multiCurl = array();
    $result = array();
    $mh = curl_multi_init();

    // Setup Multi Curl Requests

    for( $i = 0; $i <= count($items) -1; $i++ ){

        $post_data = 
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<GetItemTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
        '<RequesterCredentials>' .
        '<eBayAuthToken>' . get_option("wpt_access_token")  . '</eBayAuthToken>' .
        '</RequesterCredentials>' .
        '<ItemID>' . $items[ $i ] .'</ItemID>' .
        '</GetItemTransactionsRequest>';	
        
        $multiCurl[$i] = curl_init();			

        curl_setopt_array(
            $multiCurl[$i],
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
                    'X-EBAY-API-CALL-NAME:GetItemTransactions',
                    'X-EBAY-API-IAF-TOKEN::' . get_option("wpt_access_token")
                ]
            ]
        );	

        curl_multi_add_handle($mh, $multiCurl[$i]);
    }

    $index = null;

    // Execute Multi Curl
    do {

        curl_multi_exec( $mh, $index );

    } 
    while($index > 0);		

    // Save Result of Each Curl Pass
    foreach($multiCurl as $k => $ch) {

        $curl_result  = curl_multi_getcontent($ch);

        $xml = simplexml_load_string( $curl_result ) or die("Error: Cannot create object");
        $json = json_decode(json_encode($xml), true);
        
        $result[$k] = $json;

        if( $json["Ack"] == "Success" ){

            $status = "active";
            $transaction = null;

            if($json["Item"]["SellingStatus"]["ListingStatus"] == "Completed") {

                $status = "completed";

                if( array_key_exists("TransactionArray", $json) ) {
                    $transaction = json_encode( $json["TransactionArray"] );
                } else {
                    $transaction = "Not Sold";
                }	

            }
            elseif( $json["Item"]["SellingStatus"]["ListingStatus"] == "Active" ) {

                if( $json["Item"]["SellingStatus"]["QuantitySold"] == "0" ){

                    $status = "active";

                }
                elseif( $json["Item"]["SellingStatus"]["QuantitySold"] == "1" ){

                    $status = "awaiting";
                    
                }

                $transaction = null;
            }


            $this->wpdb->update(
                "ebay", 
                array(
                    "transaction" => $transaction,
                    "status" => $status
                ),
                array(
                    "item_id" => $json["Item"]["ItemID"]
                )
            );

        }

        curl_multi_remove_handle($mh, $ch);

    }

    // Close Multi Curl
    curl_multi_close($mh);		

    return $result;


}
		
public function getItemsMulti($pages = null,  $per_page = null){

    $apiURL = "https://api.ebay.com/ws/api.dll";



    $multiCurl = array();
    $result = array();
    $mh = curl_multi_init();

    // Setup Multi Curl Requests

    for( $i = 1; $i <= $pages; $i++ ){

        $post_data = 
            '<?xml version="1.0" encoding="utf-8"?>' .
            '<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
            '<RequesterCredentials>' .
                '<eBayAuthToken>' . $this->access_token  . '</eBayAuthToken>' .
                '</RequesterCredentials>' .
                '<ErrorLanguage>en_US</ErrorLanguage>' .
                '<WarningLevel>High</WarningLevel>' .
                '<ActiveList>' .
                '<Sort>TimeLeft</Sort>' .
                '<Pagination>' .
                '<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
                    '<PageNumber>' . $i . '</PageNumber>' .
                    '</Pagination>' .
                '</ActiveList>' .
            '</GetMyeBaySellingRequest> ';


        $multiCurl[$i] = curl_init();			

        curl_setopt_array(
            $multiCurl[$i],
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

        curl_multi_add_handle($mh, $multiCurl[$i]);

    }

    $index = null;

    // Execute Multi Curl
    do {

        curl_multi_exec( $mh, $index );

    } 
    while($index > 0);		

    // Save Result of Each Curl Pass
    foreach($multiCurl as $k => $ch) {

        $result[$k] = curl_multi_getcontent($ch);
        
        curl_multi_remove_handle($mh, $ch);

    }

    // Close Multi Curl
    curl_multi_close($mh);		


    // Get All Items From All Results
    $items = array();
    $ebay_skus = array();

    foreach( $result as $k => $v ){

        $xml=simplexml_load_string( $result[$k] ) or die("Error: Cannot create object");
        $json = json_decode(json_encode($xml), true);
        
        if(array_key_exists( "Ack", $json )){

            if($json["Ack"] == "Failure"){

                if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
                    
                    return array("error" => true, "data"=> "Refresh Access Token");

                } else {

                    return array("error" => true, "data"=> $json);

                }
    
            } else {

                foreach( $json["ActiveList"]["ItemArray"]["Item"] as $item ){
                    array_push( $items, $item );

                    if( ! in_array($item["SKU"], $ebay_skus) ){
                        array_push( $ebay_skus, $item["SKU"]);
                    }

                    $this->wpdb->replace("ebay", array(
                        "item_id" => $item["ItemID"],
                        "sku" => $item["SKU"],
                        "data" => json_encode($item),
                        "status" => "active"
                    ));
                    
                }					
            }
    
        } else {
            return "Not Valid JSON";
        }

    }

    // Get All User SKUs

    $users_with_sku = $this->wpdb->get_results ( "
        SELECT user_id 
        FROM  wp_usermeta
        WHERE meta_key = 'sku'
    " );

    $all_skus = array();

    foreach($users_with_sku as $user){
        $skus = get_user_meta( $user->user_id, "sku", true );		
        array_push( $all_skus, ...$skus );
    }

    
    // Remove Items With SKUs

    $undefined_items = array();
    foreach($items as $item){
        if( ! in_array( $item["SKU"], $all_skus ) ) {
            array_push( $undefined_items, $item );
        }
    }

    return ["count" => count( $items ), "ebay_items" => $items, "active_skus" => $all_skus, "undefined_items" => $undefined_items, "ebay_skus" => $ebay_skus ];

}	

public function getItemTransactions($item_id){


    $apiURL = "https://api.ebay.com/ws/api.dll";
    
    $post_data = 
    '<?xml version="1.0" encoding="utf-8"?>' .
    '<GetItemTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
    '<RequesterCredentials>' .
    '<eBayAuthToken>' . get_option("wpt_access_token")  . '</eBayAuthToken>' .
    '</RequesterCredentials>' .
    '<ItemID>' . $item_id .'</ItemID>' .
    '</GetItemTransactionsRequest>';	


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
                'X-EBAY-API-CALL-NAME:GetItemTransactions',
                'X-EBAY-API-IAF-TOKEN::' . get_option("wpt_access_token")
            ]
        ]
    );
    
    $response = curl_exec($curl);
    $status = curl_getinfo($curl);
    
    curl_close($curl);
    
    $xml=simplexml_load_string($response) or die("Error: Cannot create object");
    $json = json_decode(json_encode($xml), true);
    
    if(array_key_exists( "Ack", $json )){

        if($json["Ack"] == "Failure"){

            if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
                
                return array("error" => true, "data"=> "Refresh Access Token");

            } else {

                return array("error" => true, "data"=> $json);

            }
 
        } else {

            return array("error" => false, "data"=> $json);
            
        }

    } else {
        return "Not Valid JSON";
    }


}



public function getItemInfo($item_id){

    $apiURL = "https://api.ebay.com/ws/api.dll";
    
    $post_data = 
    '<?xml version="1.0" encoding="utf-8"?>' .
    '<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
        '<ErrorLanguage>en_US</ErrorLanguage>' .
        '<WarningLevel>High</WarningLevel>' .
      '<ItemID>' . $item_id .'</ItemID>' .
    '</GetItemRequest>';
            
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
                'X-EBAY-API-CALL-NAME:GetItem',
                'X-EBAY-API-IAF-TOKEN::' . get_option("wpt_access_token")
            ]
        ]
    );
    
    $response = curl_exec($curl);
    $status = curl_getinfo($curl);
    
    curl_close($curl);
    
    $xml=simplexml_load_string($response) or die("Error: Cannot create object");
    $json = json_decode(json_encode($xml), true);
    
    if(array_key_exists( "Ack", $json )){

        if($json["Ack"] == "Failure"){

            if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
                
                return array("error" => true, "data"=> "Refresh Access Token");

            } else {

                return array("error" => true, "data"=> $json);

            }
 
        } else {

            return array("error" => false, "data"=> $json);
            
        }

    } else {
        return "Not Valid JSON";
    }
    
}

?>