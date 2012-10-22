<?php
	
	$listOfEmails = array (
						"Qambar Raza,qambar.raza@elexu.org"
						,"Christy Young,christy.young@elexu.com"
					);
	
	/*
	$listOfEmails = array (
						"Qambar Raza" => "qambar.raza@elexu.org"
						,"Christy Young" => "christy.young@elexu.com"
			//			,"Christy Young" => "christyyoung@mac.com"
			//			,"Info" => "info@elexu.com"
			//			,"Sam Watson" => "sam.watson@elexu.org"
			//			,"Sam Watson" => "sam.watson@elexu.org"
					);
	*/
	//print_r($listOfEmails); 
	
	
	foreach ($listofEmails as $data) {
		
		list($name, $email) = split(',', $data);
		
		$to = $email;

		$subject = 'Elexu Creative Live!';

		$headers = "From: " . strip_tags($email) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($email) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$message = '
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
		November 15th 6:30-9:30 pm<br/>
		Verve Bar - 1 Upper St Martin\'s Lane  City of London, WC2H 9NY<br/>
		</p>
		';
		if (mail($to, $subject, $message, $headers)) {
			echo 'Mail sent to : ' . $name . '<'.$to.'>';
		}
	}
	

?>