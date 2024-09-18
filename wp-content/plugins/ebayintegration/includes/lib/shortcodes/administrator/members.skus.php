<?php
global $wpdb;


$user_skus = $wpdb->get_results ( "
SELECT * FROM `wp_usermeta` WHERE meta_key = 'sku' ORDER BY `user_id` ASC;
");

$active_skus = [];

foreach($user_skus as $sk){


    $active = get_user_meta( $sk->user_id, "sku", true );
    array_push( $active_skus, ...$active);

}

?>
<style>
    input {padding: 3px;}
    select {
        padding: 3px;
    }
    .ebayintegration-btn {
        text-decoration: none; 
    }
</style>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-6">
            <?php 

                $args = array(
                    'orderby'    => 'display_name',
                    'order'      => 'ASC'
                );

                $users = get_users( $args );

                $total_users = 0;
            ?>
            <H2 style="color: black;">User SKUs</H2>            
        </div>
        <div class="col-6 text-end">
            <input class="btn mt-3 px-2 search_box" style="text-align: left;" placeholder="Search" type="text" data-target="#members_skus_table">
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table table-bordered table-striped' id="members_skus_table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>SKUs</th>
                    <th class='text-end'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    if($users){                        
                        foreach($users as $user){

                        $skus = get_user_meta( $user->ID, "sku", true );

                ?>
                    <tr class="user_row" data-user_id="<?php  echo $user->ID ?>">
                        <td class="info">
                            <strong><?php  echo $user->display_name ?></strong><br>
                            <small><?php  echo $user->user_email ?></small><br>
                            <small><?php  echo $user->ID + 1000 ?></small>
                        </td>
                        <td class="skus">
                            <?php
                                if( $skus != null ){
                                    echo "<ul class='user_sku_list'>";
                                    
                                    foreach($skus as $sku){
                                        echo "<li><a href='#' class='ebayintegration-btn' data-action='removeSKU' data-sku='" . $sku . "' data-user_id='" . $user->ID ."'> X </a> ". $sku . "</li>";
                                    }    
                                    echo "</ul>";

                                }
                            ?>
                        </td>
                        <td class="text-end action">
                            <button class="btn border btn-primary ebayintegration-btn" 
                                data-action='addSKU' 
                                data-user_name='<?php  echo $user->display_name ?>' 
                                data-user_email='<?php  echo $user->user_email ?>' 
                                data-user_id='<?php echo $user->ID; ?>'
                            >
                                Add SKU
                            </button>
                        </td>
                    </tr>
                <?php    
                        }
                    } else {
                ?>
                    <tr>
                        <td class="text-center" colspan="4">Empty</td>
                    </tr>
                <?php                  
                    }
                ?>                
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade add_sku" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Add User SKU
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="add_sku_form" class=" mb-3">
                            <input type="hidden" name="user_id" value=''/>
                            <input type="hidden" name="action" value=''/>
                            
                            <label>Name</label>
                            <input type="text" name="user_name" disabled class="form-control mb-2" value=''/>
                            
                            <label>Email</label>
                            <input type="text" name="user_email" disabled  class="form-control mb-2" value=''/>
                            
                            <label>User ID</label>
                            <input type="text" name="id" disabled  class="form-control mb-2" value=''/>
                            
                            <label>eBay SKU</label>
                            <select class="form-control mb-2" name="sku">
                                <option selected value="">Select Active eBay SKU</option>
                                <?php 

                                    $skus = $wpdb->get_results ( "
                                        SELECT DISTINCT sku, user_id FROM ebay ORDER BY sku ASC
                                    " );                                    

                                    foreach($skus as $sku){ 
                                        if(in_array( $sku->sku, $active_skus ) === false ){
                                            echo "<option value='" . $sku->sku . "'>" . $sku->sku . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                        <button class="btn border btn-primary ebayintegration-btn" 
                            data-action='confirmAddSKU' 
                        >
                            Add SKU
                        </button>
                    </div>
                </div>
		</div>
	</div>
</div>
