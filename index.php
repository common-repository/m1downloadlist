<?php
/*
Plugin Name: m1.DownloadList
Plugin URI: http://maennchen1.de
Description: This plugin easily displays the folders and files from a selected directory. It can be placed by shortcode in any post.
Author: maennchen1.de
Version: 0.19
Author URI: http://maennchen1.de
*/

defined ( 'ABSPATH' ) or die ( 'ERROR: no ABSPATH is set' );
load_plugin_textdomain('m1dll', false, basename( dirname( __FILE__ ) ) . '/languages' );
ini_set('display_errors', 1);
$m1dll_index = 0;

/**
 * 
 * return utf8 encoded string, if needed
 */
function m1dll_utf8_encode ($f)
{
    
	if (@mb_check_encoding($f, "UTF-8") == "1") return $f;
    else return utf8_encode($f);
    
} // m1dll_utf8_encode()

/**
 *
 * uasort Array
 */

abstract class m1dll_sortfilesclass
{

	public static $order;
	public static $dir;

	public static function m1dll_sortfiles($a, $b) {

		if (self::$order === 'filename') {

			// normalize filennames
			$a['filename'] = strtolower($a['filename']);
			$b['filename'] = strtolower($b['filename']);

		}

		if ($a['type'] == $b['type']) {

			if (self::$dir == "ASC") return (($a[self::$order] < $b[self::$order])?-1:1);
			else return (($a[self::$order] < $b[self::$order])?1:-1);

		} else {

			return (($a['type'] < $b['type'])?-1:1);

		}

	}


}

/**
 * 
 * @param Array $atts
 * @return string Shortcode
 */
