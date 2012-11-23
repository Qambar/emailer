<?php
	function getMailTemplates($class, $firstname) {
	//echo  $class . ' - '. $firstname;
		//die ();
		switch ($class) {
		
			case 'attended':
				return array('subject' => 'Elexu Creative Live! Autumn Event', 'template' => '<p>
					Hey '.$firstname.',</p>
				<p>
					<strong>Elexu Creative Live</strong></p>
				<p>
					We just wanted to reach out and say how much we appreciated you coming out to Elexu Creative Live 2 on 15<sup>th</sup> November. &nbsp;We hope you enjoyed it as much as we did and we&rsquo;ll look for you at the next one in the New Year (more details to come).<br />
					<strong>Photo Competition from Event</strong></p>
				<p>
					Don&rsquo;t worry! Just because the event is over, Elexu is still here to help. You can check out our latest competition &ldquo;Aspirant Funky Fun Photos&rdquo; on <a href="http://www.elexu.com/">www.elexu.com</a>. Plus, you can get your friends on the site with <strong>VIP code CRE8LIV2</strong> to vote for you!</p>
				<p>
					Elexu will be posting fun photos from the event on our <a href="http://elexu.com/blog/creative-live-2/">Blog</a>, <a href="http://pinterest.com/elexu/">Pinterest</a>, <a href="http://www.facebook.com/pages/elexu/213643148651735">Facebook</a>, and <a href="http://elexu.com/contact.html">Newsletter</a>, so be sure to stay tuned for those.</p>
				<p>
					Looking forward to seeing you online and at our next event in the New Year.</p>
				<p>
					Until next time,<br />
					The Elexu Team</p>');
			break;
			case 'not attended': 
				return array('subject' => 'Elexu Creative Live! Autumn Event', 'template' => '<p>
			Hey '.$firstname.',</p>
		<p>
			We are sorry you were not able to attend the event this time, but we hope to see you at the next. We will be posting fun photos from the event there on our <a href="http://elexu.com/blog/creative-live-2/">Blog</a>, <a href="http://pinterest.com/elexu/">Pinterest</a>, <a href="http://www.facebook.com/pages/elexu/213643148651735">Facebook</a>, and <a href="http://elexu.com/contact.html">Newsletter</a>, so be sure to check them out.</p>
		<p>
			<strong>Elexu.com - Secret London</strong><br />
			In the meantime, you can still check out our new competition - <a href="http://elexu.com/prototype/endowment/129-secret-london">Secret London</a> - a competition where every Londoner is invited to participate. The mission is to discover new places and to win a great prize along the way&hellip; All you have to do is tell us which places are special to you in this city, take a picture of the place and tell us why this place is special to you. The winner gets two tickets for the hit new musical Loserville.</p>
		<p>
			To access the Elexu competition and platform, just head to <a href="http://www.elexu.com/invite">www.elexu.com/invite</a> and enter your <strong>VIP code CRE8LIV2</strong>. Then create a profile and you&rsquo;re off. Join the creative community assembling on the Elexu platform.</p>
		<p>
			We look forward to seeing you online, and hopefully at our next event!</p>
		<p>
			Best,<br />
			The Elexu Team</p>');
			break;
			case 'entertainment': 
				return array('subject' => 'Elexu Creative Live! Autumn Event', 'template' => '<p>
			Hey '.$firstname.',</p>
		<p>
			<strong>Elexu Creative Live</strong><br />
			We just wanted to reach out and say how much we appreciated you great performance on Elexu Creative Live on 15<sup>th</sup> November. We hope you enjoyed it as much as we did and we&rsquo;ll look for you at the next one in the New Year (more details to come).</p>
		<p>
			<strong>Photo Competition from Event</strong><br />
			Don&rsquo;t worry! Just because the event is over, Elexu is still here to help. You can check out our latest competition &ldquo;Aspirant Funky Fun Photos&rdquo; on <a href="http://www.elexu.com/">www.elexu.com</a>. Plus, you can get your friends on the site with <strong>VIP code CRE8LIV2</strong> to vote for you!</p>
		<p>
			We will be posting fun photos from the event on our <a href="http://elexu.com/blog/creative-live-2/">Blog</a>, <a href="http://pinterest.com/elexu/">Pinterest</a>, <a href="http://www.facebook.com/pages/elexu/213643148651735">Facebook</a>, and our <a href="http://elexu.com/contact.html">Newsletter</a>, so be sure to stay tuned for those.</p>
		<p>
			<strong>Music Eve Competition</strong><br />
			There is another competition for songwriters that are tired of keeping their songs in a drawer. Upload now your original song and get the chance <strong>to win two tickets for a cool concert in London</strong><strong>.</strong></p>
		<p style="margin-left:36.0pt;">
			<u>It&rsquo;s very easy to join:</u><br />
			- visit <a href="http://www.elexu.com/live/Music-Eve">www.elexu.com/live/Music-Eve</a><br />
			- upload your original song</p>
		<p>
			Looking forward to seeing you online and at our next event in the New Year.</p>
		<p>
			Until next time,<br />
			The Elexu Team</p>');
			break;
		
		}
	}

?>