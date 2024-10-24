<?php 
    global $wpdb;

    $args = array(
        'orderby'    => 'display_name',
        'order'      => 'ASC'
    );

    $users = get_users( $args );

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

    $ebay = $this->wpdb->get_results ( "
        SELECT * 
        FROM  ebay
        WHERE status = 'ActiveList'
        ORDER BY sku ASC
    " );
?>

<div>
    <!-- <button class="ebayintegration-btn" data-action="getItems" data-per_page="<?php echo $this->per_page ?><">Refresh Active eBay Items</button> -->
    <!-- <button class="ebayintegration-btn" data-action="refreshToken">Reconnect to eBay</button> -->
</div>		
<div class="ebayintegration-items_box">

    <div class="row mt-4">
        <div class="col-12">
            <H3 style="color: black;">Active eBay Items</H3>            
            <input class="btn mb-3 px-2 search_box" style="text-align: left;" placeholder="Search" type="text" data-target="#skus_table">
            <table class='table table-border table-striped' id="skus_table"> 
                <thead>
                    <tr>
                        <th>ItemID</th>
                        <th>Title</th>
                        <th>eBay SKU</th>
                        <th>ListingType</th>
                        <th>ListingDuration</th>
                        <th>CurrentPrice</th>
                    </tr>
                </thead>
                <tbody style="height: 70vh; overflow: auto; ">
                    <?php 
                        foreach($ebay as $item){
                            $item_data = json_decode( $item->data, true );
                            if( ! in_array($item_data["SKU"], $all_skus) ){

                    ?>
                        <tr  class='ebayintegration-btn ebay-item' data-action='set_sku_user' data-item_id='<?php echo $item_data["ItemID"]; ?>' data-sku='<?php echo $item_data["SKU"]; ?>'>
                            <td><?php echo $item_data["ItemID"]; ?></td>
                            <td><?php echo $item_data["Title"]; ?></td>
                            <td><?php echo $item_data["SKU"]; ?></td>
                            <td><?php echo $item_data["ListingType"]; ?></td>
                            <td><?php echo $item_data["ListingDuration"]; ?></td>
                            <td><?php echo $item_data["SellingStatus"]["CurrentPrice"]; ?></td>
                        </tr>
                    <?php 
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="6" class="my-5 text-center">Refresh eBay Items</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>		

<div class="modal fade add_sku" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog modal-xl" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Add User SKU
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
                <forn id="add_sku_form" class=" mb-3">
                    <input type="hidden" name="action" value='confirmAddSKU'/>
                    <div class="row">
                        <div class="col-xl-6">
                            <label>SKU</label>
                            <input type="text" name="sku" disabled class="form-control mb-2 clicked_sku" value=''/>                            
                        </div>
                        <div class="col-xl-6">

 
                            <label>User</label>
                            <select name="user_id" class="form-control">
                                <option value="">Select User</option>
                                <?php 
                                if($users){                        
                                    foreach($users as $user){
                                        if( $user->active == 0 ) {
                                ?>
                                    <option value="<?php echo $user->ID ?>"><?php echo $user->display_name ?> - <?php echo $user->user_email ?></option>
                                <?php
                                        } 
                                    }

                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <H5 style="color: black;">Active eBay Items</H5>
                        <div  id="items_with_sku" style="overflow:auto;" class="border">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                <button class="btn border btn-primary ebayintegration-btn" 
                    data-action='confirmAddSKU' 
                >
                    Set SKU to User
                </button>
            </div>

		</div>
	</div>
</div>
