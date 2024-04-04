<div>
    <button class="ebayintegration-btn" data-action="getItems" data-per_page="<?php echo $this->per_page ?><">Get Active eBay Items</button>
    <button class="ebayintegration-btn" data-action="refreshToken">Reconnect to eBay</button>
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
                <tbody>
                    <tr>
                        <td colspan="6" class="my-5 text-center">Refresh eBay Items</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>		

<div class="modal fade set_sku_user" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
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
                    <input type="hidden" name="user_id" value=''/>
                    <input type="hidden" name="action" value=''/>
                    <div class="row">
                        <div class="col-xl-6">
                            <label>SKU</label>
                            <input type="text" name="clicked_sku" disabled class="form-control mb-2" value=''/>                            
                        </div>
                        <div class="col-xl-6">
                            <label>SKU</label>
                            <input type="text" name="user_name" disabled class="form-control mb-2" value=''/>                            
                        </div>
                    </div>
                </form>
                <div class="row mt-4">
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
