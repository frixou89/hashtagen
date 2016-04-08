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

    //Validation rules
    public function rules()
    {
        return [
            ['url', 'required', 'message' => 'Please enter a URL'], //Add required rule for validation
            ['url', 'url'], //Add url rule for validation
            [['title', 'description', 'content', 'words'], 'safe'] //Mark attributes as safe so they can pass validation
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

    public function hashtags($array1, $array2, $defaults = null) {
    	$matches = [];
    	$found = [];
    	$depth = 2;
    	for ($i = $depth; $i > 0; $i--) { 
    		$ar1 = array_chunk( $array1, $i, true );
	    	foreach ($ar1 as $key1 => $value1) {
	    		$string1 = implode(' ', $value1);
	    		$string1 = preg_replace('/[^A-Za-z0-9\-]/', '' , $string1);
	    		$ar2 = array_chunk ( $array2, $i, true );
	    		foreach ($ar2 as $key2 => $value2) {
	    			$string2 = implode(' ', $value2);
	    			$string2 = preg_replace('/[^A-Za-z0-9\-]/', '' , $string2);
	    			similar_text($string1, $string2, $percent); 
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
		    				array_push($matches, '<span style="font-size: '.$size.'px;">'.$string2.'</span> ');
		    				array_push($found, $string2);
	    				}
	    			}
	    		}
	    	}
    	}
		array_unique($matches);
    	return implode(', #', $matches);
    }
}