1: <?php
 
        //$key = "0AhmpMSfj-J34dG1TU3NiNG5Nd0NXUVhJM0FkQ2FQQWc";
        //$key = "0An0A05lbmJePdDdaNUlCR0hKZVNUMndOTFdlWjF3Z2c";
        //$url = "http://spreadsheets.google.com/feeds/cells/$key/1/public/values";
       // $url = "https://spreadsheets.google.com/feeds/cells/".$key."/ocs/public/values?alt=rss";
        $key = "0AhmpMSfj-J34dGNvSE92Q2EyY3Z2cjZuSkZwVnlsT2c/oci";
        $url = "http://spreadsheets.google.com/feeds/cells/".$key."/public/values?alt=rss";

         echo $url;

        $ch = curl_init();
     
        // set URL and other appropriate options
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       
       // grab URL and pass it to the browser
       $google_sheet = curl_exec($ch);
       
       // close cURL resource, and free up system resources
       curl_close($ch);
    
       $doc = new DOMDocument();
       $doc->loadXML($google_sheet); 


      //print_r($doc);
       
       $nodes = $doc->getElementsByTagName("cell");
       

       $count = 0;    
       /*
       if($nodes->length > 0)
       {*/

           foreach($nodes as $node)
           { 
              $nextAdd = (9*($node->getAttribute("row")-1));

             // echo $count . ' ';
              if ($count%2 == 0) {
                echo '<br />';
                $name = $node->nodeValue;
                //echo 'Name: ' . $node->nodeValue;
              } else {
                $email = $node->nodeValue;
                $to = $node->nodeValue;
               // echo 'Email: '. $node->nodeValue;
              

              //echo '<br />';
            $subject = 'Your Elexu Account Information';    
            $message = '<html>
                          <head>
                            <title></title>
                          </head>
                          <body>
                            <p>
                              &nbsp;</p>
                            <p class="p1">
                              Hey '.$name.',</p>
                            <p class="p1">
                              Thank you for attending Elexu Creative Live! You have been successfully registered.</p>
                            <p class="p3">
                              <b>First Up...Change Your Password</b></p>
                            <p class="p3">
                              Go to <a href="http://www.elexu.com">elexu.com</a>, click &lsquo;sign in&rsquo;, input:</p>
                            <p class="p3">
                              Username: '.$email.'<br />
                              Password: password</p>
                            <p class="p3">
                              <b>Next Up...Editing Your Profile</b></p>
                            <p class="p3">
                              Tell us a bit about yourself. Give others in the community a chance to see how you shine and what makes you tick. Don&#39;t be shy. Give us something to talk about, something to ask about, something to laugh about.</p>
                            <p class="p4">
                              Thanks,<br>
                              The Elexu Team<br/>

                              Empowering People</p>
                          </body>
                        </html>
          ';
          $headers = "From: Elexu <info@elexu.com> \r\n";
          $headers .= "Reply-To: Elexu <info@elexu.com> \r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          if (@mail($email, $subject, $message, $headers)) {
            echo 'Mail sent to : ' . $name . ' <'.$email . '> - '.$typeofemail. ' -' . $subject .'<br/>';
          } else {
            echo 'Mail was not sent to : ' . $name . ' <'.$email.'> - '.$typeofemail. ' -' . $subject .'<br/>';
          }
         // echo $template;
}

            $count++;

           }
       //
    
 ?>