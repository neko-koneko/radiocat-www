<?php


class SendMail{
    function send($emailTo, $emailFrom, $emailRe, $name, $emailSubject, $emailText, $emailPriority = 3, $encoding = "UTF-8", $attach = array()){
        $priorityList = array(
            1 => "Highest",
            2 => "High",
            3 => "Normal",
            4 => "Low",
            5 => "Lowest"
        );

        //$emailTo = mime_header_encode($emailTo, "UTF-8", $encoding);
        $emailSubject = mime_header_encode($emailSubject, "UTF-8", $encoding);

        $name = mime_header_encode($name, "UTF-8", $encoding);
        $from = $name." <".$emailFrom.">";
        //$from = mime_header_encode($from, "UTF-8", $encoding);

        $RE   = $name." <".$emailRe.">";
        //$RE   = mime_header_encode($RE, "UTF-8", $encoding);

        $boundary = $this->generate();
        $header = "Date: ".date("D, j M Y G:i:s")." +0300\n";
        $header .= "From: $from\n";
        $header .= "X-Mailer: PHP Mail Sender\n";
        $header .= "Reply-To: $RE\n";
       // $header .= "X-Confirm-Reading-To: <{$emailFrom}>\n";
       // $header .= "Disposition-Notification-To: <{$emailFrom}>\n";
       // $header .= "Return-Receipt-To: <{$emailFrom}>\n";
        $header .= "X-Priority: {$emailPriority} ({$priorityList[(int)$emailPriority]})\n";
        $header .= "MIME-Version: 1.0\n";
        $header .= "Content-Type: multipart/related; boundary=\"------------{$boundary}\"\n\n";
        $sendText = "--------------{$boundary}\n";
        $sendText .= "Content-Type: text/html; charset={$encoding}\n";
        $sendText .= "Content-Transfer-Encoding: 8bit\n\n";
        $sendText .= "{$emailText}\n\n";
        foreach($attach as $fileName){
            $sendText .= "--------------{$boundary}\n";
            $sendText .= "Content-Type: application/octet-stream; name=\"{$fileName}\"\n";
            $sendText .= "Content-Transfer-Encoding: base64\n";
            $sendText .= "Content-ID: <{$fileName}>\n";
            $sendText .= "Content-Disposition: inline; filename=\"{$fileName}\"\n\n";

            $file = fopen(getcwd()."/sendmail/".$fileName, "rb");
            $sendText .= chunk_split(base64_encode(fread($file, filesize(getcwd()."/sendmail/".$fileName))))."\n";
            fclose($file);
        }

        return mail($emailTo, $emailSubject, $sendText, $header);
    }
    function generate(){
        $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $boundary = "";
        for($cycle = 1; $cycle < 25; $cycle++){
            $number = rand(0, 61);
            $boundary .= $chars[$number];
        }
        return $boundary;
    }
}


function mail_prepare($txt)
         {
          return FixEOL(str_replace ("<br />", "<br />\n", $txt ));


         //return FixEOL(chunk_split($txt));
         }


 function FixEOL($str) {
        $str = str_replace("\n.", "\n..", $str);
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\r", "\n", $str);

         if (substr($str, -(strlen("\n"))) != "\n"){ $str .= "\n";}

        return $str;
    }

function mime_header_encode($str, $data_charset, $send_charset) {
  if($data_charset != $send_charset) {
    $str = iconv($data_charset, $send_charset, $str);
  }
  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}


function fix_ansi_string($str)
 {
 $order   = array("\r\n", "\n", "\r");
 $replace = '<br />';
// Processes \r\n's first so they aren't converted twice.
 $str = str_replace($order, $replace, $str);
 return $str;
 }

?>
