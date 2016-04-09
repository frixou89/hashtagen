<?php
namespace app\models;

use yii\base\Model;
use yii\helpers\StringHelper;
use yii\base\Security;

class Tag extends Model
{
    public $words;
    
    public $url; //Since we don't use a database we will be needing a public variable to inherit the $url value from the SiteController
    public $title;
    public $description;
    public $content;

    public $seperator;
    public $depth;

    //Validation rules
    public function rules()
    {
        return [
            ['url', 'required', 'message' => 'Please enter a URL'], //Add required rule for validation
            ['url', 'url'], //Add url rule for validation
            [['title', 'description', 'content', 'words', 'depth'], 'safe'], //Mark attributes as safe so they can pass validation
            ['seperator', 'default', 'value' => 'camelCase'], //Default value for depth
            ['depth', 'default', 'value' => 3], //Default value for depth
            ['seperator', 'in', 'range' => ['camelCase', 'underscore']], 
            ['depth', 'in', 'range' => ['1', '2', '3', '4']], 
        ];
    }

    //Labels
    public function attributeLabels()
    {
        return [
            'url' => 'Requested URL',
            'title' => 'Site Title',
        ];
    }

    public function getCleanContent() {
    	$content = $this->content;
        $step1 = strip_tags($content); //Strip HTML tags
        $step2 = stripslashes($step1); //Strip slashes
        //Limit words so it doesn't exceed process time limits. 
        //Limiting characters would make keywords lose their value.
        $step3 = StringHelper::truncateWords ( $step2, 500); 
        
        return $step3;
    }

    public function compare($array1, $array2, $defaults) {
    	$matchedWords = array_intersect($array1, $array2);
    	$randomDelimiter = '-' . \Yii::$app->security->generateRandomString(16) . '-';
        $arrayToString = implode($randomDelimiter, $matchedWords);
        $arrayToString .= $defaults;
        $justWords = preg_replace('/[^A-Za-z0-9\-]/', '' , $arrayToString);
    	$noSmallChars = preg_replace('/\b\w{1,3}\b/u', '', $justWords);
        $result = str_replace($randomDelimiter, ', ' , $noSmallChars);
        return $result;
    }

    public function hashtags($text1, $text2, $defaults = null, $seperator = 'camelCase') {
    	$matches = [];
    	$found = [];
    	$depth = $this->depth;
    	$array1 = $this->prepareKeywordArray($text1);
    	$array2 = $this->prepareKeywordArray($text2);
    	for ($i = $depth; $i > 0; $i--) { 
    		$ar1 = array_chunk( $array1, $i, true );
	    	foreach ($ar1 as $key1 => $string1) {
	    		$string1 = $this->seperateWords(implode(' ', $string1), $this->seperator);
	    		//$string1 = preg_replace('[^A-Za-z0-9]', '' , $string1);
	    		$ar2 = array_chunk ( $array2, $i, true );
	    		foreach ($ar2 as $key2 => $string2) {
	    			$string2 = $this->seperateWords(implode(' ', $string2), $this->seperator);
	    			//$string2 = preg_replace('[^A-Za-z0-9]', '' , $string2);
	    			similar_text($string1, $string2, $percent);
	    			$percent = round($percent, 2); 
	    			if ($percent > 0) {
	    				switch ($percent) {
	    					case ($percent > 50):
	    						$size = 50;
	    						break;
    						case ($percent < 14):
	    						$size = 14;
	    						break;
	    					default:
	    						$size = $percent;
	    						break;
	    				}
	    				if ((strlen($string2) < 20) && (strlen($string2) > 3) && (!array_search($string2, $found)) ) {
		    				array_push($matches, '<span data-toggle="tooltip" data-placement="top" title="Volume: '.$percent.'%" style="font-size: '.$size.'px;">#'.trim($string2).'</span>');
		    				array_push($found, $string2);
	    				}
	    			}
	    		}
	    	}
    	}
		array_unique($matches);
    	return implode(', ', $matches);
    }

    protected function prepareKeywordArray($text) {
    	$s = explode(' ', $text);
    	$result = [];
    	foreach ($s as $key => $value) {
    		$clean = preg_replace("/[^A-Za-z0-9]/", "", $value);
    		$clean = strtolower($clean);
    		array_push($result, $clean);
    	}
    	return $result;
    }

    protected function seperateWords($text, $seperator = 'camelCase') {
    	$result = "";
    	switch ($seperator) {
    		case 'camelCase':
    			$trimmed = trim($text);
    			$camel = ucwords(strtolower($trimmed));
    			$result = preg_replace('/\s+/', '', $camel);
    			break;

    		case 'underscore':
    			$trimmed = trim($text);
    			$camel = ucwords(strtolower($trimmed));
    			$result = preg_replace('/\s+/', '_', $camel);
    			break;    		

			default:
				$trimmed = trim($text);
    			$camel = ucwords(strtolower($trimmed));
    			$result = preg_replace('/\s+/', '', $camel);
    			break;
    	}
    	return $result;
    }
}