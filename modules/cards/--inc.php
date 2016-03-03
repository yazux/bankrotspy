<?php

function function_parce_page_tru($array){
    $baseurl='https://www.yandex.ru/';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $thispage = curl_exec($ch);
    curl_close($ch);
    sleep(3);
    
    $url=$array['query'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)");
    $thispage = curl_exec($ch);
    curl_close($ch);
    //echo '<textarea style="width:1200px; height:500px;">'.htmlspecialchars($thispage).'</textarea>';
    return $thispage;
}

function function_parce_page($array){
    $url=$array['query'];
    $ch = curl_init($url);
    //curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/45.0.2454.101 Chrome/45.0.2454.101 Safari/537.36');
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $thispage = curl_exec($ch);
    curl_close($ch);
    return $thispage;
}

function function_page_short($array){
    $result='';
    $page=explode($array['start'],$array['page']);
    $result=$page[$array['mode']];
    return $result;
}

function function_page_div_search($array){
    $result='';
    //echo '<textarea style="width:1200px; height:500px;">'.htmlspecialchars($array['page']).'</textarea>';
    $page=explode($array['start'],$array['page']);
    //echo count($page);
    return $page;
}

function function_page_div_search_vals($array){
    $result='';
    //echo '<textarea style="width:1200px; height:500px;">'.htmlspecialchars($array['page']).'</textarea>';
    $page=explode($array['val'],$array['page']);
    //echo count($page);
    return $page;
}