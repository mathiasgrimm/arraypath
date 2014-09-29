<?php
namespace mathiasgrimm\ArrayPath;

class ArrayPath
{
    protected static $sSeparator = '/';
    
    public static function setSeparator($sSeparator)
    {
        $sPrevSeparator   = self::$sSeparator; 
        self::$sSeparator = $sSeparator;
        
        return $sPrevSeparator;
    }
    
    public static function getSeparator()
    {
        return self::$sSeparator;
    }
    
    public static function get($mIndex, $aSource, $mDefault = null)
    {
        $aPath   = explode(self::$sSeparator, $mIndex);
        $sEval   = "\$mItem = @\$aSource['" . implode("']['", $aPath) . "'];";
        
        eval($sEval);
        
        if (!$mItem) {
            $mItem = $mDefault;
        }
        
        return $mItem;
    }
    
    public static function set($mValue, $mIndex, &$aSource)
    {
        $aPath   = explode(self::$sSeparator, $mIndex);
        $sEval   = "\$aSource['" . implode("']['", $aPath) . "'] = \$mValue;";
        
        eval($sEval);
        
        
        return $mValue;
    }
    
    public static function remove($mIndex, &$aSource)
    {
        $aPath   = explode(self::$sSeparator, $mIndex);
        $sEval   = "\$mItem = @\$aSource['" . implode("']['", $aPath) . "'];";
        
        eval($sEval);
        
        $sEval = "unset(\$aSource['" . implode("']['", $aPath) . "']);";
        eval($sEval);
        
        return $mItem;
    }
    
    public static function exists($mIndex, $aSource)
    {
        $aPath      = explode(self::$sSeparator, $mIndex);
        $sPath      = '';
        $mCurSource = $aSource;
        $bExists    = true;
        
        foreach ($aPath as $sPath) {
            if (array_key_exists($sPath, (array) $mCurSource)) {
                $mCurSource  = $mCurSource[$sPath];
            } else {
                $bExists = false;
                break;
            }
        }
        
        return $bExists;

    }
    
    // will keep commented till i get the bug answered. The behavior of this class won't change at all
    // https://bugs.php.net/bug.php?id=68110
//     public static function exists($mIndex, $aSource)
//     {
        
//         $aPath   = explode(self::$sSeparator, $mIndex);
//         $sEval   = "\$bExists = @\$aSource['" . implode("']['", $aPath) . "'];";
        
//         eval($sEval);
        
        
//         return !isset($php_errormsg);
//     }
    
    
}