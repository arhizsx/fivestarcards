<style>
    h3 {
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
</style>

<?php 


    if( isset( $_GET["type"] ) ){

        global $wpdb;

        $consignment = $this->wpdb->get_results ( "
        SELECT * 
        FROM consignment
        where user_id = " . get_current_user_id() . " 
        and status = 'logged'
        order by id desc
        "
        );        

        switch( $_GET["type"] ){

            case "psa-value_bulk": 

                $max_dv = 499;
                $per_card = 19;
                break;

            case "psa-value_plus": 

                $max_dv = 499;
                $per_card = 40;
                break;

            case "psa-regular": 

                $max_dv = 1499;
                $per_card = 75;
                break;

            case "psa-express": 

                $max_dv = 2499;
                $per_card = 165;
                break;

            case "psa-super_express": 

                $max_dv = 4999;
                $per_card = 330;
                break;

            default: 
                $max_dv = 0;
                $per_card = 0;
            
        }
?>
    <a href="/my-account/grading/" class="btn btn-sm btn-primary mb-3 ">Back to Grading Types</a>

<button class="btn btn-sm btn-success mb-3 ebayintegration-btn" data-action="show_log_consign_modal">
    Log Card
</button>
<button class="btn btn-sm btn-primary mb-3  ebayintegration-btn" data-action="show_ship_batch_modal">
    Ship Cards
</button>
<div class="table-responsive d-none d-lg-block">
    <table class="table table-sm table-bordered" id="new_consignment">
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th>Year</th>
                <th>Brand</th>
                <th>Player Name</th>
                <th class="text-end">Card Number</th>
                <th class="text-end">Attribute S/N</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( count( $consignment ) == 0 ){
            ?>
            <tr class="empty_consignment">
                <td colspan="7" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ){

                        $data = json_decode( $card->data, true );

            ?>
            <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                <td>
                    <a class='text-danger  ebayintegration-btn' data-action="removeConsignedCardRow"  data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#'>
                        <i class='fa-solid fa-lg fa-xmark'></i>
                    </a>
                </td>
                <td><?php echo $data["year"] ?></td>
                <td><?php echo $data["brand"] ?></td>
                <td><?php echo $data["player_name"] ?></td>
                <td class='text-end'><?php echo $data["card_number"] ?></td>
                <td class='text-end'><?php echo $data["attribute_sn"] ?></td>
            </tr>
            <?php 
                    }
                }
            ?>  
        </tbody>
    </table>
</div>
<div class="d-lg-none pb-2">
    <table class="table table-sm table-bordered" id="new_consignment_mobile">
        <thead>
            <tr>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( count( $consignment ) == 0 ){
            ?>
            <tr class="empty_consignment">
                <td class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ){
            ?>
            <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                <td>
                    <div class='w-100 p-0 text-end' style='position: relative;'>
                        <a class='text-danger ebayintegration-btn' data-action="removeConsignedCardRow" data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#' style='position: absolute; right: 0px;'>
                            <i class='fa-solid fa-xl fa-xmark'></i>
                        </a>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Player</div>
                        <div class='col-sm-8'>
                            <?php echo $data["player_name"] ?>								
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
                </td>
            </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>
</div>

<?php
    } else {
?>

<div class="row mx-3">
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
                <a href="?type=psa-value_bulk" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                <a href="?type=psa-value_plus" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                <a href="?type=psa-regular" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                <a href="?type=psa-express" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                    <a  href="?type=psa-super_express" class="btn btn-sm btn-outline-primary">Log Cards</a>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php 
    }
?>