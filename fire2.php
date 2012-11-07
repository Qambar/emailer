<?php


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
				if ($row[17] != '' && $this->check_email_address($row[17]) && $row[7] == 'No')
					array_push($this->finalData, array('rsvpmailsent' => $row[7], 'contact' => $row[4], 'typeofemail' => $row[8],'community' => $row[9], 'firstname' => $row[11], 'surname' => $row[12], 'email' => $row[17]));
					
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
	
	$data = new ElexuMailer("live.xls");
	
	
	//foreach ( $data->getDataFromExcel() as $d ){
	$d1 = $data->getDataFromExcel();
	$total = sizeof($d1);
	//echo $total;
	echo $this_page . ' - '. $next_page;
	for ($i = $this_page;$i < $total && $i < $next_page; $i++){
		$d = $d1[$i];
		$name 	= trim($d['firstname']);
		$to		= $d['email'];
		$typeofemail		= $d['typeofemail'];
		
		
		$subject = 'BUG :: ' . $typeofemail . ' - not working';
		$message = $name; 

		$signature = '<strong>Yanet Vinals</strong><br />
	<span style="color:#808080;">Events</span>
<p>
	<img alt="http://media-cdn.pinterest.com/upload/53550683039274336_2ANJg2lM.jpg" height="54" src="http://media-cdn.pinterest.com/upload/53550683039274336_2ANJg2lM.jpg" width="109" />&nbsp;<br />
	Elexu Ltd <strong>| </strong>52 A Berwick St <strong>|</strong><br />
	London <strong>|</strong> W1F 8SL <strong>|</strong> United Kingdom <strong>|</strong><br />
	P <strong>|</strong> 020 7734 8294 <strong>|</strong><br />
	E <strong>|</strong> <a href="mailto:yanet.vinals@elexu.org">yanet.vinals@elexu.org</a><br />
	W<strong>|</strong> <a href="http://www.elexu.com/" target="_blank">www.elexu.com</a><br />
	<br />
	<span style="font-size:10px;"><span style="color:#808080;">Registered in England 07472860</span><br />
	<br />
	<span style="color:#808080;">Disclaimer Notice: The message and attachment(s) contained in this e-mail are intended for the named recipient(s) only. It may contain privileged or confidential information or information which is exempt from disclosure under the applicable laws. If you are not the intended recipient, you must not read, print, retain, copy, distribute, forward or take any action in reliance on it or its attachment(s). If you have received or have been forwarded this e-mail in error, please notify us immediately by return e-mail or&nbsp;e-mail&nbsp;</span><a href="mailto:info@elexu.com" target="_blank"><span style="color:#808080;">info@elexu.com</span></a><span style="color:#808080;">&nbsp;and delete this message from the computer in its entirety. Internet communications cannot be guaranteed to be secure and error-free as the information could be intercepted, corrupted, lost, arrive late or contain viruses. The sender and this Company therefore do not accept any liability or responsibility of whatsoever nature in the context of this message and its attachment(s) which arises as a result of Internet transmission. Opinions, conclusions, representations, views and such other information in this message that do not relate to the official business of this Company shall be understood as neither given nor endorsed by it.</span></span></p>
';

		$headers = "From: Yanet Vinals <Yanet.Vinals@elexu.org> \r\n";
		$headers .= "Reply-To: Yanet Vinals <Yanet.Vinals@elexu.org> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$oldcontacts_attended_message = '
		<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	As you remember we recently hosted our Elexu Creative Live event this summer at Canary Wharf. We are sorry you were not able to attend, but the good news is that it was such a success; we are throwing an autumn event on <strong>November 15<sup>th</sup></strong> in Leicester Square. This time we will have more great entertainment <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP</strong> by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a></p>
<p>
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>


		<p>	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
		<p>
		We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.
</p>
<br/>
Kind regards,<br/>
'.$signature.'

		';
		
		
		$oldcontacts_notattended_message = '<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	As you remember we recently hosted our Elexu Creative Live event this summer at Canary Wharf. We are sorry you were not able to attend, but the good news is that it was such a success; we are throwing an autumn event on <strong>November 15<sup>th</sup></strong> in Leicester Square. This time we will have more great entertainment <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP</strong> by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	<br />
	Kind regards,</p>
<p>
	'.$signature.'</p>

';

$empowering_photo_message = '<p>
	&nbsp;</p>
<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	Elexu &ndash; the new online competition site is offering a live event in London, Elexu Creative Live! As you know from our <strong>Empowerment Photo Competition</strong>, we are all about our community, so this autumn we are throwing our second live event on <strong>November 15<sup>th</sup></strong> in Leicester Square. We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a></p>
<p>
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>
<p>	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$style_it_soho_message = '<p>
	&nbsp;</p>
<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	Elexu &ndash; the new online competition site is offering a live event in London, Elexu Creative Live! As you know from <strong>Style It Soho Competition</strong>, we are all about our community, so this autumn we are throwing our second live event on <strong>November 15<sup>th</sup></strong> in Leicester Square. It is at our event that we will <strong>announce the winner of the competition LIVE</strong>! So bring friends and family to check out your great work.</p>
<p>
	We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY<br />
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	&nbsp;</p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>

';
$platform_members_message = '
<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	Elexu &ndash; your new online competition site is hosting a live event in London, Elexu Creative Live! As you know from our site, we are all about our community, so this autumn we are throwing our second live event on <strong>November 15<sup>th</sup> </strong>in Leicester Square. We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>),</strong> and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Email <a href="mailto:info@elexu.com?subject=RSVP%20Creative%20Live%20II">info@elexu.com</a> with &ndash; First name, Last name and email address &ndash; If your friend referred you, throw them in there too. Easy peasy.</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$current_staff_message = '<p>
	&nbsp;</p>
