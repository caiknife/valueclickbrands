<?PHP
if(!class_exists("xxObject")) { echo "class xxObject exists";exit;}

	define("SMTP_STATUS_NOT_CONNECTED", 1, true);
	define("SMTP_STATUS_CONNECTED", 2, true);

	if(!class_exists("xxObject")) {
		echo "<H1>Class xxObject not found!</H1>";
		exit;
	}
	class xxSMTP extends xxObject {
		/** PUBLIC VARIABLES */
		var $_CRLF="\r\n";
		/** PRIVATE VARIABLES */
		var $authenticated;
		var $connection;
		var $recipients;
		var $headers;
		var $timeout;
		var $status;
		var $body;
		var $from;
		var $host;
		var $port;
		var $helo;
		var $auth;
		var $user;
		var $pass;
		/** PUBLIC: Constructor */
		function xxSMTP($params=array()) {
			$this->authenticated=false;
			$this->timeout=5;
			$this->status=SMTP_STATUS_NOT_CONNECTED;
			$this->host="localhost";
			$this->port=25;
			$this->helo="localhost";
			$this->auth=false;
			$this->user="";
			$this->pass="";
			foreach($params as $k=>$v) $this->$k=$v;
		}
		/** PUBLIC: Connect to server */
		function &connect($params=array()) {
  			if(!isset($this->status)) {
				$obj=new xxSMTP($params);
				if($obj->connect()) $obj->status=SMTP_STATUS_CONNECTED;
				return $obj;
			} else {
				$this->connection=fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
				if(function_exists("socket_set_timeout")) socket_set_timeout($this->connection, 0, 250000);
				$greeting=$this->get_data();
				if(is_resource($this->connection)) {
					return $this->auth ? $this->ehlo() : $this->helo();
				} else {
					$this->setError("Failed to connect to server: ".$errstr);
					return false;
				}
			}
		}
		/** PUBLIC: Send the mail */
		function send($params=array()) {
			foreach($params as $k=>$v) $this->set($k, $v);
			if($this->is_connected()) {
				/** Do we auth || not? Note the distinction between the auth variable && auth() function */
				if($this->auth && !$this->authenticated) if(!$this->auth()) return false;
				$this->mail($this->from);
				if(is_array($this->recipients)) foreach($this->recipients as $value)  $this->rcpt($value);
				else $this->rcpt($this->recipients);
				if(!$this->data()) return false;
				/** Transparency */
				$headers=str_replace($this->_CRLF.".", $this->_CRLF."..", trim(implode($this->_CRLF, $this->headers)));
				$body=str_replace($this->_CRLF.".", $this->_CRLF."..", $this->body);
				$body=$body[0]=="." ? ".".$body : $body;
				$this->send_data($headers);
				$this->send_data("");
				$this->send_data($body);
				$this->send_data(".");
				$result=(substr(trim($this->get_data()), 0, 3)==="250");
				return $result;
			} else {
				$this->setError("Not connected!");
				return false;
			}
		}
		/** PRIVATE: Implement HELO command */
		function helo() {
			if(is_resource($this->connection) &&
				$this->send_data("HELO ".$this->helo) && substr(trim($error=$this->get_data()), 0, 3)==="250") return true;
			else {
				$this->setError("HELO command failed, output: ".trim(substr(trim($error),3)));
				return false;
			}
		}
		/** PRIVATE: Implement EHLO command */
		function ehlo() {
			if(is_resource($this->connection) &&
				$this->send_data("EHLO ".$this->helo) && substr(trim($error=$this->get_data()), 0, 3)==="250") return true;
			else {
				$this->setError("EHLO command failed, output: ".trim(substr(trim($error),3)));
				return false;
			}
		}
		/** PRIVATE: Implement RSET command */
		function rset() {
			if(is_resource($this->connection) &&
				$this->send_data("RSET") && substr(trim($error=$this->get_data()), 0, 3)==="250") return true;
			else {
				$this->setError("RSET command failed, output: ".trim(substr(trim($error),3)));
				return false;
			}
		}
		/** PRIVATE: Implement QUIT command */
		function quit() {
			if(is_resource($this->connection) && $this->send_data("QUIT") && substr(trim($error=$this->get_data()), 0, 3)==="221") {
				fclose($this->connection);
				$this->status=SMTP_STATUS_NOT_CONNECTED;
				return true;
			} else {
				$this->setError("QUIT command failed, output: ".trim(substr(trim($error),3)));
				return false;
			}
		}
		/** PRIVATE: Implement AUTH command */
		function auth() {
			if(is_resource($this->connection) &&
				$this->send_data("AUTH LOGIN") && substr(trim($error=$this->get_data()), 0, 3)==="334" &&
				$this->send_data(base64_encode($this->user)) && substr(trim($error=$this->get_data()),0,3)==="334" &&
				$this->send_data(base64_encode($this->pass)) && substr(trim($error=$this->get_data()),0,3)==="235") {
				$this->authenticated=true;
				return true;
			} else {
				$this->setError("AUTH command failed: ".trim(substr(trim($error),3)));
				return false;
			}
		}
		/** PRIVATE: Implement MAIL FROM: command */
		function mail($from) {
			if($this->is_connected() &&
				$this->send_data("MAIL FROM:<".$from.">") && substr(trim($this->get_data()), 0, 2)==="250") return true;
			else return false;
		}
		/** PRIVATE: Implement RCPT TO: command */
		function rcpt($to) {
			if($this->is_connected() &&
				$this->send_data("RCPT TO:<".$to.">") && substr(trim($error=$this->get_data()), 0, 2)==="25") return true;
			else {
				$this->setError(trim(substr(trim($error), 3)));
				return false;
			}
		}
		/** PRIVATE: Implement DATA command */
		function data() {
			if($this->is_connected() && $this->send_data("DATA") && substr(trim($error=$this->get_data()), 0, 3)==="354") return true;
			else {
				$this->setError(trim(substr(trim($error), 3)));
				return false;
			}
		}
		/** PRIVATE: Function to determine if this object is connected to the server || not */
		function is_connected() {
			return (is_resource($this->connection) && ($this->status===SMTP_STATUS_CONNECTED));
		}
		/** PRIVATE: Function to send a bit of data */
		function send_data($data) {
			if(is_resource($this->connection)) return fwrite($this->connection, $data.$this->_CRLF, strlen($data)+2);
			else return false;
		}
		/** PRIVATE: Function to get data */
		function &get_data() {
			$return="";
			$line="";
			if(is_resource($this->connection)) {
				while(strpos($return, $this->_CRLF)===false || substr($line,3,1)!==" ") {
					$line=fgets($this->connection, 512);
					$return.=$line;
				}
				return $return;
			} else return false;
		}
		/** PRIVATE: Sets a variable */
		function set($var, $value) {
			$this->$var=$value;
			return true;
		}
	}

	class xxMimePart {
		/** The encoding type of this part @var string */
		var $_encoding;
		/** An array of subparts @var array */
		var $_subparts;
		/** The output of this part after being built @var string */
		var $_encoded;
		/** Headers for this part @var array */
		var $_headers;
		/** The body of this part (not encoded) @var string */
		var $_body;
		/**
		 * Constructor. Sets up the object.
		 * @param $body   - The body of the mime part if any.
		 * @param $params - An associative array of parameters:
		 *                  content_type - The content type for this part eg multipart/mixed
		 *                  encoding     - The encoding to use, 7bit, base64, || quoted-printable
		 *                  cid          - Content ID to apply
		 *                  disposition  - Content disposition, inline || attachment
		 *                  dfilename    - Optional filename parameter for content disposition
		 *                  description  - Content description
		 * @access public
		 */
		function xxMimePart($body, $params=array()) {
			if(!defined("xxMimePart_CRLF")) define("xxMimePart_CRLF", "\r\n", true);
			foreach($params as $key=>$value) {
				switch($key) {
					case "content_type":
						$headers["Content-Type"]=$value.(isset($charset) ? ";charset=\"".$charset."\"" : "");
						break;
					case "encoding":
						$this->_encoding=$value;
						$headers["Content-Transfer-Encoding"]=$value;
						break;
					case "cid":
						$headers["Content-ID"]="<".$value.">";
						break;
					case "disposition":
						$headers["Content-Disposition"]=$value.(isset($dfilename) ? ";filename=\"".$dfilename."\"" : "");
						break;
					case "dfilename":
						if(isset($headers["Content-Disposition"])) {
							$headers["Content-Disposition"].=";filename=\"".$value."\"";
						} else $dfilename=$value;
						break;
					case "description":
						$headers["Content-Description"]=$value;
						break;
					case "charset":
						if(isset($headers["Content-Type"])) {
							$headers["Content-Type"].=";charset=\"".$value."\"";
						} else $charset=$value;
						break;
				}
			}
			/** Default content-type */
			if(!isset($_headers["Content-Type"])) $_headers["Content-Type"]="text/plain";
			/** Assign stuff to member variables */
			$this->_encoded=array();
			$this->_headers=&$headers;
			$this->_body=$body;
		}
		/**
		 * encode() - Encodes && returns the email. Also stores it in the encoded member variable
		 * @return An associative array containing two elements,
		 *         body && headers. The headers element is itself
		 *         an indexed array.
		 * @access public
		 */
		function encode() {
			$encoded=&$this->_encoded;
			if(!empty($this->_subparts)) {
				srand((double)microtime()*1000000);
				$boundary="=_".md5(uniqid(rand()).microtime())."=";
				$this->_headers["Content-Type"].=";".xxMimePart_CRLF.chr(9)."boundary=\"".$boundary."\"\"";
				/** Add body parts to $subparts */
				for($i=0;$i < sizeof($this->_subparts);$i++) {
					$headers=array();
					$tmp=$this->_subparts[$i]->encode();
					foreach ($tmp["headers"] as $key=>$value) $headers[]=$key.": ".$value;
					$subparts[]=implode(xxMimePart_CRLF, $headers).xxMimePart_CRLF.xxMimePart_CRLF.$tmp["body"];
				}
				$encoded["body"]="--".$boundary.xxMimePart_CRLF.
					implode("--".$boundary.xxMimePart_CRLF, $subparts)."--".$boundary."--".xxMimePart_CRLF;
			} else $encoded["body"]=$this->_getEncodedData($this->_body, $this->_encoding).xxMimePart_CRLF;
			/** Add headers to $encoded */
			$encoded["headers"]=&$this->_headers;
			return $encoded;
		}
		/**
		 * &addSubPart() - Adds a subpart to current mime part && returns
		 * a reference to it
		 * @param $body   The body of the subpart, if any.
		 * @param $params The parameters for the subpart, same
		 *                as the $params argument for constructor.
		 * @return A reference to the part you just added. It is
		 *         crucial if using multipart/* in your subparts that
		 *         you use=&in your script when calling this function,
		 *         otherwise you will not be able to add further subparts.
		 * @access public
		 */
		function &addSubPart($body, $params) {
			$this->_subparts[]=new xxMimePart($body, $params);
			return $this->_subparts[sizeof($this->_subparts) - 1];
		}
		/**
		 * _getEncodedData() - Returns encoded data based upon encoding passed to it
		 * @param $data     The data to encode.
		 * @param $encoding The encoding type to use, 7bit, base64,
		 *                  || quoted-printable.
		 * @access private
		 */
		function _getEncodedData($data, $encoding) {
			switch($encoding) {
				case "7bit":
					return $data;
					break;
				case "quoted-printable":
					return $this->_quotedPrintableEncode($data);
					break;
				case "base64":
					return rtrim(chunk_split(base64_encode($data), 76, xxMimePart_CRLF));
					break;
			}
		}
		/**
		 * quoteadPrintableEncode() - Encodes data to quoted-printable standard.
		 * @param $input    The data to encode
		 * @param $line_max Optional max line length. Should 
		 *                  not be more than 76 chars
		 * @access private
		 */
		function _quotedPrintableEncode($input , $line_max=76) {
			$lines=preg_split("/\r\n|\r|\n/", $input);
			$eol=xxMimePart_CRLF;
			$escape="=";
			$output="";
			while(list(, $line)=each($lines)) {
				$linlen=strlen($line);
				$newline="";
				for($i=0;$i < $linlen;$i++) {
					$char=substr($line, $i, 1);
					$dec=ord($char);
					/** convert space at eol only */
                	if(($dec == 32) && ($i == ($linlen - 1))) $char="=20";
                	elseif($dec == 9) ;/** Do nothing if a tab */
                	elseif(($dec == 61) || ($dec < 32 ) || ($dec > 126)) $char=$escape.strtoupper(sprintf("%02s", dechex($dec)));
					/** xxMimePart_CRLF is not counted */
					if((strlen($newline) + strlen($char)) >= $line_max) {
						/** soft line break;" =\r\n" is okay */
						$output .=$newline.$escape.$eol;
						$newline ="";
					}
					$newline.=$char;
				}
				$output.=$newline.$eol;
			}
			/** Don't want last crlf */
			$output=substr($output, 0, -1 * strlen($eol));
			return $output;
		}
	}

	class xxMail extends xxObject {
		var $_CRLF="\r\n";
		var $html;
		var $text;
		var $output;
		var $html_text;
		var $html_images;
		var $image_types;
		var $build_params;
		var $attachments;
		var $headers;
		/** Constructor function. Sets the headers if supplied */
		function xxMail($headers=array()) {
			$this->html_images=array();
			$this->headers=array();
			$this->image_types=array(
				"gif"=>"image/gif",
				"jpg"=>"image/jpeg",
				"jpeg"=>"image/jpeg",
				"jpe"=>"image/jpeg",
				"bmp"=>"image/bmp",
				"png"=>"image/png",
				"tif"=>"image/tiff",
				"tiff"=>"image/tiff",
				"swf"=>"application/x-shockwave-flash",
				"css"=>"text/css"
			);
			$this->build_params["html_encoding"]="quoted-printable";
			$this->build_params["text_encoding"]="7bit";
			$this->build_params["html_charset"]="UTF-8";
			$this->build_params["text_charset"]="UTF-8";
			$this->build_params["text_wrap"]=998;
			$this->headers[]="MIME-Version: 1.0";
			foreach($headers as $value) if(!empty($value)) $this->headers[]=$value;
		}
		/**
		 * This function will read a file in from a supplied filename && return
		 * it. This can then be given as the first argument of the the functions
		 * addHtmlImage() || addAttachment().
		 */
		function getFile($filename) {
			if($fp=fopen($filename, "rb")) {
				$return=fread($fp, filesize($filename));
				fclose($fp);
				return $return;
			} else return false;
		}
		/**
		 * Function for extracting images from html source. This function will look
		 * through the html code supplied by addHtml() && find any file that ends in one of the
		 * extensions defined in $obj->image_types. If the file exists it will read it in and
		 * embed it, (not an attachment)
		 */
		function findHtmlImages($images_dir) {
			while(list($key,)=each($this->image_types)) $extensions[]=$key;
			preg_match_all("/\"([^\"]+\.(".implode("|", $extensions)."))\"/Ui", $this->html, $images);
			for($i=0;$i<sizeof($images[1]);$i++) {
				if(file_exists($images_dir.$images[1][$i])) {
					$html_images[]=$images[1][$i];
					$this->html=str_replace($images[1][$i], basename($images[1][$i]), $this->html);
				}
			}
			if(!empty($html_images)) {
				$html_images=array_unique($html_images);
				sort($html_images);
				for($i=0;$i<sizeof($html_images);$i++) {
					if($image=$this->getFile($images_dir.$html_images[$i])) {
						$content_type=$this->image_types[substr($html_images[$i], strrpos($html_images[$i], ".") + 1)];
						$this->addHtmlImage($image, basename($html_images[$i]), $content_type);
					}
				}
			}
		}
		/** Adds plain text. Use this function when NOT sending html email */
		function addText($text="") {
			$this->text=$text;
		}
		/** Adds a html part to the mail. Also replaces image names with content-id's */
		function addHtml($html, $text=NULL, $images_dir=NULL) {
			$this->html=$html;
			$this->html_text=$text;
			if(isset($images_dir)) $this->findHtmlImages($images_dir);
		}
		/** Adds an image to the list of embedded images */
		function addHtmlImage($file, $name="", $c_type="application/octet-stream") {
			$this->html_images[]=array(
				"body"=>$file,
				"name"=>$name,
				"c_type"=>$c_type,
				"cid"=>md5(uniqid(time()))
			);
		}
		/** Adds a file to the list of attachments */
		function addAttachment($file, $name="", $c_type="application/octet-stream", $encoding="base64") {
			$this->attachments[]=array(
				"body"=>$file,
				"name"=>$name,
				"c_type"=>$c_type,
				"encoding"=>$encoding
			);
		}
		/** Adds a text subpart to a mime_part object */
		function &addTextPart(&$obj, $text) {
			$params["content_type"]="text/plain";
			$params["encoding"]=$this->build_params["text_encoding"];
			$params["charset"]=$this->build_params["text_charset"];
			if(is_object($obj)) return $obj->addSubpart($text, $params);
			else return new xxMimePart($text, $params);
		}
		/** Adds a html subpart to a mime_part object */
		function &addHtmlPart(&$obj) {
			$params["content_type"]="text/html";
			$params["encoding"]=$this->build_params["html_encoding"];
			$params["charset"]=$this->build_params["html_charset"];
			if(is_object($obj)) return $obj->addSubpart($this->html, $params);
			else return new xxMimePart($this->html, $params);
		}
		/** Starts a message with a mixed part */
		function &addMixedPart() {
			$params["content_type"]="multipart/mixed";
			return new xxMimePart("", $params);
		}
		/** Adds an alternative part to a mime_part object */
		function &addAlternativePart(&$obj) {
			$params["content_type"]="multipart/alternative";
			if(is_object($obj)) return $obj->addSubpart("", $params);
			else return new xxMimePart("", $params);
		}
		/** Adds a html subpart to a mime_part object */
		function &addRelatedPart(&$obj) {
			$params["content_type"]="multipart/related";
			if(is_object($obj)) return $obj->addSubpart("", $params);
			else return new xxMimePart("", $params);
		}
		/** Adds an html image subpart to a mime_part object */
		function &addHtmlImagePart(&$obj, $value) {
			$params["content_type"]=$value["c_type"];
			$params["encoding"]="base64";
			$params["disposition"]="inline";
			$params["dfilename"]=$value["name"];
			$params["cid"]=$value["cid"];
			$obj->addSubpart($value["body"], $params);
		}
		/** Adds an attachment subpart to a mime_part object */
		function &addAttachmentPart(&$obj, $value) {
			$params["content_type"]=$value["c_type"];
			$params["encoding"]=$value["encoding"];
			$params["disposition"]="attachment";
			$params["dfilename"]=$value["name"];
			$obj->addSubpart($value["body"], $params);
		}
		/**
		 * Builds the multipart message from the list ($this->_parts). $params is an
		 * array of parameters that shape the building of the message. Currently supported are:
		 *
		 * $params["html_encoding"] - The type of encoding to use on html. Valid options are
		 *                            "7bit", "quoted-printable" || "base64" (all without quotes).
		 *                            7bit is EXPRESSLY NOT RECOMMENDED. Default is quoted-printable
		 * $params["text_encoding"] - The type of encoding to use on plain text Valid options are
		 *                            "7bit", "quoted-printable" || "base64" (all without quotes).
		 *                            Default is 7bit
		 * $params["text_wrap"]     - The character count at which to wrap 7bit encoded data.
		 *                            Default this is 998.
		 * $params["html_charset"]  - The character set to use for a html section.
		 *                            Default is UTF-8
		 * $params["text_charset"]  - The character set to use for a text section.
		 *                          - Default is UTF-8
		 */
		function buildMessage($params=array()) {
			if(sizeof($params)>0) while(list($key, $value)=each($params)) $this->build_params[$key]=$value;
			if(!empty($this->html_images))
				foreach($this->html_images as $value) $this->html=str_replace($value["name"], "cid:".$value["cid"], $this->html);
			$null=NULL;
			$attachments=!empty($this->attachments) ? true : false;
			$html_images=!empty($this->html_images) ? true : false;
			$html=!empty($this->html) ? true : false;
			$text=isset($this->text) ? true : false;
			switch(true) {
				case $text && !$attachments && !$html:
					$message=&$this->addTextPart($null, $this->text);
					break;
				case !$text && $attachments && !$html:
					$message=&$this->addMixedPart();
					for($i=0;$i<sizeof($this->attachments);$i++) $this->addAttachmentPart($message, $this->attachments[$i]);
					break;
				case $text && $attachments && !$html:
					$message=&$this->addMixedPart();
					$this->addTextPart($message, $this->text);
					for($i=0;$i<sizeof($this->attachments);$i++) $this->addAttachmentPart($message, $this->attachments[$i]);
					break;
				case $html && !$attachments && !$html_images && !$text:
					if(!is_null($this->html_text)) {
						$message=&$this->addAlternativePart($null);
						$this->addTextPart($message, $this->html_text);
						$this->addHtmlPart($message);
					} else $message=&$this->addHtmlPart($null);
					break;
				case $text && $html && !$attachments && !$html_images:
					$message=&$this->addMixedPart();
					if(!is_null($this->text)) $this->addTextPart($message, $this->text);
					if(!is_null($this->text)) $this->addTextPart($message, $this->html_text);
					$this->addAlternativePart($null);
					$this->addHtmlPart($message);
					break;
				case $text && $html && !$attachments && $html_images:
					$message=&$this->addMixedPart();
					if(!is_null($this->text)) $this->addTextPart($message, $this->text);
					if(!is_null($this->html_text)) {
						$alt=&$this->addAlternativePart($message);
						$this->addTextPart($alt, $this->html_text);
						$related=&$this->addRelatedPart($message);
					} else $related=&$this->addRelatedPart($message);
					$this->addHtmlPart($related);
					for($i=0;$i<sizeof($this->html_images);$i++) $this->addHtmlImagePart($related, $this->html_images[$i]);
					break;
				case $text && $html && $attachments && !$html_images:
					$message=&$this->addMixedPart();
					if(!is_null($this->text)) $this->addTextPart($message, $this->text);
					if(!is_null($this->html_text)) {
						$alt=&$this->addAlternativePart($message);
						$this->addTextPart($alt, $this->html_text);
						$this->addHtmlPart($alt);
					} else $this->addHtmlPart($message);
					for($i=0;$i<sizeof($this->attachments);$i++) $this->addAttachmentPart($message, $this->attachments[$i]);
					break;
				case $text && $html && $attachments && $html_images:
					$message=&$this->addMixedPart();
					if(!is_null($this->text)) $this->addTextPart($message, $this->text);
					if(!is_null($this->html_text)) {
						$alt=&$this->addAlternativePart($message);
						$this->addTextPart($alt, $this->html_text);
						$rel=&$this->addRelatedPart($alt);
					} else $rel=&$this->addRelatedPart($message);
					$this->addHtmlPart($rel);
					for($i=0;$i<sizeof($this->html_images);$i++) $this->addHtmlImagePart($rel, $this->html_images[$i]);
					for($i=0;$i<sizeof($this->attachments);$i++) $this->addAttachmentPart($message, $this->attachments[$i]);
					break;
				case $html && !$attachments && $html_images:
					if(!is_null($this->html_text)) {
						$message=&$this->addAlternativePart($null);
						$this->addTextPart($message, $this->html_text);
						$related=&$this->addRelatedPart($message);
					} else $related=&$this->addRelatedPart($null);
					$this->addHtmlPart($related);
					for($i=0;$i<sizeof($this->html_images);$i++) $this->addHtmlImagePart($related, $this->html_images[$i]);
					break;
				case $html && $attachments && !$html_images:
					$message=&$this->addMixedPart();
					if(!is_null($this->html_text)) {
						$alt=&$this->addAlternativePart($message);
						$this->addTextPart($alt, $this->html_text);
						$this->addHtmlPart($alt);
					} else $this->addHtmlPart($message);
					for($i=0;$i<sizeof($this->attachments);$i++) $this->addAttachmentPart($message, $this->attachments[$i]);
					break;
				case $html && $attachments && $html_images:
					$message=&$this->addMixedPart();
					if(!is_null($this->html_text)) {
						$alt=&$this->addAlternativePart($message);
						$this->addTextPart($alt, $this->html_text);
						$rel=&$this->addRelatedPart($alt);
					} else $rel=&$this->addRelatedPart($message);
					$this->addHtmlPart($rel);
					for($i=0;$i<sizeof($this->html_images);$i++) $this->addHtmlImagePart($rel, $this->html_images[$i]);
					for($i=0;$i<sizeof($this->attachments);$i++) $this->addAttachmentPart($message, $this->attachments[$i]);
					break;
			}
			if(isset($message)) {
				$output=$message->encode();
				$this->output=$output["body"];
				foreach($output["headers"] as $key=>$value) $headers[]=$key.": ".$value;
				$this->headers=array_merge($this->headers, $headers);
				return true;
			} else return false;
		}
		/** Sends the mail */
		function send($toName, $toAddr, $fromName, $fromAddr, $subject="", $headers="") {
			$to		= ($toName!="") ? "\"".$toName."\" <".$toAddr.">" : $toAddr;
			$from	= ($fromName!="") ? "\"".$fromName."\" <".$fromAddr.">" : $fromAddr;
			if(is_string($headers)) $headers=explode($this->_CRLF, trim($headers));
			for($i=0;$i<sizeof($headers);$i++) {
				if(is_array($headers[$i]))
					for($j=0;$j<sizeof($headers[$i]);$j++)	if($headers[$i][$j]!="") $xtra_headers[]=$headers[$i][$j];
				if($headers[$i]!="") $xtra_headers[]=$headers[$i];
			}
			if(!isset($xtra_headers)) $xtra_headers=array();
			return mail($to, $subject, $this->output, "From: ".$from.$this->_CRLF.implode($this->_CRLF, $this->headers).$this->_CRLF.implode($this->_CRLF, $xtra_headers));
		}
		/**
		 * Use this method to deliver using direct smtp connection. Relies upon the smtp
		 * class available from http://www.heyes-computing.net. You probably downloaded
		 * it with this class though.
		 * bool smtpSend(object The smtp object, array  Parameters to pass to the smtp object)
		 */
		function smtpSend(&$smtp, $params=array()) {
			foreach($params as $key=>$value) {
				switch($key) {
					case "headers":
						$headers=$value;
						break;
					case "from":
						$send_params["from"]=$value;
						break;
					case "recipients":
						$send_params["recipients"]=$value;
						break;
				}
			}
			$send_params["body"]=$this->output;
			$send_params["headers"]=array_merge($this->headers, $headers);
			return $smtp->send($send_params);
		}
		/**
		 * Use this method to return the email in message/rfc822 format. Useful for
		 * adding an email to another email as an attachment. there's a commented
		 * out example in example.php.
		 *
		 * string getRFC822(string To name,
		 *		   string To email,
		 *		   string From name,
		 *		   string From email,
		 *		   [string Subject,
		 *		    string Extra headers])
		 */
		function getRFC822($toName, $toAddr, $fromName, $fromAddr, $subject="", $headers="") {
			$date="Date: ".date("D, d M y H:i:s");
			$to=($toName!="") ? "To: \"".$toName."\" <".$toAddr.">" : "To: ".$toAddr;
			$from=($fromName!="") ? "From: \"".$fromName."\" <".$fromAddr.">" : "From: ".$fromAddr;
			if(is_string($subject)) $subject="Subject: ".$subject;
			if(is_string($headers)) $headers=explode($this->_CRLF, trim($headers));
			for($i=0;$i<sizeof($headers);$i++) {
				if(is_array($headers[$i]))
					for($j=0;$j<sizeof($headers[$i]);$j++) if($headers[$i][$j]!="") $xtra_headers[]=$headers[$i][$j];
				if($headers[$i]!="") $xtra_headers[]=$headers[$i];
			}
			if(!isset($xtra_headers)) $xtra_headers=array();
			$headers=array_merge($this->headers, $xtra_headers);
			return $date.$this->_CRLF.$from.$this->_CRLF.$to.$this->_CRLF.$subject.$this->_CRLF.implode($this->_CRLF, $headers).$this->_CRLF.$this->_CRLF.$this->output;
		}
		
		
		function is_domain_valid_raw($domain){
		if(is_array($domain)) { return true;}
			$found=false;
			$len=strlen($domain);
			$command="dig ".$domain." -mx";
			exec($command, $response);
			while(list($id, $value)=each($response)) 
				{ 
				if(substr($value,0, $len) == $domain) 
					{ 
					$found=true;
					$temp=$domain;
					settype($domain,"array");
					$domain[0]=$temp;
					break;
					} 
				} 
			return $found;
		}
		//with some servers work wrong
		function is_domain_valid_php($domain){
			$found=false;
			if(checkdnsrr($domain)) {
				if(getmxrr($domain, $MXHost)) {
					$domain=$MXHost;
					$found=true;
				}
			}
			return $found;
		}
		function isEmailNoValid($verified_email, $bomb) {
			global $HTTP_HOST;
			$verified_email=trim($verified_email);
			preg_match("/^(([\.\w\d\-\_]+)@(([\w\d\-\_]+)\.([\.\w\d\-\_]+)))$/", $verified_email, $results);
			if($results[0]=="") return 1;// error email format
			$domain_name=$results[3];
			if(!$this->is_domain_valid_php(&$domain_name)&&!$this->is_domain_valid_raw(&$domain_name)) return 2;// error dns found
			$return="1";
			$loop=0;
			do {
				$Connect=fsockopen($domain_name[$loop], 25);
				if($Connect) {
					$s=$c=0;
					$out="";
					socket_set_blocking( $Connect, false);
					do {
						$out=fgets($Connect, 2500);
						if(ereg("^220", $out)) {
							$s=0;
							$out="";
							$c++;
						} elseif($c>0 && $out=="") break; else $s++;
						if($s==999) break;
					} while($out=="");
					socket_set_blocking($Connect, true);
					// Inform client's reaching to server who connect.
					fputs($Connect, "HELO $HTTP_HOST\r\n");
					$Out=fgets($Connect, 1024);// Receive server's answering cord.
					// Inform sender's address to server.
					fputs($Connect, "MAIL FROM: <{$verified_email}>\r\n");
					// Receive server's answering cord.
					$From=fgets($Connect, 1024);
					// Inform listener's address to server.
					fputs($Connect, "RCPT TO: <{$verified_email}>\r\n");
					// Receive server's answering cord.
					$To=fgets($Connect, 1024);
					// Finish connection.
					fputs($Connect, "QUIT\r\n");
					fclose($Connect);
					// Server's answering cord about MAIL and TO command checks.
					// Server about listener's address reacts to 550 codes if there does not exist
					// checking that mailbox is in own E-Mail account.
					// error no email found
					if(!ereg("^250", $To)) $return=3;
					// Server not set data about user
					if(ereg("^220", $To)) $return=5;
					if(ereg("^250", $To)) $return=0;
				}
				// error no connect found
				else $return=4;
				$loop++;
				} while($return>"0" && $bomb && $loop<sizeof($domain_name));
			// is emai valid
			return $return;
		}
		function isEmailValid($email, $bomb=false) {
			switch($this->isEmailNoValid($email, $bomb)) {
				case 0:
					return true;
					break;
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
					return false;
					break;
			}
		}
	}
?>
