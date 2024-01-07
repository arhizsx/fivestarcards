<?php 

require_once ( '../dompdf/autoload.inc.php');
use Dompdf\Dompdf; 


// instantiate and use the dompdf class
$dompdf = new Dompdf();


$post = get_post(2244);

$dompdf->loadHtml($post->post_content);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();


?>