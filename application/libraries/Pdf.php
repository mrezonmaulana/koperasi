<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Pdf
{
    function createPDF($html, $filename='', $download=TRUE, $paper='A5', $orientation='portrait'){
        $dompdf = new Dompdf;
        $dompdf->load_html($html);
        $dompdf->set_paper(array(0, 0, 164.44, 842.07), $orientation);
        $dompdf->render();
        if($download)
            $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename.'.pdf', array('Attachment' => 0));
    }
}
?>
