<style>
    h2, h3 {
        color: black;
    }
    .grading_box {
        border: 1px solid black;
        padding: 0px;
    }
    .grading_box table {
        margin: 0px !important;
    }
    .grading_title {
        height: 100px;
        vertical-align: middle;
        font-size: 1.2em;
        font-weight: bold;
    }
    .pricing {
        font-size: 3em !important;
        font-weight: bolder;
    }
    .picture_box {
        width: auto; 
        min-height: 120px; 
        max-height: 120px; 
        color: black;
        background-color: lightgray; 
        cursor: pointer;
    }
</style>

<?php 


    if( isset( $_GET["type"] ) ){

        global $wpdb;

        $grading_files = $this->wpdb->get_results ( "
        SELECT * 
        FROM grading
        where user_id = " . get_current_user_id() . " 
        and status = 'logged'
        and type = '" . $_GET['type']  . "_file'
        order by id desc
        "
        );        


        $grading = $this->wpdb->get_results ( "
        SELECT * 
        FROM grading
        where user_id = " . get_current_user_id() . " 
        and status = 'logged'
        and type = '" . $_GET['type']  . "'
        order by id desc
        "
        );        

        $grading_addon = $this->wpdb->get_results ( "
        SELECT * 
        FROM grading_addons
        where user_id = " . get_current_user_id() . " 
        and type = '" . $_GET['type']  . "'
        order by id desc
        "
        );        

        switch( $_GET["type"] ){

            case "psa-value_bulk": 
                $grading_title = "PSA Value Bulk";
                $max_dv = 499;
                $per_card = 19;
                break;

            case "psa-value_bulk_vintage": 
                $grading_title = "PSA Value Bulk Vintage";
                $max_dv = 499;
                $per_card = 19;
                break;

            case "psa-value_plus": 
                $grading_title = "PSA Value Plus";
                $max_dv = 499;
                $per_card = 40;
                break;

            case "psa-regular": 
                $grading_title = "PSA Regular";
                $max_dv = 1499;
                $per_card = 75;
                break;

            case "psa-express": 
                $grading_title = "PSA Express";
                $max_dv = 2499;
                $per_card = 165;
                break;

            case "psa-super_express": 
                $grading_title = "PSA Super Express";
                $max_dv = 4999;
                $per_card = 330;
                break;

            case "sgc-bulk": 
                $grading_title = "SGC Bulk";
                $max_dv = 0;
                $per_card = 19;
                break;

            default: 
                $max_dv = 0;
                $per_card = 0;
            
        }
?>

        <!-- Grading Title -->
        <div class="">
            <H3><?php echo $grading_title; ?></H3>
        </div>

        <!-- UPPER BUTTONS -->
        <div>
            <?php 
                if( isset($_GET["grader"]) ){
                    $grd = "?grader=" . $_GET["grader"];
                } else {
                    $grd = "";
                }
            ?>
            <a href="/my-account/grading/new<?php echo $grd; ?>" class="btn btn-outline-dark mb-3 ">Back to Grading Types</a>

            <button class="btn btn-success mb-3 ebayintegration-btn" data-action="show_log_grading_modal">
                Log Card
            </button>
            <button class="btn btn-dark mb-3 ebayintegration-btn" data-action="show_import_grading_modal">
                Upload Cards List
            </button>
        </div>

        <!-- DESKTOP VIEW -->
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-sm table-bordered" id="new_grading">                
                <thead>
                    <tr>
                        <th style="width: 20px;"></th>
                        <th width="30%">Player Name</th>
                        <th>Year</th>
                        <th>Brand</th>
                        <th>Card Number</th>
                        <th class="text-end">DV</th>
                        <th class="text-end">Grading</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total_dv = 0;
                        $total_grading = 0;

                        if( count( $grading ) == 0 ){
                    ?>
                    <tr class="empty_grading">
                        <td colspan="7" class="text-center py-5">
                            Empty
                        </td>
                    </tr>
                    <?php 
                        } else {


                            foreach( $grading as $card ){

                                $data = json_decode( $card->data, true );



                    ?>
                    <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                        <td>
                            <a class='text-danger  ebayintegration-btn' data-action="removeGradingCardRow"  data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#'>
                                <i class='fa-solid fa-lg fa-xmark'></i>
                            </a>
                        </td>
                        <td><?php echo $data["player"] ?></td>
                        <td><?php echo $data["year"] ?></td>
                        <td><?php echo $data["brand"] ?></td>
                        <td><?php echo $data["card_number"] ?><br><small><?php echo $data["attribute_sn"] ?></small></td>
                        <td class='text-end'>$<?php echo $data["dv"] ?></td>
                        <td class='text-end'>$<?php echo $data["per_card"] ?></td>
                    </tr>
                    <?php 
                                $total_grading = $total_grading + 1;
                                $total_dv = $total_dv + 1;

                            }
                        }
                    ?>            
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='6' class="text-end">Total DV</th>
                        <th colspan='1' class="text-end">$<?php echo $total_dv ?></th>
                    </tr>
                    <tr>
                        <th colspan='6' class="text-end">Grading Charge</th>
                        <th colspan='1' class="text-end">$<?php echo $total_grading ?></th>
                    </tr>
                    <tr>
                            <?php 
                                if(  count( $grading_addon ) > 0 ){
                                    $checked = "checked";
                                    $colspan = "4";                                    
                                } else {
                                    $checked = "";
                                    $colspan = "7";
                                }
                            ?>

                        <td colspan="<?php echo $colspan ?>" class="bg-success py-3 text-white">
                            <input <?php echo $checked ?> type="checkbox" id="service" name="service" class="me-3 grading_inspection_checkbox" data-user_id="<?php echo get_current_user_id() ?>" data-type="<?php echo $_GET["type"] ?>" value="inspection_service"><strong class="">Include Inspection Service</strong> (This will be an additional charge of $3 per card)
                        </td>
                        <?php 
                        if( $checked != "" ){
                        ?>
                        <th colspan='2' class="text-end bg-success text-white">
                            Total Inspection Service
                        </th>
                        <th colspan='1' class="text-end bg-success text-white">
                            $<?php echo count($grading) * 3 ?>
                        </th>
                        <?php 
                        }   
                        ?>
                    </tr>
                    <?php 
                        if( count( $grading_files ) > 0 ){
                    ?>
                    <tr>                        
                        <th colspan="4" class="text-center">
                            Uploaded Cards List Files
                        </th>
                        <th  class="text-center">
                            Quantity
                        </th>
                        <th class="text-center">
                            Card Show
                        </th>
                    </tr>   
                    <?php 
                        foreach($grading_files as $file){
                    ?>
                        <?php 
                            $file_data = json_decode($file->data, true);
                            foreach( $file_data as $fdata ){
                        ?>
                        <tr class="grading_file">
                            <td colspan="4" class="text-left">
                                <a class="me-3 btn btn-danger btn-sm ebayintegration-btn" data-action="remove_grading_file" data-id="<?php echo $file->id ?>" data-file="<?php echo $fdata["baseurl"] ?>" >REMOVE</a>
                                <a href="<?php echo $fdata["baseurl"] ?>" target="_blank"><?php echo $fdata["name"] ?></a>
                            </td>
                            <td>
                                <?php echo $fdata["qty"] ?>
                            </td>                        
                            <td>
                                <?php echo $fdata["card_show"] ?>
                            </td>                        
                        </tr>
                        <?php                                 
                            }
                        ?>                        
                    <?php                             
                        }
                    ?> 
                    <?php 
                        } 
                    ?>
                </tfoot>        
            </table>
        </div>

        <!-- MOBILE VIEW -->
        <div class="d-lg-none pb-2">
            <table class="table table-sm table-bordered" id="new_grading_mobile">
                <thead>
                    <tr>
                        <th colspan="2">Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total_dv = 0;
                        $total_grading = 0;

                        if( count( $grading ) == 0 ){
                    ?>
                    <tr class="empty_grading">
                        <td colspan="2" class="text-center py-5">
                            Empty
                        </td>
                    </tr>
                    <?php 
                        } else {
                            foreach( $grading as $card ){

                            $data = json_decode( $card->data, true );

                            if(array_key_exists("file", $data)){
                                $img = "<img src='" . ($data["file"]["baseurl"]) . "'>";
                            } else {
                                $img = '<div class="d-flex justify-content-center align-items-center picture_box">' .
                                            '<i class="fa-solid fa-file-image fa-2x"></i>' . 
                                        '</div>';

                            }   

                    ?>
                    <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                        <td colspan="2">
                            <div class='w-100 p-0 text-end' style='position: relative;'>
                                <a class='text-danger ebayintegration-btn' data-action="removeGradingCardRow" data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#' style='position: absolute; right: 0px;'>
                                    <i class='fa-solid fa-xl fa-xmark'></i>
                                </a>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Player</div>
                                <div class='col-sm-8'>                                    
                                    <?php echo $data["player"] ?>								
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Year</div>
                                <div class='col-sm-8'>
                                    <?php echo $data["year"] ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Brand</div>
                                <div class='col-sm-8'>
                                    <?php echo $data["brand"] ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-4'>Card #</div>
                                <div class='col-sm-8'>
                                    <?php echo $data["card_number"] ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Attribute SN</div>
                                <div class='col-sm-8'>
                                    <?php echo $data["attribute_sn"] ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Declared Value</div>
                                <div class='col-sm-8'>
                                    $0.00                        
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Grading</div>
                                <div class='col-sm-8'>
                                    $0.00
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                            $total_grading = $total_grading + $data["per_card"];
                            $total_dv = $total_dv + $data["dv"];

                            }
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='1' class="text-end">
                            Total Inspection Service
                        </th>
                        <th colspan='1' class="text-end">
                            $<?php echo count($grading) * 3 ?>
                        </th>
                    </tr>

                    <tr>
                        <th colspan='1' class="text-end">Total DV</th>
                        <th colspan='1' class="text-end">$<?php echo $total_dv ?></th>
                    </tr>
                    <tr>
                        <th colspan='1' class="text-end">Grading Charge</th>
                        <th colspan='1' class="text-end">$<?php echo $total_grading ?></th>
                    </tr>
                    <?php 
                        if( count( $grading_files ) > 0 ){
                    ?>
                    <tr>                        
                        <th colspan="1" class="text-center">
                            Uploaded Cards List Files
                        </th>
                    </tr>   
                    <?php 
                        foreach($grading_files as $file){
                    ?>
                        <?php 
                            $file_data = json_decode($file->data, true);
                            foreach( $file_data as $fdata ){
                        ?>
                        <tr class="grading_file">
                            <td colspan="1" class="text-left">
                                <a class="me-3 btn btn-danger btn-sm ebayintegration-btn" data-id="<?php echo $file->id ?>" data-action="remove_grading_file" data-file="<?php echo $fdata["baseurl"] ?>" >REMOVE</a>
                                <a href="<?php echo $fdata["baseurl"] ?>" target="_blank"><?php echo $fdata["name"] ?></a>
                            </td>
                        </tr>
                        <?php                                 
                            }
                        ?>                        
                    <?php                             
                        }
                    ?> 
                    <?php 
                        } 
                    ?>

                </tfoot>        
            </table>    
        </div>

        <!-- TABLE LOWER BUTTONS -->
        <div class="d-flex justify-content-end">
            <button class="btn btn-danger mb-3 me-2 ebayintegration-btn" data-action="show_grading_table_clear_list" data-user_id="<?php echo get_current_user_id(); ?>" data-grading_type="<?php echo $_GET["type"]; ?>">
                Clear List
            </button>
            <button class="btn btn-primary mb-3  ebayintegration-btn" data-action="show_grading_table_checkout" data-user_id="<?php echo get_current_user_id(); ?>" data-grading_type="<?php echo $_GET["type"]; ?>">
                Checkout
            </button>
        </div>



<!-- GRADING MENU -->

<?php
    } else {

        if( get_current_user_id() != 0 ){

            if( ! isset( $_GET["grader"] ) || $_GET["grader"] == "psa"  ){
?>
    <div class="row mx-3 mb-3">
        <div class="col-12">
            <H2>PSA Grading &emsp; <a style="text-decoration: none; color: lightgray;" href='?grader=sgc'>SGC Grading</a></H2>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #1ba01d; color: #ffffff;">Value Bulk</td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">19</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $499</td>
                </tr>
                <tr>
                    <td>
                    <a href="?grader=psa&type=psa-value_bulk" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #005e0c; color: #ffffff;">Value Bulk Vintage</td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">19</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $499</td>
                </tr>
                <tr>
                    <td>
                    <a href="?grader=psa&type=psa-value_bulk_vintage" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #e02b20; color: #ffffff;">Value Plus</td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">40</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $499</td>
                </tr>
                <tr>
                    <td>
                    <a href="?grader=psa&type=psa-value_plus" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #0c71c3; color: #ffffff;">Regular</td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">75</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $1499</td>
                </tr>
                <tr>
                    <td>
                    <a href="?grader=psa&type=psa-regular" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #000000; color:  #e09900;">Express</td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">165</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $2499</td>
                </tr>
                <tr>
                    <td>
                    <a href="?grader=psa&type=psa-express" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title'  style="background-color: #e09900;">Super Express</td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">330</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $4999</td>
                </tr>
                <tr>
                    <td>
                        <a  href="?grader=psa&type=psa-super_express" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
        } 
            elseif( isset( $_GET["grader"] ) && $_GET["grader"] == "sgc"  ){
?>
    <div class="row mx-3">
        <div class="col-12">
            <H2><a style="text-decoration: none; color: lightgray;" href='?grader=psa'>PSA Grading</a> &emsp; SGC Grading</H2>
        </div>
        <div class="col-md-3 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #0c71c3; color: #ffffff;">
                        Bulk<br>
                        <small>20+ Cards</small>
                    </td>
                </tr>
                <tr>
                    <td class="">
                        <div class="pricing">19</div>
                        <div>per card</div>
                    </td>
                </tr>
                <tr>
                    <td>DV < $499</td>
                </tr>
                <tr>
                    <td>
                    <a href="?grader=sgc&type=sgc-bulk" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>    
    </div>
<?php    
            }
        } else {
?>
    <H1 style="color: black;">Coming Soon</H1>
    <a href="/grading">Log Cards for Grading</a>
<?php 
        }
    }
?>

