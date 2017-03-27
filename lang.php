<?php
   
    $lang['zh']=include_once('zh.php');
    $lang['en']=  array_flip($lang['zh']);
    
    
    $GLOBALS=$lang;
    
    function lang($_lang, $_string)
    {
        if(isset($GLOBALS[$_lang][$_string]))
        {
            return $GLOBALS[$_lang][$_string];
        }
        else
        {
            return $_string;
        }
    }
    $_str="你好吗";
    echo $_str . PHP_EOL;
    echo "\t英文释义:" . lang('en',  $_str)  . PHP_EOL;
   
    $_str="How have you been";
    echo $_str . PHP_EOL;
    echo "\t中文释义:" . lang('zh', $_str) . PHP_EOL;