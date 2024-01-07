<?php 

require_once (plugin_dir_path( __FILE__ ) . 'dompdf/autoload.inc.php');
use Dompdf\Dompdf; 

global $wp;
$current_url = home_url();

$html = "TEST";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

$dompdf->render();

return $dompdf->stream('title.pdf');

?>
</div>