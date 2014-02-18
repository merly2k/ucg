<?php
/**
 *http://company.yandex.ru/technology/mystem/noncommercial.xml
 * Парсер Mystem от компании Яндекс.
 * Первое что нужно, для использования приведенного ниже кода, - это выбрать 
 * подходящую версию парсера и, скачав ее, разместить на хостинге (сайте), 
 * установив для файла mystem права на исполнение.
 * Первый параметр функции это, собственно, строка данных,
 * а вторая - полный путь к папке с исполняемым файлом mystem.
 * @param type $q
 * @param type $path
 * @return type 
 */
function normalize_string($q, $path)
{
	$q = iconv('utf-8', 'windows-1251', mb_strtolower($q, 'utf-8'));
	$out = array();
	exec('echo "'.$q.'" | '.rtrim($path,'/ ').'/mystem -c', $out);
	$q = implode('', $out);
	$q = str_replace('}', ' ', $q);
	$q = trim(preg_replace('#\s+#is', ' ', $q));
	$q = explode(' ', $q);
	$out = '';
	foreach($q as $w)
	{
		$w = str_replace('?', '', $w);
		$w = explode('{', $w);
		if (count($w)<2||preg_match('#^(\d+|[a-z0-9A-Z]+)$#is', $w[0])) 
		{
			$out .= $w[0] . ' ';
		}
		else
		{
			$w = explode('|', $w[1]);
			$out .= $w[0] . ' ';
		}
	}
	return trim(iconv('windows-1251', 'utf-8', $out));
}
?>