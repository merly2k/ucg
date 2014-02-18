<?php

/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */

/**
 * Description of send_as_file
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
function send_download($data, $filename)
{
    header("HTTP/1.1 200 OK");
    header("Content-Type: application/force-download; charset=windows-1251");
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    $ua = (isset($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:'';
    $isMSIE = preg_match('@MSIE ([0-9].[0-9]{1,2})@', $ua);
    if ($isMSIE) 
    {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
 
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    } 
    else 
    {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
    }
    echo $data;
}
?>
