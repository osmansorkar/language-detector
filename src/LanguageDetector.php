<?php
/**
 * Created by PhpStorm.
 * User: osman sorkar
 * Date: 1/17/2017
 * Time: 8:01 PM
 */

namespace OsmanSorkar\Language;

class LanguageDetector{
    /**
     * Language String
     * @return string | null
     */
    private $str;

    /**
     * @return string
     */
    private $language;

    /**
     * Language List Witch Range
     *
     * @var array
     * @return array
     */
    private $languages=[
        'english'=>[0,123],
        'bengali'=>[2432,2559],
        'devanagari'=>[2304,2431],
        'tamil'=>[2944,3031],
        'kannada'=>[3200,3327],
        'gujarati'=>[2689,2786],
        'telugu'=>[3073,3199],
        'arabic'=>[1536,1791]
    ];
    /**
     * @var int
     * @return int
     */
    private $percent=60;

    public function __construct($str=null,$p=60)
    {
        $this->setStr($str);
        $this->setPercent($p);
    }

    public function setStr($str){
        // Remove Space get for Match 100% Language due to space is english character
        $str = preg_replace('/\s+/', '', $str);
        $this->str=$str;
        $this->language=false;
        return $this;
    }

    public function getStr(){
        return $this->str;
    }

    public function getLanguage(){
        $this->process();
        return $this->language;
    }


    public function isLanguage($lan){
        $this->process();
        if($this->language===$lan){
            return true;
        }
        return false;
    }

    public function setPercent($int){
        if(is_int($int) && $int <= 100 ){
        $this->percent=$int;
        }
        return $this;
    }
    public function getPercent(){
        return $this->percent;
    }

    public function addLanguages($lan,$s,$e){
        $this->languages[$lan]=[$s,$e];
        return $this;
    }
    public function getLanguages(){
        return $this->languages;
    }

    public function removeLanguages($languages){
        if(is_array($languages)){
            foreach($languages as $language){
                if(key_exists($language,$this->languages)){
                    unset($this->languages[$language]);
                }
            }
            return $this;
        }
        if(key_exists($languages,$this->languages)) {
            unset($this->languages[$languages]);
        }
        return $this;
    }

    public function setLanguages($languages){
        if (is_array($languages)) {
            foreach ($languages as $key=> $language){
                if(!is_int($language[0])|| !is_int($language[1])){
                 return false;
                }
            }
            $this->languages = $languages;
            return true;
        }
        return false;
    }

    public function uniord($u) {
        // i just copied this function fron the php.net comments, but it should work fine!
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }

    public function process() {
        if($this->str==null){
            return false;
        }
        if(mb_detect_encoding($this->str) !== 'UTF-8') {
            $str = mb_convert_encoding($this->str,mb_detect_encoding($this->str),'UTF-8');
        }

        /*
        $str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
        $str = preg_split('//u',$str); <- this function woulrd probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
        */
        preg_match_all('/.|\n/u', $this->str, $matches);
        if(empty($matches)){
            return false;
        }
        $chars = $matches[0];
        foreach ($this->languages as $language=>$range){
        $match = 0;
        $latin_count = 0;
        $total_count = 0;
        foreach($chars as $char) {
            //$pos = ord($char); we cant use that, its not binary safe
            $pos = $this->uniord($char);
            //echo $char ." --> ".$pos.PHP_EOL;
            if($pos >= $range[0] && $pos <= $range[1]) {
                $match++;
            } else {
                $latin_count++;
            }
            $total_count++;
        }
            if($match===0){
                continue;
            }
        if(($match/$total_count) >= ($this->percent/100)) {
            // 60% arabic chars, its probably arabic
            $this->language=$language;
            return true;
        }
        }

        return false;
    }
}