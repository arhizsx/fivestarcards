
<div class="container-fluid">
    <div class="row">
        <div class="col d-none d-lg-block">
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("auction") ?>" href="/my-account/consignment/?mode=listed&type=auction">Auction</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("fixed_price") ?>" href="/my-account/consignment/?mode=listed&type=fixed_price">Fixed Price</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("awaiting_payment") ?>" href="/my-account/consignment/?mode=listed&type=awaiting_payment">Awaiting Payment</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("pending_payout") ?>" href="/my-account/consignment/?mode=listed&type=pending_payout">Pending Payout</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("paid_out") ?>" href="/my-account/consignment/?mode=listed&type=paid_out">Paid Out</a>
        </div>
        <div class="col">
            <label>Select Listing Status</label>
            <select class="form-control" id="mobile_tab_select">
                <option value="/my-account/consignment/?mode=listed&type=auction" <?php echo ActivateListingSelect("new") ?>>Auction</option>
                <option value="/my-account/consignment/?mode=listed&type=fixed_price" <?php echo ActivateListingSelect("to-ship") ?>>Fixed Price</option>
                <option value="/my-account/consignment/?mode=listed&type=awaiting_payment" <?php echo ActivateListingSelect("consigned") ?>>Awaiting Payment</option>
                <option value="/my-account/consignment/?mode=listed&type=pending_payout" <?php echo ActivateListingSelect("listed") ?>>Pending Payout</option>
                <option value="/my-account/consignment/?mode=listed&type=paid_out" <?php echo ActivateListingSelect("sold") ?>>Paid Out</option>
            </select>

        </div>
    </div>
    <div class="row">



    </div>
</div>
