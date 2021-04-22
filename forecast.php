<?php

// Assuming you installed from Composer:
require "vendor/autoload.php";
include_once('libs/simple_html_dom.php');

use Goutte\Client;


$url = 'https://www.poznavach.com/obosnovani-futbolni-prognozi/botev-plovdiv-24-08-2019/';
$domain = 'http://www.betway1x2.com';
$client = new Client();

$crawler = $client->request('GET', $url);

$content =$crawler->filter('#primary');

$html = $content->html();
$content2 = str_replace("https://www.poznavach.com",$domain, $html);

$header = '<div class="container"><div id="primary" class="content-area">';
$footer = '</div></div>';
echo '<pre>';


$dom = str_get_html($content2);

//$dom->find('div', 1)->class = 'bar';

//$dom->find('span[class=ob-posts-stats-title]', 0)->innertext = 'foo';

$img = 'http://www.betway1x2.com/image/teams/';
foreach($dom->find('img') as $element){
//    echo $element->src . '<br>';
    $src = $element->src;
    $var = preg_split("/\//", $src);
//    print_r($var);
//    print_r(end($var));
    $element->src = $img. end($var);
    echo $element->src . '<br>';

}

$page = 'forecast';
foreach($dom->find('a') as $element){
//    echo $element->href . '<br>';
    $src =  rtrim($element->href, '/');
    $var = preg_split("/\//", $src);
//    print_r($var);
//    print_r(end($var));
    if (count($var) > 4) {
        $element->href = $domain . '/' . $var[3] . '?data=' .$var[3].'&key='. end($var);
    }
    echo $element->href . '<br>';

}

// Empty Unnecessary data
$dom->find('.tips-new .first-tips-new', 0)->innertext = '';
$dom->find('.tips-new .first-tips-new', 1)->innertext = '';
$dom->find('.tips-new .second-tips-new', 0)->innertext = '';
$dom->find('.tips-new .second-tips-new', 1)->innertext = '';

$dom->find('.entry-content > .fullwidth', 0)->innertext = '';

$dom->find('table[class=bookie-odds2]', 0)->innertext = '';
$dom->find('p[class=tags-style]', 0)->innertext = '';



$txt = $header.$dom.$footer;

$myfile = fopen("data/forecast.html", "w") or die("Unable to open file!");
fwrite($myfile, $txt);
fclose($myfile);





