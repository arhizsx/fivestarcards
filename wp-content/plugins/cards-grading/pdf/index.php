<?php 

require_once("./wp-load.php");
require_once ( '../dompdf/autoload.inc.php');
use Dompdf\Dompdf; 


// instantiate and use the dompdf class
$dompdf = new Dompdf();

$args = array(
    'post_type' => 'cards-grading-chk',
    'post__in' => array(2244)
);

$posts = get_posts($args);

$dompdf->loadHtml("test");

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();


?>