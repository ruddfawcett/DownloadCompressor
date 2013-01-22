<?php
	if (array_key_exists('url',$_POST)) {
		downloadFile($_POST['url']);
	}
	else {
		echo "<div class='alert alert-error'><strong>Error! </strong>You have to enter a URL for Download Compressor to work!</div>";
	}
		
		function downloadFile ($url) {
			$curl = curl_init($url);
				$fileName = basename($url);
				$fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
				if ($fileExt == null) {
					echo "<div class='alert alert-error'><strong>Error! </strong>You must enter a file with an extension for Download Compressor to work!</div>";
					exit();
				}
				
				$filePath = 'temp/' . $fileName;
				$file = fopen($filePath, 'wb');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FILE, $file);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_exec($curl);
				curl_close($curl);
			fclose($file);
			
			$FileSize = (filesize($filePath) * .0009765625) * .0009765625;
			
			 $zipName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);		 
			 $zip = new ZipArchive();
			 
			 $zipFile = "temp/" . $zipName . ".zip";
			 if($zip->open($zipFile, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) {
			   $zip->addFile($filePath);
			   $zip->close();
			   $zipStatus = 1;
			   $ZipSize = (filesize($zipFile) * .0009765625) * .0009765625;
			 }
			 else {
			 	$zipStatus = 0;
			 	echo "<div class='alert alert-error'><strong>Error! </strong>Unable to zip the file!</div>";
			}
			
			$newPath = "files/" . $zipName . ".zip";
			
			if (copy($zipFile,$newPath)) {
  				unlink($filePath);
  				unlink($zipFile);
  				$moveStatus = 1;
			}
			 else {
			 	$moveStatus = 0;
			 	echo "<div class='alert alert-error'><strong>Error! </strong>Unable to move the archive!</div>";
			}
			
			$difference = round($FileSize,2) - round($ZipSize,2);
			
			if ($zipStatus == 1 && $moveStatus == 1) {
				echo "<div class='alert alert-success'><strong>Success! </strong>Here's a URL to your file: <a href='http://domain.com/DownloadCompressor/" . $newPath . "' target='_blank'>" . "http://domain.com/DownloadCompressor/" . $newPath . "</div>";
			}
		 }
?>