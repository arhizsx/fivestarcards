<?php 

require_once ( '../dompdf/autoload.inc.php');
use Dompdf\Dompdf; 
 


// instantiate and use the dompdf class
$dompdf = new Dompdf();


get_header(); 
$posts = get_posts();
var_dump($posts[0]->ID);


$dompdf->loadHtml($post->post_content);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();


?>