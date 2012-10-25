<?php
	require_once 'excel_reader2.php';
	
	class ElexuMailer {
		
		var $filename;
		var $finalData;
		var $listOfCommunities;
		
		function __construct($filename) {
			$this->filename = $filename;
		}
	
		function check_email_address($email) {
		  // First, we check that there's one @ symbol, 
		  // and that the lengths are right.
		  if (!@ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters 
			// in one section or wrong number of @ symbols.
			return false;
		  }
		  // Split it into sections to make life easier
		  $email_array = explode("@", $email);
		  $local_array = explode(".", $email_array[0]);
		  for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!@ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
			?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
			$local_array[$i])) {
				  return false;
				}
			  }
			  // Check if domain is IP. If not, 
			  // it should be valid domain name
			  if (!@ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
				$domain_array = explode(".", $email_array[1]);
				if (sizeof($domain_array) < 2) {
					return false; // Not enough parts to domain
				}
				for ($i = 0; $i < sizeof($domain_array); $i++) {
				  if(!@ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
			?([A-Za-z0-9]+))$",
			$domain_array[$i])) {
				return false;
			  }
			}
		  }
		  return true;
		}
		function asort2d($records, $field, $reverse=false) {
		// Sort an array of arrays with text keys, like a 2d array from a table query:
		  $hash = array();
		  foreach($records as $key => $record) {
			$hash[$record[$field].$key] = $record;
		  }
		  ($reverse)? krsort($hash) : ksort($hash);
		  $records = array();
		  foreach($hash as $record) {
			$records []= $record;
		  }
		  return $records;
		} // end function asort2d
		function getDataFromExcel() {
			$this->finalData 			= array();
			$this->listOfCommunities 	= array();
			
			$data = new Spreadsheet_Excel_Reader($this->filename);
			$datagrid = $data->sheets[0][cells];
			foreach ($datagrid as $row) {
				if ($row[17] != '' && $this->check_email_address($row[17]))
					array_push($this->finalData, array('contact' => $row[4], 'typeofemail' => $row[8],'community' => $row[9], 'firstname' => $row[11], 'surname' => $row[12], 'email' => $row[17]));
					
					if (!in_array($row[9], $this->listOfCommunities)) {
						array_push($this->listOfCommunities, $row[9]);
					}
			}
			
			return $this->asort2d($this->finalData, 'community');
		}
		function getData() {
			if ($finalData == null) {
				return getDataFromExcel();
			} else {
				return $this->finalData;
			}
		}
		
	}
	
	$data = new ElexuMailer("Event Contact Master List.xls");
	
	foreach ( $data->getDataFromExcel() as $d ){
		
		$name 	= $d['firstname'];
		$to		= $d['email'];
		$typeofemail		= $d['typeofemail'];

		$subject = 'Elexu Creative Live!';

		$headers = "From: <Yanet Vinals> " . strip_tags('Yanet.Vinals@elexu.org') . "\r\n";
		$headers .= "Reply-To: <Yanet Vinals>". strip_tags('Yanet.Vinals@elexu.org') . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$oldcontacts_attended_message = '
		Hey '.$name.',<br/>

		<p>
			Elexu Creative Live! As you remember our last Creative Live event at Canary Wharf
			was such a success, we are throwing a winter event on November 15th in Leicester Square. 
			This time we will have more great entertainment including live music, short films, photographers, 
			and it’s at a great location in the heart of London!
		</p><p>
		It is free if you RSVP by November 12th, otherwise it’s £2 at the door. Plus, when you RSVP we will give you free invite only membership to www.elexu.com and enter you into the raffle drawing!
		</p><p>
		To RSVP – send an email to info@elexu.com with the subject line RSVP. Then just write “RSVP Creative Live II – First name, Last name and email address.” If your friend referred you, throw them in there too. Easy peasy.
		</p><p>
		Feel free to bring along friends, family, co-workers, anyone (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you  know would like to perform or show off their work as a part of our entertainment, email us by October 30th the contact info or if it’s not you, have them email us directly.
		</p><p>
		The Details…<br/>
		Elexu Creative Live<br/>
		Live Music, Film, Art, and More!!<br/>
		November 15th 6:00-9:30 pm<br/>
		Verve Bar - 1 Upper St Martin\'s Lane  City of London, WC2H 9NY<br/>
		</p><br/>
		<img src="http://qambar.agiletech.ie/emailer/photo.jpg" alt="" />
		
		';
		
		
		$oldcontacts_notattended_message = '<p>
	Hey '.$name.'</p>
<p>
	&nbsp;</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	As you remember we recently hosted our Elexu Creative Live event this summer at Canary Wharf. We are sorry you were not able to attend, but the good news is that it was such a success; we are throwing an autumn event on <strong>November 15<sup>th</sup></strong> in Leicester Square. This time we will have more great entertainment <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="file:///C:/Users/Mysterious%20Raza/Desktop/vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	&nbsp;</p>
<p>
	It is <strong>free if you RSVP</strong> by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	&nbsp;</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Email Address</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong></p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	&nbsp;</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	&nbsp;</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a></p>
<p>
	<em>Live Music, Film, Art, and More!!</em></p>
<p>
	November 15<sup>th</sup> 6:00-9:30 pm</p>
<p>
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p><br/>
	<img src="http://qambar.agiletech.ie/emailer/photo.jpg" alt="" />
';
	switch(trim($typeofemail)) {
		case 'Creative Live I Invites, Not Attended':
			$message = $oldcontacts_attended_message;
		break;
		case 'Creative Live I Attended':
			$message = $oldcontacts_notattended_message;
		break;
		
	}

		if (mail($to, $subject, $message, $headers)) {
			echo 'Mail sent to : ' . $name . '<'.$to.'>';
		}
	}
	
	
?>
