<?php 
 /*
  * @author & Original Code by:
  * 	   //WaveStreaming\\
  *
  * Edited & Updated by Vee_
  * Info lebih lanjut hubungin aja Vee_ (Googling sana!)
 */
class Shout
{
// Setting koneksi
   private $_host,
   $_port,
   $_stream,
   $_pass,
   $_xml,
   $_status = false,
   $_error = false;

   public function __construct($host = '', $port = 8000, $pass = '', $stream = 1)
   		{
      	  $this->setDetails($host, $port, $pass, $stream);
   		}	

/*DOKUMENTASI @setDetails*/
   public function setDetails ($host,$port = 8000, $pass = '', $stream = 1)
   {
   		$this->_host = $host;
   		$this->_port = $port;
   		$this->_pass = $pass;
   		$this->_stream = $stream;
   		return $this;
   }

/*DOKUMENTASI @getXML*/
   	public function getXML()
   {
      if ($this->_status === true) {
         return $this->_xml;
      }
      return false;
   }

/**
    * Method to check if the stream is online or not
    * @return bool true if streaming 
    */
   public function streamStatus()
   {
     return (bool) $this->get('streamStatus');
   }

/*DOKUMENTASI @getAll

	http://localhost:666/admin.cgi?sid=1&mode=viewxml
	http://localhost:666/admin.cgi?mode=viewxml&sid=1
*/
	public function getAll()
	{
		$url = 'http://' . $this->_host . ':' . $this->_port . '/admin.cgi?mode=viewxml&page=1' . '&pass=' . $this->_pass . '&sid=' . $this->_stream;
      return $this->_curl($url);
	}
	/*public function getAll()
	{
		$url = 'http://' . $this->_host . ':' . $this->_port . '/admin.cgi?'.'&sid=' . $this->_stream. 'mode=viewxml' . '&pass=' . $this->_pass;
      return $this->_curl($url);
	}*/

/*DOKUMENTASI @getBasicStats*/
	public function getBasicStats()
   {
      return $this->_curl('http://' . $this->_host . ':' . $this->_port . '/stats?sid=' . $this->_stream);
   }

/*DOKUMENTASI @getBasicStats*/
	public function getListeners()
   {
      if ($this->_status === true) {
         if (isset($this->_xml->LISTENERS, $this->_xml->LISTENERS->LISTENER)) {
            $listeners = array();
            foreach ($this->_xml->LISTENERS->LISTENER as $listener) {
               $listeners[] = (array) $listener;
            }
            return $listeners;
         }
      }
      return false;
   }

/**
    * Get a song history array
    * @return array array of song arrays 
    */
   public function getSongHistory()
   {
      $songs = array();
      if ($this->_status === true) {
         if (isset($this->_xml->SONGHISTORY, $this->_xml->SONGHISTORY->SONG)) {
            foreach ($this->_xml->SONGHISTORY->SONG as $song) {
               $songs[] = array(
                   'playedat' => (string) $song->PLAYEDAT,
                   'title' => (string) $song->TITLE,
               );
            }
            return $songs;
         }
      }
      return false;
   }

/////////////////////////////////////////////////////////////////////
/**
    * Get data from the shout cast 7.html
    * For shoutcast version <2
    * @return stdClass 
    */
   public function getSevenHTML()
   {
      $data = $this->_curl('http://' . $this->_host . ':' . $this->_port . '/7.html', false);
      $ret = new \stdClass();

      if ($data) {
         $parts = explode(',', $data);

         if (count($parts) > 5) {

            $ret->currentListeners = (isset($parts[0])) ? array_shift($parts) : 0;
            $ret->streamStatus = (isset($parts[0])) ? array_shift($parts) : 0;
            $ret->peakListeners = (isset($parts[0])) ? array_shift($parts) : 0;
            $ret->maxListeners = (isset($parts[0])) ? array_shift($parts) : 0;
            $ret->uniqueListeners = (isset($parts[0])) ? array_shift($parts) : 0;
            $ret->bitrate = (isset($parts[0])) ? array_shift($parts) : 0;
            $ret->songTitle = implode(',', $parts);

            return $ret;
         } else {
            $this->_error = 'Did not get all parts, data: <br/><br/>' . $data;
         }
      } else {
         $this->_error = 'Failed to get data';
      }
      return false;
   }

/////////////////////////////////////////////////////////////////////
/* 						 Function _curl
 * 
 * Bagian ini bisa diedit selama lu tau apa yang lu mau edit.
 * Kalo ga tau atau emang ga ngerti mending belajar lagi, 
 * banyakin Iqra(Bacalah), atau ya nanya ama yang bikin ini code.
 * 
 * 						!!! CAUTION !!!
 * 						  HELL INSIDE
*/
/////////////////////////////////////////////////////////////////////
/**
    * Get information about na error
    * @return string error string 
    */
   public function getError()
   {
      return $this->_error;
   }
/**
    * Get an attribute from the feed
    * @param string $attr the attribute
    * @param bool $string if the output should be cast as string.
    * If string is not true a simple xml element will be returned.
    * @return mixed attribute or false 
    */
   public function get($attr, $string = true)
   {
      $attr = strtoupper($attr);

      if ($this->_status === true) {
         if (isset($this->_xml->$attr)) {
            return ($string === true) ? (string) $this->_xml->$attr : $this->_xml->$attr;
         }
      }
      return false;
   }
/*Ngakalin Method get()*/
public function __get($key)
   {
      return $this->get($key);
   }

/**
    * Tell the DNAS to reload the config file
    * @return string DNAS response 
    */
   public function reloadConfig()
   {
      $url = 'http://' . $this->_host . ':' . $this->_port . '/admin.cgi?pass=' . $this->_pass . '&mode=reload&force=1';
      return $this->_curl($url,false);
   }

/**
    * Get the xml feed etc
    * @return bool
    */
private function _curl($url, $xml = true)
   {
      $ret = false;
      $this->_status = false;
      $this->_error = false;

      $ch = curl_init();
      if (is_resource($ch)) {
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; Vee)');
         curl_setopt($ch, CURLOPT_TIMEOUT, 5);
         $data = curl_exec($ch);
         curl_close($ch);

         if ($data) {

            if ($xml === true) {
               $this->_xml = @simplexml_load_string($data);
               if ($this->_xml instanceof \SimpleXMLElement && $this->_xml->getName() == 'SHOUTCASTSERVER') {
                  $this->_status = true;
                  $ret = true;
               } else {
                  $this->_error = 'Not SHOUTcast';
               }
            } else {
               $this->_status = true;
               $ret = $data;
            }
         } else {
            $this->_error = 'Failed to connect';
         }
      } else {
         $this->_error = 'cURL is not a resource';
      }
      return $ret;
   }
}
 ?>