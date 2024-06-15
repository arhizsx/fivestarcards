
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class=" d-none d-lg-block">
                <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("auction") ?>" href="/my-account/consignment/?mode=listed&type=auction">Auction</a>
                <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("fixed_price") ?>" href="/my-account/consignment/?mode=listed&type=fixed_price">Fixed Price</a>
                <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("awaiting_payment") ?>" href="/my-account/consignment/?mode=listed&type=awaiting_payment">Awaiting Payment</a>
                <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("pending_payout") ?>" href="/my-account/consignment/?mode=listed&type=pending_payout">Pending Payout</a>
                <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("paid_out") ?>" href="/my-account/consignment/?mode=listed&type=paid_out">Paid Out</a>
            </div>
            <div class="col d-lg-none p-3">
                <label>Select Listing Status</label>
                <select class="form-control" id="mobile_tab_select">
                    <option value="/my-account/consignment/?mode=listed&type=auction" <?php echo ActivateListingSelect("auction") ?>>Auction</option>
                    <option value="/my-account/consignment/?mode=listed&type=fixed_price" <?php echo ActivateListingSelect("fixed_price") ?>>Fixed Price</option>
                    <option value="/my-account/consignment/?mode=listed&type=awaiting_payment" <?php echo ActivateListingSelect("awaiting_payment") ?>>Awaiting Payment</option>
                    <option value="/my-account/consignment/?mode=listed&type=pending_payout" <?php echo ActivateListingSelect("pending_payout") ?>>Pending Payout</option>
                    <option value="/my-account/consignment/?mode=listed&type=paid_out" <?php echo ActivateListingSelect("paid_out") ?>>Paid Out</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">



    </div>
</div>
