<div>
    <button class="ebayintegration-btn" data-action="getItems" data-per_page="<?php echo $this->per_page ?><">Get Active eBay Items</button>
    <button class="ebayintegration-btn" data-action="refreshToken">Reconnect to eBay</button>
</div>		
<div class="ebayintegration-items_box">

    <div class="row mt-4">
        <div class="col-4">
            <H3 style="color: black;">Active SKUs</H3>            
            <input class="btn mb-3 px-2 search_box" style="text-align: left;" placeholder="Search SKU" type="text" data-target="#members_skus_table">
            <table class='table table-border table-striped' id="skus_table"> 
                <thead>
                    <tr>
                        <th>eBay SKU</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="col-8 text-end">
        </div>
    </div>
</div>		
