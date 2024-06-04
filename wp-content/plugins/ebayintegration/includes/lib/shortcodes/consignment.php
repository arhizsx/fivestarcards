<style>

    .shortcode_tab_box {
        box-shadow: 0px 2px 18px 0px rgba(0,0,0,0.3);
    }
    
    .shortcode_tab_box ul {
        list-style-type: none;
        padding: 0 !important;
        line-height: inherit !important;        
        background-color: #f4f4f4;
        border-bottom: none;
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
        border-bottom: 1px solid #d9d9d9;
        display: table;
        height: 72px;
        position: relative;
        line-height: 4em;
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
            <a class="" href="/my-account/my-consignment">New</a>
        </li>
        <li class="<?php echo Activate("to-ship"); ?>">
            <a class="" href="/my-account/my-consignment/?mode=to-ship">To Ship</a>
        </li>
        <li class="<?php echo Activate("consigned"); ?>">
            <a class="" href="/my-account/my-consignment/?mode=consigned">Consigned</a>
        </li>
        <li class="<?php echo Activate("sold"); ?>">
            <a class="" href="/my-account/my-consignment/?mode=sold">Sold</a>
        </li>
    </ul>
    <ul class="clearfix d-lg-none">
        <li class="">
            <a class="" href="/my-account/my-consignment">New</a>
        </li>

    </ul>
    <div class="content">
        <?php 
        if(isset( $_GET['mode']) ){
            echo $_GET['mode'] . "<br>";

			include( plugin_dir_path( __FILE__ ) . "consignment." . $_GET['mode'] . '.php' );			

        } else {
            echo "new <br>";

			include( plugin_dir_path( __FILE__ ) . 'consignment.new.php' );			

        }
        ?>
    </div>
</div>