<style>

    .shortcode_tab_box {
        box-shadow: 0px 2px 18px 0px rgba(0,0,0,0.3);
    }

    .shortcode_tab_box H1 {
        color: black;
    }
    
    .shortcode_tab_box ul {
        list-style-type: none;
        padding: 0 !important;
        line-height: inherit !important;        
        background-color: #f4f4f4;
        height: auto !important;
        word-wrap: break-word;
        box-sizing: border-box;
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .shortcode_tab_box li {
        float: left;
        border-right: none;
        border-bottom: none;
        display: table;
        height: 72px;
        position: relative;
        line-height: 4em;
        font-weight: bold;
    }
    .shortcode_tab_box li a {
        text-decoration: none;
        padding: 4px 30px;
        vertical-align: middle;
        display: table-cell;
        color: #666;
    }

    .shortcode_tab_box li.active {
        background-color: #fff;
        font-weight: bold;
        border-bottom: none;

    }

</style>

<?php 

    function Activate($page){
        if( isset( $_GET['mode']) == false ){
            if( $page == "new" ){
                return "active";
            }
        } 
        else {
            if( $page == $_GET['mode'] ){
                return "active";
            } else {
                return "";
            }
        }
    }


?>

<div class="shortcode_tab_box">
    <ul class="clearfix d-none d-lg-block">
        <li class="<?php echo Activate("new"); ?>">
            <a class="" href="/my-account/consignment">New</a>
        </li>
        <li class="<?php echo Activate("to-ship"); ?>">
            <a class="" href="/my-account/consignment/?mode=to-ship">To Ship</a>
        </li>
        <li class="<?php echo Activate("consigned"); ?>">
            <a class="" href="/my-account/consignment/?mode=consigned">Consigned</a>
        </li>
        <li class="<?php echo Activate("sold"); ?>">
            <a class="" href="/my-account/consignment/?mode=sold">Sold</a>
        </li>
    </ul>
    <ul class="clearfix d-lg-none">
        <li class="">
            <a class="" href="/my-account/consignment">New</a>
        </li>

    </ul>
    <div class="content p-3">
        <?php 
        if(isset( $_GET['mode']) ){
            include( plugin_dir_path( __FILE__ ) . "consignment." . $_GET['mode'] . '.php' );			

        } else {
            include( plugin_dir_path( __FILE__ ) . 'consignment.new.php' );			

        }
        ?>
    </div>
</div>

<div class="modal fade log_consign_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog modal-xl" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Log Card to Consign
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                <button class="btn border btn-primary ebayintegration-btn" 
                    data-action='confirmAddConsign' 
                >
                    Log Card
                </button>
            </div>

		</div>
	</div>
</div>

