<div>
    <button class="ebayintegration-btn" data-action="getItems" data-per_page="<?php echo $this->per_page ?><">Get Active eBay Items</button>
    <button class="ebayintegration-btn" data-action="refreshToken">Reconnect to eBay</button>
</div>		
<div class="ebayintegration-items_box">
    <div class="row mt-3"> 
        </div class="col-xl-6">
            <input class="btn mt-3 px-2 search_box" style="text-align: left;" placeholder="Search" type="text" data-target="#skus_table">
            <table class='table table-border table-striped' id="skus_table">
                <thead>
                    <tr>
                        <td>eBay SKU</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>


</div>		
