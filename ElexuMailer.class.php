<?php
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
			$firstName = trim($row[1]);
			//$lastName = $row[1];
			$email = trim($row[7]);
			$classification = trim($row[34]);
			
			
			if ($this->check_email_address($email) && strlen($classification) > 1) {
				array_push($this->finalData, array (
														'firstname' => $firstName
														,'email'	=> $email
														,'class'	=> $classification
													)
							);
			}
		}
		
		return $this->asort2d($this->finalData, 'class');
	}
	function getData() {
		if ($finalData == null) {
			return getDataFromExcel();
		} else {
			return $this->finalData;
		}
	}
	
}
?>