<div>
    <button class="ebayintegration-btn" data-action="getItems" data-per_page="<?php echo $this->per_page ?><">Get Active eBay Items</button>
    <button class="ebayintegration-btn" data-action="refreshToken">Reconnect to eBay</button>
</div>		
<div class="ebayintegration-items_box">
    <div class="row">
        <div class="col-6">
            <H1 style="color: black;">Active SKUs</H1>            
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
        <div class="col-6 text-end">
            <input class="btn mt-3 px-2 search_box" style="text-align: left;" placeholder="Search" type="text" data-target="#members_skus_table">
        </div>
    </div>
</div>		
