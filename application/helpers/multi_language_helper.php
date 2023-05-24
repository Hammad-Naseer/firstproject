<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
if ( ! function_exists('get_phrase'))
{
	function get_phrase($phrase = '')
    {
		
		
// 		$user_language = get_user_language();
// 		$CI =& get_instance();
// 		$CI->lang->load('common', $user_language);
// 		$phrase_value = $CI->lang->line($phrase);
// 		$not_in_lang_file = 0;

		//if($phrase_value ==''){
			$phrase_value = ucwords(str_replace('_',' ',$phrase));
			//$not_in_lang_file = 1;
		//}

		//insert_lang_key($phrase,$not_in_lang_file);
		return $phrase_value;
	}
}

if ( ! function_exists('get_user_language'))
{
	function get_user_language()
    {
		$user_language='english';
		
		if ( isset($_SESSION['user_language']) && ($_SESSION['user_language'] != ""))
		{
			$user_language=$_SESSION['user_language'];
		}
		
		return $user_language;
	}
}

if ( ! function_exists('get_controller'))
{
	function get_controller()
    {
    	$ci =& get_instance();
    	return $ci->router->fetch_class();
	}
}

if ( ! function_exists('get_method'))
{
	function get_method()
    {
    	$ci =& get_instance();
    	return $ci->router->fetch_method();
	}
}

if ( ! function_exists('insert_lang_key'))
{
	function insert_lang_key($phrase , $not_in_lang_file)
	{
		$controller = get_controller();
		$method = get_method();
		$language = $_SESSION['user_language'];

		$ci =& get_instance();
		$ci->load->database();

		$query = $ci->db->query("select * from ".get_system_db().".language_keys where language_key ='$phrase' and controller = '$controller' and method = '$method' and language = '$language'");
		if($query->num_rows() == 0)
		{
		   $query = $ci->db->query("insert into ".get_system_db().".language_keys (language_key,controller,	method,not_in_lang_file,language)values('$phrase','$controller','$method',$not_in_lang_file,'$language')");
		}
	}
}


// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */