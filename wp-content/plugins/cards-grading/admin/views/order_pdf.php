<?php 

require_once (plugin_dir_path( __FILE__ ) . 'dompdf/autoload.inc.php');
use Dompdf\Dompdf; 

global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));

$html = file_get_contents($current_url);

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

$dompdf->render();

$dompdf->stream('title.pdf');

?>
</div>