<?php 

global $wpdb;

$consignment = $this->wpdb->get_results ( "
SELECT * 
FROM consignment
where user_id = " . get_current_user_id() . " 
and status = 'shipped'
order by order_id desc, id desc
"
);

?>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Year</th>
                <th>Brand</th>
                <th>Card Number</th>
                <th>Player Name</th>
                <th>Attribute S/N</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center py-5">
                    Empty
                </td>
            </tr>
        </tbody>
    </table>
</div>