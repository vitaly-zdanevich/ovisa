<?php set_time_limit(30);
if($_SERVER['REQUEST_URI'] === '/mail.js'){
 require_once('./mail.js');
} elseif($_SERVER['REQUEST_URI'] === '/mail.php'){
 require_once('./mail.php');
} elseif($_SERVER['REQUEST_URI'] === '/robots.txt'){
 require_once('./robots.txt');
} else {
  $content = curl_get($site.$_SERVER['REQUEST_URI']);
  $content = str_replace('</head>', file_get_contents('head.htm').'</head>', $content);
  $content = str_replace('</body>', file_get_contents('footer.htm').'</body>', $content);
  $content = str_replace('<!-- This site was created in Webflow. http://www.webflow.com-->', '', $content);
  $content = str_replace('<meta name="generator" content="Webflow">', '', $content);
  $attributes = "onclick,onblur,ondblclick,onfocus,onkeydown,onkeypress,onkeyup,onload,onmousedown,onmousemove,onmouseout,onmouseover,onmouseup,onsubmit,for,style";
  foreach(explode(',',$attributes) as $attr){$content = str_replace( '__'.$attr, $attr, $content); }
  echo $content;
}
function curl_get($url)
{
    $resource = curl_init();
    curl_setopt($resource, CURLOPT_URL, $url);
    curl_setopt($resource, CURLOPT_ENCODING , 'gzip, deflate');
    curl_setopt($resource, CURLOPT_RETURNTRANSFER , 1);
    curl_setopt($resource, CURLOPT_HEADER, 0);
    $curl_result = curl_exec($resource);
    curl_close($resource);
    return $curl_result;
}
