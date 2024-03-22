<?php

// playerjs.com playlist generator v.3
// https://playerjs.com/docs/q=phpforplaylist

// default sort by alphabet
// options:
// ?reverse
// ?date
// ?date&reverse

$sort_by_date = false;

$reverse = false;

if(isset($_GET['reverse'])){
    $reverse = true;
}

if(isset($_GET['date'])){
    $sort_by_date = true;
}

$json = '[';

PlayerjsScanFolder('.');

function PlayerjsScanFolder_old($folder){
    global $json;
    foreach (scandir($folder) as $file){
        if(is_dir($folder.'/'.$file)){
            if($file!='..' && $file!='.'){
                $json.='{"title":"'.$file.'","folder":[';
                PlayerjsScanFolder($folder.'/'.$file);
                $json = chop($json,',');
                $json.=']},';
            }
        }else{
            PlayerjsAddFileToJson($file,$folder);
        }
    }
}

function PlayerjsScanFolder($folder){
    global $json;
    global $sort_by_date;
    $f = array();
    $j = 0;
    $ignored = array('.', '..', '.svn', '.htaccess');
    foreach (scandir($folder) as $file){
        if(is_dir($folder.'/'.$file)){
            if($file!='..' && $file!='.'){
                $json.='{"title":"'.$file.'","folder":[';
                PlayerjsScanFolder($folder.'/'.$file);
                $json = chop($json,',');
                $json.=']},';
            }
        }else{
            if($sort_by_date){
                $f[$file] = filemtime($folder.'/'.$file);
            }else{
                $f[$file] = $j;
                $j++;
            }
        }
    }
    //

    PlayerjsScanFiles($f,$folder);
}

function PlayerjsScanFiles($f,$folder){
    global $reverse;
    if($reverse){
        arsort($f);
    }else{
        asort($f);
    }
    $f = array_keys($f);
    foreach ($f as $file){
        PlayerjsAddFileToJson($file,$folder);
    }
}

function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);
    return $files;
}

function PlayerjsAddFileToJson($file,$folder){
    global $json;
    if($file){
        $ext = substr($file,strrpos($file,'.'));
        $except = ['.php','.jpg','.txt','.zip'];
        if(strpos($file,'.')>0 &&!in_array($ext,$except)){
            $filename = substr($file,0,strpos($file,'.'));
            $poster = '';

            $path =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}".pathinfo($_SERVER['PHP_SELF'], 1).'/'.($folder!='.'?$folder.'/':'');

            $path = str_replace('/./','/',$path);

            if(file_exists(($folder!='.'?$folder.'/':'').$filename.'.jpg')){
                $poster = ',"poster":"'.$path.($filename.'.jpg').'"';
            }
            $json.='{"title":"'.$filename.'","file":"'.$path.$file.'"'.$poster.'},';
        }
    }
}

$json = chop($json,',').']';

echo($json);

file_put_contents('playlist.txt', $json);

?>