<p>
	Hey '.$name.',</p>
<p>
	<strong><em>Here is the info to share with friends, family and others about Creative Live II, though please make sure to add any new contacts to the events master list.</em></strong></p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	Elexu &ndash; your new online competition site is hosting a live event in London, Elexu Creative Live! As you know from our site, we are all about our community, so this autumn we are throwing our second live event on <strong>November 15<sup>th</sup> </strong>in Leicester Square. We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>),</strong> and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	&nbsp;</p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	Yanet</p>
';
$intern_alumni = '<p>
	&nbsp;</p>
<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	Well life at Elexu is certainly not the same without you, and we miss you! That&rsquo;s why we want to invite you to come catch up with us at our next Elexu Creative Live on <strong>November 15<sup>th</sup> </strong>in Leicester Square. We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>),</strong> and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Email <a href="mailto:info@elexu.com?subject=RSVP%20Creative%20Live%20II">info@elexu.com</a> with &ndash; First name, Last name and email address &ndash; Easy peasy.</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	Elexu Creative Live<br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you all soon!</p>
<p>
	Miss you guys!</p>
<p>
	'.$signature.'</p>
';
$space_contacts_message = '<p>
	Hey '.$name.',</p>
<p>
	You may remember, earlier this year I stopped by the open house night you had with Space. It was lovely speaking to you and learning about your work. We are close to launching the [ SPACE ] site for your artistic community, so we will have updates for you on that shortly.</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	In the meantime, I wanted to invite you to an artistic showcase. This autumn we are throwing our second live event on <strong>November 15<sup>th</sup></strong> in Leicester Square. We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>, otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment, email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$coporate_message = '<p>
	Dear '.$name.',</p>
<p>
	Firstly, we wanted to thank you for support Elexu, and as you may already know&nbsp;this summer we proudly launched our invite only Beta Stage site <a href="http://www.elexu.com/">www.elexu.com</a> (social networking with exciting online competitions). Our innovative online community is&nbsp;designed for people who have aspirations as artists, entrepreneurs and activists, but find it difficult to reach these dreams.</p>
<p>
	That&rsquo;s why we are hosting <strong>our </strong><strong>second creative showcase on November 15<sup>th</sup></strong> in Leicester Square.</p>
<p>
	The evening will be fantastic. Not only&nbsp;will you enjoy amazing entertainment - including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>- but you can also learn more about Elexu and how we help&nbsp;<a href="http://www.youtube.com/watch?v=0K05x9XIOGA" target="_blank">brands, businesses and charities accelerate their performance using social media.</a></p>
<p>
	<strong><u>RSVP and </u></strong><a href="http://www.facebook.com/events/456811061037535/"><strong>Bring a Friend</strong></a></p>
<p>
	If your colleague or friend mentions your name in the RSVP we will make sure to enter you in the raffle drawing twice. Also, if someone you know would like to perform as a part of our entertainment, please email us by October 30<sup>th</sup> their contact info or have them email us directly.</p>
<p>
	<strong><em>Join the Guest List</em></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	I hope to see you!</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$entertainment_performedcl1 = '<p>
	Hey '.$name.',</p>
<p>
	<strong>Elexu Creative Live!</strong><br />
	As you remember our last Creative Live event at Canary Wharf was such a success thanks to you, that now we are throwing an autumn event on <strong>November 15<sup>th</sup></strong> in Leicester Square. This time we will have more great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	<strong>Chance to Shine</strong><br />
	If you are interested in performing or showing off your work again as a part of our entertainment, email us by October 30<sup>th</sup> and we&rsquo;ll get in touch about our opportunities.</p>
