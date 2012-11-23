<?php
	require 'excel_reader2.php';
	require 'ElexuMailer.class.php';
	require 'mailtemplates.php';
	
	
	//$data = new ElexuMailer("data.xls");
	$data = new ElexuMailer("test.xls");
	
	$d1 = $data->getDataFromExcel();
	$total = sizeof($d1);	
	echo $total;
	//die ($total);
	
	if (!isset($_REQUEST['pg'])) {
		$pg = 1; 
	} else {
		$pg = $_REQUEST['pg'];
	}
	$per_page = 10;
	
	
	$this_page = ($pg-1) * $per_page;
	$next_page = ($pg) * $per_page;
	
	echo 'pg:' . $pg;
	echo '<br/>';
	echo 'thispag:' . $this_page;
	echo '<br/>';
	echo 'nxtpgL'. $next_page;
	echo '<br/>';
	$headers = "From: Yanet Vinals <Yanet.Vinals@elexu.org> \r\n";
	$headers .= "Reply-To: Yanet Vinals <Yanet.Vinals@elexu.org> \r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	echo $this_page . ' - '. $next_page;
	$sendmail = true;
	for ($i = $this_page;$i < $total && $i < $next_page; $i++){
		$d = $d1[$i];
		print_r($d);
		echo $subject. '<hr/>';
		//die (print_r(getMailTemplates($d['class'], $d['firstname'])));
		$name = $d['firstname'];
		$mail = getMailTemplates($d['class'], $d['firstname']);
		$subject = $mail['subject'];
		$message = $mail['template'];
		$to = $d['email'];
		
		if ($sendmail) {
			if (@mail($to, $subject, $message, $headers)) {
				echo 'Mail sent to : ' . $name . ' <'.$to.'> - '.$typeofemail. ' -' . $subject .'<br/>';
			} else {
				echo 'Mail sent to : ' . $name . ' <'.$to.'> - '.$typeofemail. ' -' . $subject .'<br/>';
			}
		}
		
		echo 'Mail sent to : ' . $name . ' <'.$to.'> - '.$typeofemail. ' -' . $subject .'<br/>';
	
	}
	echo 'Total Sent: '. $per_page * $pg . ' of '. $total;
	
	$stop = false;
	if ($total <= ($per_page * $pg)) $stop = true;
	
?>


<html><head><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<script type="text/javascript">
<!--
	$(document).ready(function(){
	   <?php 
		if (!$stop) {
	   ?>
		//alert('<?php echo ($pg+1); ?>');
		$(location).attr('href','?blah&pg=' + <?php echo ($pg+1); ?>);
		<?php
		} else {
		?>
			alert('Email has been sent to all!');
		<?php
		}
		?>
	});
-->
</script>
</head>
<body >
</body>
</html>