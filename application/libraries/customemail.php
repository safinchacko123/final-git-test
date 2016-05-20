<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class customemail {

    public function reg_confirmation($to,$subject,$message='',$heading='',$from='Market Place',$cc='',$bcc='',$your_name='Dropneed')
    {
	
        $CI =& get_instance();
        //$config['protocol'] = 'sendmail';
        //$config['mailpath'] = '/usr/sbin/sendmail';
        //$config['charset'] = 'iso-8859-1';
        //$config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html'; 
        
       
		$CI->load->library('email');
		$CI->email->initialize($config);
		$CI->email->from('<lynn@csgroupchd.com>');
		$CI->email->to($to); 
		//$CI->email->cc($cc); 
		//$CI->email->bcc($bcc); 
		$CI->email->subject($subject);
		//  $appUrl = BASE_URL;
		// $logo =$appUrl.'images/top2.jpg';
		// $bg = 'background:url("'.$appUrl.'images/bg.jpg")';
        $messageBody = '<table border="0" align="center" cellspacing="0" cellpadding="0" width="590" style="border:solid 1px #dfdfdf;background:url(http://images/bg.jpg) repeat center top">
						<tbody>
						<tr><td valign="top"><table border="0" cellspacing="0" cellpadding="15" width="100%">
						<tbody><tr>
						<td valign="top" style="font-family:Arial,Tahoma;font-size:13px;color:#484848;line-height:18px">
						<h1 style="color:#ae0a19">'.$heading.'</h1><br>'.$message.'	</td></tr>
						</tbody></table></td>
						</tr>
						<tr>
						<td valign="top" style="border-top:solid 1px #dfdfdf;padding:10px"><table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tbody><tr>
						<td width="56%" valign="middle">
						</td>
						<td align="right" width="44%" valign="middle" style="font-family:arial,tahoma;color:#afafb0;font-size:11px">Copyright © 2016 Dropneed.com</td>
						</tr>
						</tbody></table></td>
						</tr>
						</tbody></table>';

	$CI->email->message($messageBody);	
	return $CI->email->send();
	//echo $CI->email->print_debugger();
    }
}

/* End of file The Best In Canadaemail.php */
