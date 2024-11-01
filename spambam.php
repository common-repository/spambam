<?php
/*
Plugin Name: SpamBam
Plugin URI: http://www.thespanner.co.uk/category/spam-bam/
Description: A plugin that hopefully eliminates comment spam
Author: Gareth Heyes
Version: 2.1
Author URI: http://www.thespanner.co.uk/
*/


/*  Copyright 2007  Gareth Heyes  (email : gareth[at]NOSPAM businessinfo(dot)(co)(dot)uk

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_action('init', 'spamBam_init');
add_action('comment_form', 'spamBam_commentFormMsg');
add_action('pre_comment_approved', 'spamBam_allowedPost');

// Change the spammer delay here
define('SPAMBAM_SPAMMER_DELAY', 30);

function spamBam_init() {
	session_start();
}
function spamBam_removeKey() {
	$_SESSION['spambam_result'] = '';
}
function spamBam_getCurrentKey() {
	return join("",$_SESSION['spambam_result']); 
}
function spamBam_allowedPost($approved) {	
	if($_POST['comment_spambamKey'] === spamBam_getCurrentKey() && strlen(spamBam_getCurrentKey()) > 0) {
		spamBam_removeKey();
		return $approved;
	} else {
		// delay spammers muhhahahaha
		spamBam_removeKey();
		sleep(SPAMBAM_SPAMMER_DELAY);
		die("We don't allow comment spam here. Javascript is required to submit a comment.");
		return false;
	}
}
function spamBam_commentFormMsg() {
	spamBam_javascript();
	echo '<p><a href="http://www.thespanner.co.uk/category/spam-bam/">Comment spam protected by SpamBam</a></p>';
	echo '<noscript><p>You will not be able to post a comment. Javascript is required.</p></noscript>';
}
function spamBam_javascript() {
		echo '<script type="text/javascript">';
		$salt = SpamBam_RandomJavascriptGenerator::generateRandomKey(rand(5,10));
		?>
		var <?=$salt?> = '';
		<?php
		$randomJS = new SpamBam_RandomJavascriptGenerator;
		$randomJS->setJSVariable(SpamBam_RandomJavascriptGenerator::generateRandomKey(rand(5,10)));
		$randomJS->setNumOfBlocks(rand(2, $randomJS->maxAmountOfBlocks));
		echo "var {$randomJS->jsVariable};\n";
		for($i=0;$i<$randomJS->numOfBlocks;$i++) {
			$randomJS->generateCodeBlock(rand(1,$randomJS->maxAmountOfBlocks));
			echo "$salt += {$randomJS->jsVariable}\n";
		}
		echo 'document.write(\'<input type="hidden" name="comment_spambamKey" value="\'+'.$salt.'+\'">\')',";\n";
		echo '</script>';
		$_SESSION['spambam_result'] = $randomJS->result;			
}


class SpamBam_RandomJavascriptGenerator {
	var $maxAmountOfBlocks = 7;
	var $numOfBlocks = 3;
	var $result = array();
	var $input = 0;
	var $jsVariable;
	
	function setJSVariable($variable) {
		$this->jsVariable = $variable;
	}
	function setNumOfBlocks($num) {
		$this->numOfBlocks = $num;
	}
	function generateCodeBlock($codeBlock) {
		switch($codeBlock) {
			case 1:
				$this->setInput(rand(100,50000));
				$input = $this->getInput();
				$operators = array('+','-');
				$amount = rand(1,5);
				echo "{$this->jsVariable} = $input;\n";
				for($i=0;$i<$amount;$i++) {
					$operator = $operators[rand(0,count($operators)-1)];				
					$num = rand(100,50000);
					echo $this->jsVariable . ' = '.$this->jsVariable.' ' . $operator . ' ' . $num . ";\n";  
					$input = eval("return $input $operator $num;");
					$this->setInput($input);
				}
			break;
			case 2:
				$this->setInput($this->generateRandomKey(rand(5,50)));
				$input = $this->getInput();
				echo "string = '$input';\n";
				$len = strlen($input);
				$pos = rand(0, $len-1);
				$end = $len - $pos;
				$input = substr($input, $pos, $end);
				echo "$this->jsVariable = string.substr($pos, $end);\n";  
				$this->setInput($input);
			break;
			case 3:
				$this->setInput($this->generateRandomKey(rand(1,10)));
				$input = $this->getInput();
				echo "arr = new Array;\n";
				for($i=0;$i<strlen($input);$i++) {
					echo "arr.push('".substr($input, $i, 1)."');\n";
				} 
				echo "$this->jsVariable = arr.join('');\n";
				$this->setInput($input);				
			break;
			case 4:
				$this->setInput($this->generateRandomKey(rand(2,10)));
				$input = $this->getInput();
				echo "string = '';\n";
				for($i=0;$i<strlen($input);$i++) {
					echo "string += '".substr($input, $i, 1)."';\n";
				} 
				echo "$this->jsVariable = string\n";
				$this->setInput($input);								
			break;
			case 5:
				$this->setInput($this->generateRandomKey(rand(2,20)));
				$input = $this->getInput();
				echo "string = '';\n";
				$pos = floor(strlen($input)/2);
				echo "string = '".substr($input, 0, $pos)."' + '".substr($input, $pos, strlen($input))."';\n";
				echo "$this->jsVariable = string\n";
				$this->setInput($input);								
			break;
			case 6:
				$this->setInput($this->generateRandomKey(rand(2,20)));
				$input = $this->getInput();
				echo "string = '';\n";
				$pos = floor(strlen($input)/2);
				echo "string = '".substr($input, 0, $pos)."';\n";
				echo "$this->jsVariable = string\n";
				$input = substr($input, 0, $pos);
				$this->setInput($input);								
			break;
			case 7:
				$this->setInput(rand(2,2000));
				$input = $this->getInput();
				echo "num = ".$input.";\n";
				$rand = rand(1,3);
				$amount = rand(1,5);
				$randNum = rand(1,100);
				
				for($i=0;$i<$amount;$i++) {
					$input += $randNum;
					if($rand > 1) {
						for($j=0;$j<$amount;$j++) {
							$input += $randNum;
							if($rand > 2) {
								for($k=0;$k<$amount;$k++) {
									$input += $randNum;
								}
							}
						}
					}				
				}
				
				echo "for(i=0;i<$amount;i++) {\n";
				echo "num += $randNum;\n";
				if($rand > 1) {
					echo "for(j=0;j<$amount;j++) {\n";
					echo "num += $randNum;\n";
					if($rand > 2) {
					echo "for(k=0;k<$amount;k++) {\n";					
					echo "num += $randNum;\n";
					echo "}\n";
					}
					echo "}\n";
				}
				echo "}\n";
				echo "$this->jsVariable = num\n";
				$this->setInput($input);											
			break;						
		}
		array_push($this->result, $this->getInput());
	}
	function generateRandomKey($length) {
		$letters = range('a','z');
		$letters = array_merge($letters, range('A','Z'));
		$string = '';
		for($i=0;$i<$length;$i++) {
			$string .= $letters[rand(0,count($letters)-1)];
		}
		return $string;
	}	
	function getInput() {
		return $this->input;
	}
	function setInput($input) {
		$this->input = $input;
	}
	function getResult() {
		return $this->result;
	}
}
?>