<p>
	<strong>RSVP and Bring a Friend</strong><br />
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>, otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to www.elexu.com and enter you into the raffle drawing! Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice.</p>
<p>
	Email <a href="mailto:info@elexu.com?subject=RSVP%20Creative%20Live%20II">info@elexu.com</a> with &ndash; First name, Last name and email address &ndash; If your friend referred you, throw them in there too. Easy peasy.</p>
<p>
	<strong>The Details&hellip;</strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$entertainment_didnotperformc1 = '<p>
	Hey '.$name.',</p>
<p>
	<strong>Elexu Creative Live!</strong><br />
	Elexu Creative Live! As you may remember our last Creative Live event at Canary Wharf was such a success, we are throwing an autumn event on <strong>November 15<sup>th</sup></strong> in Leicester Square. This time we will have more great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	<strong>Chance to Shine</strong><br />
	If you missed out on your chance to perform last time, but are interested in showing off your work as a part of our entertainment for this event, email us by October 30<sup>th</sup>&nbsp; and we&rsquo;ll get in touch about our opportunities.</p>
<p>
	<strong>RSVP and Bring a Friend</strong><br />
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>, otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing! Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice.</p>
<p>
	Email <a href="mailto:info@elexu.com?subject=RSVP%20Creative%20Live%20II">info@elexu.com</a> with &ndash; First name, Last name and email address &ndash; If your friend referred you, throw them in there too. Easy peasy.</p>
<p>
	<strong>The Details&hellip;</strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$new_audience = '<p>
	Hey '.$name.',</p>
<p>
	<strong>Elexu Creative Live!</strong><br />
	Elexu &ndash; the new online competition site is hosting a <strong>live event in London</strong>! If you are a creative individual or just want to support young people with a dream, come on down for a great night of entertainment at Elexu Creative Live!</p>
<p>
	On <strong>November 15<sup>th</sup></strong> in Leicester Square we will have some fantastic acts including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	<strong>RSVP and Bring a Friend</strong><br />
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>, otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment, email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong>The Details&hellip;</strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$bloggers = '<p>
	Hey '.$name.',</p>
<p>
	I&rsquo;ve been following your blog for a while now and I thought you or some of your other fans might be interested in attending our Elexu Creative Live, on <strong>Thursday, November 15<sup>th</sup></strong>, 6:00&ndash; 9:30 pm. We wanted to host an event for the creative community to mix, mingle, and enjoy some of each other&rsquo;s work.</p>
<p>
	<strong>Elexu Creative Live!</strong><br />
	Our event will have some fantastic acts including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London! It also provides an opportunity to find out more about Elexu (your new online competition site) and to meet other creative people.</p>
<p>
	<strong>RSVP and Bring a Friend</strong><br />
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>, otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment, email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong>The Details&hellip;</strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';

$community_projects = '
<p>
	Hey '.$name.',</p>
<p>
	<strong><u>Elexu Creative Live!</u></strong></p>
<p>
	Elexu &ndash; the new online competition site is offering a live event in London, Elexu Creative Live! As you know from our site, we are all about our community, so this autumn we are throwing our second live event on <strong>November 15<sup>th</sup></strong> in Leicester Square. We will have great entertainment including <strong>live music (</strong><a href="http://soundcloud.com/search?q%5Bfulltext%5D=colourshop"><strong>Colourshop band</strong></a><strong>), short films (</strong><a href="http://vimeo.com/22365180"><strong>Will Berman</strong></a><strong>), photography (</strong><a href="http://pinterest.com/pin/439452876109112393/"><strong>Claudia Iasella</strong></a><strong>)</strong>, and it&rsquo;s at a great location in the heart of London!</p>
<p>
	It is <strong>free if you RSVP </strong>by November 12<sup>th</sup>; otherwise it&rsquo;s &pound;2 at the door. Plus, when you RSVP we will give you free invite only membership to <a href="http://www.elexu.com/">www.elexu.com</a> and enter you into the raffle drawing!</p>
<p>
	<strong><u>RSVP and Bring a Friend</u></strong></p>
<p>
	Visit&nbsp;<strong><a href="http://www.elexu.com/invite" target="_blank">www.elexu.com/invite</a></strong>&nbsp;and&nbsp;<a href="http://www.elexu.com/invite" target="_blank"><strong>sign up online</strong></a>&nbsp;to reserve your place. Remember to include your:</p>
<p style="margin-left:47.25pt;">
	&middot;&nbsp;&nbsp;Name<br />
	&middot;&nbsp;&nbsp;Email Address<br />
	&middot;&nbsp;&nbsp;VIP Access Code&nbsp;<strong>CRE8LIV2</strong><br />
	&middot;&nbsp;&nbsp;Biggest Aspiration</p>