function m1dll_shortcode( $atts ) {
	
	//define $atts
	if ($atts === "") {$atts = array();}
	
	if(!array_key_exists('path', $atts)) $atts['path'] = "";
	if(!array_key_exists('target', $atts)) $atts['target'] = "";
	if(!array_key_exists('sort', $atts)) $atts['sort'] = "ASC";
	if(!array_key_exists('sort-order', $atts)) $atts['sort-order'] = "filename";
	if(!array_key_exists('label', $atts)) $atts['label'] = "";
	if(!array_key_exists('nosize', $atts)) $atts['nosize'] = "";
	if(!array_key_exists('hidedirs', $atts)) $atts['hidedirs'] = "";
	if(!array_key_exists('filetype', $atts)) $atts['filetype'] = "";
	if(!array_key_exists('ftime', $atts)) $atts['ftime'] = "";
	if(!array_key_exists('filetime', $atts)) $atts['filetime'] = "";
	if(!array_key_exists('foldertime', $atts)) $atts['foldertime'] = "";
	if(!array_key_exists('date_format', $atts)) $atts['date_format'] = "";
	if(!array_key_exists('hidefilename', $atts)) $atts['hidefilename'] = "";
	if(!array_key_exists('hidefiletype', $atts)) $atts['hidefiletype'] = "";
	if(!array_key_exists('noext', $atts)) $atts['noext'] = "";
	if(!array_key_exists('nobreadcrumb', $atts)) $atts['nobreadcrumb'] = 0;
	if(!array_key_exists('d', $_REQUEST)) $request['d'] = ""; else $request['d'] = $_REQUEST['d'];
	if(!array_key_exists('m1dll_index_get', $_REQUEST)) $request['m1dll_index_get'] = ""; else $request['m1dll_index_get'] = $_REQUEST['m1dll_index_get'];
	
	//define atts end

    $m1dll_fileicon  = array(
            '*'    => plugins_url( '/icons/file.gif', __FILE__ ),
            'bz2'  => plugins_url( '/icons/rarfile.gif', __FILE__ ),
            'c'    => plugins_url( '/icons/cfile.gif', __FILE__ ),
            'cpp'  => plugins_url( '/icons/cppfile.gif', __FILE__ ),
            'doc'  => plugins_url( '/icons/docfile.gif', __FILE__ ),
			'docx'  => plugins_url( '/icons/docfile.gif', __FILE__ ),
            'exe'  => plugins_url( '/icons/exefile.gif', __FILE__ ),
            'h'    => plugins_url( '/icons/hfile.gif', __FILE__ ),
            'htm'  => plugins_url( '/icons/htmfile.gif', __FILE__ ),
            'html' => plugins_url( '/icons/htmfile.gif', __FILE__ ),
            'gif'  => plugins_url( '/icons/imgfile.gif', __FILE__ ),
            'gz'   => plugins_url( '/icons/zipfile.gif', __FILE__ ),
            'jpg'  => plugins_url( '/icons/imgfile.gif', __FILE__ ),
            'js'   => plugins_url( '/icons/jsfile.gif', __FILE__ ),
            'm'    => plugins_url( '/icons/mfile.gif', __FILE__ ),
            'mp3'  => plugins_url( '/icons/mpgfile.gif', __FILE__ ),
            'mpg'  => plugins_url( '/icons/mpgfile.gif', __FILE__ ),
            'pdf'  => plugins_url( '/icons/pdffile.gif', __FILE__ ),
            'png'  => plugins_url( '/icons/imgfile.gif', __FILE__ ),
            'ppt'  => plugins_url( '/icons/pptfile.gif', __FILE__ ),
			'pptx'  => plugins_url( '/icons/pptfile.gif', __FILE__ ),
            'rar'  => plugins_url( '/icons/rarfile.gif', __FILE__ ),
            'swf'  => plugins_url( '/icons/swffile.gif', __FILE__ ),
            'txt'  => plugins_url( '/icons/txtfile.gif', __FILE__ ),
            'xls'  => plugins_url( '/icons/xlsfile.gif', __FILE__ ),
			'xlsx'  => plugins_url( '/icons/xlsfile.gif', __FILE__ ),
            'zip'  => plugins_url( '/icons/zipfile.gif', __FILE__ ),
            'dir'  => plugins_url( '/icons/folder.gif', __FILE__ )
    );


    

    global $m1dll_index;

    /*---------------------------------------------------------------------------------------------------*/
    /* Output: */
    /*---------------------------------------------------------------------------------------------------*/
    
    //path
    if ($atts['path'] != "") {
        
		$subdir = "";
		$dirname = ABSPATH.str_replace("..","",rawurldecode($atts['path']));
    }
    else {
        
    	$subdir = "";
    	$dirname = ABSPATH . "wp-content/uploads";

    }
    
    // check if filetype filtering is enabled via the shortcode param 'filetype'
    if (strlen($atts['filetype']) != "") {
    	
    	// add commaseparated filetypes to filter. 'dir' is always contained
    	$filetypeFilter = array_merge(array('dir'), explode(',', $atts['filetype']));
    	
    }
    
    if ($request['d'] != ""  && $request['m1dll_index_get'] == $m1dll_index) {
        
        //path from URL
        $subdir = str_replace("..","",base64_decode(rawurldecode(strip_tags($request['d']))));
        $dirname.= $subdir;
        
    }
    
    $content = '';

    if (is_dir($dirname)) {

    	if ($atts['nobreadcrumb'] == '1') {
    	
    	}
    	else {
 
	        //breadcrumb
	        if (strlen($atts['label']) > 0) {
	            $subdir_path = '<strong>'.__('path', 'm1dll').':</strong> <a href="' . get_permalink(get_the_ID()) . '">'.__($atts['label'], 'm1dll').'</a>';
	        } else {
	            $subdir_path = '<strong>'.__('path', 'm1dll').':</strong> <a href="' . get_permalink(get_the_ID()) . '">'.__('downloads', 'm1dll').'</a>';
	        }
	
	        if ($subdir)
	        {
	                $ptmp = explode("/", $subdir);
	                $sp = array();
	                foreach($ptmp as $item)
	                {
	                        if ($item)
	                        {
	                                $sp[] = $item;
	                                $subdir_path.= '/<a href="' . 
	                                    esc_url( 
	                                        add_query_arg( 
	                                            array( 
	                                                'd' => base64_encode('/' . implode('/', $sp)), 
	                                                'm1dll_index_get' => $m1dll_index 
	                                                )
	                                        )
	                                    ) . '">' . m1dll_utf8_encode( $item ) . '</a>';
	                        }
	                }
	        }
	
	        $content.= '
	        <p class="m1dll_subdirpath">'.$subdir_path.'</p>
	        ';
        
    	} //breadcrumb
	    
	    $content.= '
        <ul class="m1dll_filelist">
        ';

        if ($dh = opendir($dirname))
        {
			$ar_content = array ();
			$i = 0;


			while (($file = readdir($dh)) !== false)
			{
				$filetime = "";
				$file_timestamp = 0;

				if ($file != "." && $file != ".." && in_array($file, explode(',', $atts['hidefilename'])) === false)
				{

					$target = '';
					//filepath
					$fpath = $dirname ."/". $file;

						if (is_dir($dirname ."/". $file) && $atts['hidedirs'] != "1")
						{
							//folder
							$href = add_query_arg(
								array(
									'd' =>  rawurlencode( base64_encode($subdir.'/'.$file) ),
									'm1dll_index_get' => $m1dll_index
									)
								);

							//size
							$printsize = '';

							//foldertime
							$file_timestamp = filemtime($fpath);
							$str_filetime = strtotime(get_date_from_gmt(date('Y-m-d H:i:s', $file_timestamp), 'Y-m-d H:i:s'));

							if ($atts['foldertime'] == "1" || $atts['ftime'] == "1") {
								$filetime = date_i18n(get_option('date_format') . ", " . get_option('time_format'), $str_filetime);
							}
							elseif (strlen($atts['ftime']) > 0) {
								$filetime = date_i18n(sanitize_text_field($atts['ftime']), $str_filetime);
							}
							elseif (strlen($atts['foldertime']) > 0) {
								$filetime = date_i18n(sanitize_text_field($atts['foldertime']), $str_filetime);
							}
							else {
								$filetime = "";
							}

							//icons
							$endung = "dir";

							$type = "d";
						}
						else
						{
							//filelink
							$href = get_site_url() . "/" . m1dll_utf8_encode ( substr($dirname, strlen(ABSPATH)) ."/". $file );

							//filesize
							$size = round(filesize($fpath)/1000, 2);
							if ($size >= 1000) { $size = round($size/1000, 2); $printsize = number_format($size,2,',','.').'&nbsp;MB'; }
							else { $printsize = number_format($size,2,',','.').'&nbsp;kb';}

							//filetime
							$file_timestamp = filemtime($fpath);
							$str_filetime = strtotime(get_date_from_gmt(date('Y-m-d H:i:s', $file_timestamp), 'Y-m-d H:i:s'));

							if ($atts['filetime'] == "1" || $atts['ftime'] == "1") {
								$filetime = date_i18n(get_option('date_format') . ", " . get_option('time_format'), $str_filetime);
							}
							elseif (strlen($atts['ftime']) > 0) {
								$filetime = date_i18n(sanitize_text_field($atts['ftime']), $str_filetime);
							}
							elseif (strlen($atts['filetime']) > 0) {
								$filetime = date_i18n(sanitize_text_field($atts['filetime']), $str_filetime);
							}
							else {
								$filetime = "";
							}

							//filename
							$endung = strtolower(substr($file, strrpos($file, ".")+1));

							if ($atts['target'] != "") {
								$target = ' target="'. $atts['target'] .'"';
							}
							$type = "f";
						}

						// skip file, if filetype filtering is enabled & filetype in blacklist
						if (strlen($atts['filetype']) > 0 && !in_array($endung, $filetypeFilter)) continue;

						// skip file, if hidefiletype filtering is enabled & filetype in blacklist
						if (in_array($endung, explode(',', $atts['hidefiletype']))) continue;

						if(array_key_exists($endung, $m1dll_fileicon) === false) $endung = "*";

						$ar_content[$i] = array (
								'filename' => m1dll_utf8_encode( $file ),
								'href' => $href,
								'bg-url' => $m1dll_fileicon[$endung],
								'target' => $target,
								'size' => $printsize,
								'mtime' => $filetime,
								'timestamp' => $file_timestamp,
								'type' => $type
						);
						$i++;
				}
			}
			closedir($dh);

			m1dll_sortfilesclass::$dir = $atts['sort'];
			m1dll_sortfilesclass::$order = $atts['sort-order'];
			uasort($ar_content, array('m1dll_sortfilesclass', 'm1dll_sortfiles'));

			foreach ($ar_content as $f) {
				$content.= '
							<li>

											<a href="'.$f['href'].'" class="test" style="background: url(\''. $f['bg-url'] .'\') left no-repeat; padding-left:20px;"'. $f['target'] .'>
											' . (($atts['noext']=="1" && $f['type']=="f")?str_replace(strtolower(substr($f['filename'], strrpos($f['filename'], "."))), '', $f['filename']):$f['filename']) . '
											</a>
											'. (($atts['nosize']!="1")?'<span class="m1dll_filesize">'.$f['size'].'</span>':'') .'
											'. (($f['mtime']!="")?'<span class="m1dll_filetime">'.$f['mtime'].'</span>':'') .'
							</li>
							';
			}
        }
        $content.= '
        </ul>
   		';
    }
    $m1dll_index++;
    return $content;
    
} // m1dll_shortcode ()

/**
 * add CSS to template
 */
function m1dll_css() {
	wp_enqueue_style( 'm1dll', plugins_url('main.css', __FILE__) );
} // m1dll_css()

add_action( 'wp_enqueue_scripts', 'm1dll_css' );
add_shortcode( 'm1dll', 'm1dll_shortcode' );  

?>