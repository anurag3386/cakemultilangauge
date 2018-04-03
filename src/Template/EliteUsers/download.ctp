<?php use Cake\Routing\Router; ?>
<?php
	//if(isset($_REQUEST['apikey'])) {
		if(isset($OrderId)) {
			$OrderId = base64_decode(trim($OrderId));
			
			$ReportFileName = $_SERVER['DOCUMENT_ROOT'].'/webroot/reports/var/spool/'.$OrderId.'.bundle.pdf'; //'/home/astronew/
			$FileName = sprintf("%d-HoroscopeReport.pdf", $OrderId);
 
			if(file_exists($ReportFileName)) {
				header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($ReportFileName)) . ' GMT');
				header('Accept-Ranges: bytes');  // For download resume
				header('Content-Length: ' . filesize($ReportFileName));  // File size
				header('Content-Type: application/pdf');  // Change this mime type if the file is not PDF
				header('Content-Disposition: attachment; filename=' . $FileName);  // Make the browser display the Save As dialog
				
				ob_clean();
				set_time_limit(0);
				readfile($ReportFileName);
				exit;
			} else {
				echo "Invalid Order request";
			}
		} else {
			echo "Invalid request token.";
		}
	/*} else {
		echo "Invalid Api Key. Please provide valid Api Key.";
	}*/
?>