<p>
	Feel free to <a href="http://www.facebook.com/events/456811061037535/"><strong>share this invite</strong></a> and bring others along (though have them RSVP too if they want to save a few quid). Plus, if they mention your name in the RSVP we will make sure to enter you in the raffle drawing twice. Lastly, if you or someone you know would like to perform or show off their work as a part of our entertainment email us by October 30<sup>th</sup> the contact info or if it&rsquo;s not you, have them email us directly.</p>
<p>
	<strong><u>The Details&hellip;</u></strong></p>
<p>
	<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a><br />
	<em>Live Music, Film, Art, and More!!</em><br />
	November 15<sup>th</sup> 6:00-9:30 pm<br />
	Verve Bar - 1 Upper St Martin&#39;s Lane&nbsp;City of London, WC2H 9NY</p>
<p>
	<a href="http://elexu.com/blog/elexu-creative-live-success/"><strong><img alt="" border="0" height="234" src="http://qambar.agiletech.ie/emailer/photo.jpg" width="353" /></strong></a></p>
<p>
	&nbsp;</p>
<p>
	We look forward to seeing you soon! Let us know if you have any questions via email or by phone 020 7734 8294.</p>
<p>
	Kind regards,</p>
<p>
	'.$signature.'</p>
';	

$address = 'Hey '.$name.',';
if ($name == '') {
	$address = 'Hey,';
}
$non_rsvp_subject = 'Reminder – Elexu Creative Live - November 15th';
$non_rsvp_message = '<table border="0" cellpadding="0" cellspacing="10" style="width: 700px; ">
			<tbody>
				<tr>
					<td width="70%">
						<p>
							'.$address.'</p>
						<p>
							<a href="http://www.facebook.com/events/456811061037535/">Elexu Creative Live</a></p>
						<p>
							We noticed you didn&rsquo;t get a chance to register for the&nbsp;<a href="http://www.facebook.com/pages/elexu/213643148651735?sk=events">Elexu Creative Live event on November 15th</a>. Perhaps we forgot to mention the&hellip;<br /><br />
							&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Live Music, Films</strong>,&nbsp;<strong>Photography</strong>, and more<strong>!</strong><br />
							&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>1/2</strong>&nbsp;<strong>price Drinks&nbsp;</strong>and Food til 8<br />
							&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fun&nbsp;<strong>Photo Booth</strong></p>
						<p>
							<strong>RSVP / Signup&nbsp;</strong>and get in for<strong>&nbsp;FREE</strong><br />
							As tickets are free now until November 12<sup>th</sup>&nbsp;(otherwise &pound;2 at the door) we wanted to make sure we got you in for free&hellip;So&nbsp;<a href="http://www.facebook.com/pages/elexu/213643148651735?sk=app_286581271459486">register your RSVP here</a>.</p>
						<p>
							<strong>Can&rsquo;t Make It?</strong><br />
							Join our&nbsp;<a href="http://elexu.com/contact">Newsletter</a>&nbsp;or check out our latest competition -&nbsp;<a href="http://elexu.com/prototype/endowment/129-secret-london">Secret London</a>&nbsp;- where every Londoner is invited to share which places are special to you in London, why. Then vote for the winner of two tickets for the hit, new musical Loserville. For access&nbsp;<a href="http://www.facebook.com/pages/elexu/213643148651735?sk=app_286581271459486">register here</a>.</p>
						


					</td>
					<td valign="top">
						
						<p>
							<a href="http://www.facebook.com/events/456811061037535/"><img src="http://assets5.pinimg.com/upload/53550683040680999_yYKJc2ZQ.jpg" width="300px;" /></a></p>
					</td>
				</tr>
			</tbody>
		</table>
<p>
	Looking forward to seeing you then!</p>
<p>
	Best,</p>
<p>
	'.$signature.'</p>
';
$sendmail = true;

$message = $non_rsvp_message;
$subject = $non_rsvp_subject;
	if ($sendmail) {
		if (@mail($to, $subject, $message, $headers)) {
			echo 'Mail sent to : ' . $name . ' <'.$to.'> - '.$typeofemail. ' -' . $subject .'<br/>';
		} else {
			echo 'Mail sent to : ' . $name . ' <'.$to.'> - '.$typeofemail. ' -' . $subject .'<br/>';
		}
	}
		
		//echo 'Mail sent to : ' . $name . ' <'.$to.'> - '.$typeofemail. ' -' . $subject .'<br/>';
	}
	echo 'Total Sent: '. $per_page * $pg . ' of '. $total;
	
	$stop = false;
	if ($total <= ($per_page * $pg)) $stop = true;
	
?>
<html><head><script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

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