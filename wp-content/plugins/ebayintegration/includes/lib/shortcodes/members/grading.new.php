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
    <a href="/my-account/grading/" class="btn btn-sm btn-outline-primary">Back to Grading Types</a>
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