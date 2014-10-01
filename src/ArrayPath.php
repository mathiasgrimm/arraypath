<?php
/**
 * ArrayPath
 *
 * @link      http://github.com/mathiasgrimm/arraypath for the canonical source repository
 * @copyright Copyright (c) 2014 Mathias Grimm. (http://github.com/mathiasgrimm)
 * @license   https://github.com/mathiasgrimm/arraypath/blob/master/LICENSE.txt Mathias Grimm License
 */
namespace mathiasgrimm\arraypath;

class ArrayPath
{
    /**
     * @var string
     */
    protected static $sSeparator = '/';
    
    /**
     * sets a new separator and returns the old one
     * 
     * @param string $sSeparator
     * @return string
     */
    public static function setSeparator($sSeparator)
    {
        $sPrevSeparator   = self::$sSeparator; 
        self::$sSeparator = $sSeparator;
        
        return $sPrevSeparator;
    }
    
    /**
     * returns the current separator
     * 
     * @return string
     */
    public static function getSeparator()
    {
        return self::$sSeparator;
    }
    
    /**
     * get the value of an index $mIndex from the array $aSource
     * in the format idx1/idx2/idx3/... where "/" is the current separator
     * 
     * $mDefault is returned in case the requested index does not exists
     * 
     * @param mixed $mIndex
     * @param array $aSource
     * @param mixed $mDefault
     * @return midex $mCurSource
     */
    public static function get($mIndex, $aSource, $mDefault = null)
    {
        $aPath      = explode(self::$sSeparator, $mIndex);
        $mPath      = '';
        $mCurSource = $aSource;
        $bExists    = true;
        
        foreach ($aPath as $mPath) {
            if (array_key_exists($mPath, (array) $mCurSource)) {
                $mCurSource  = $mCurSource[$mPath];
            } else {
                $bExists = false;
                break;
            }
        }
        
        if (!$bExists) {
            $mCurSource = $mDefault;
        }
        
        return $mCurSource;
    }
    
    /**
     * set $mValue in the array $sSource for the given $mIndex key
     * where $mIndex is idx1/idx2/idx3/... and "/" is the current separator
     * 
     * @param mixed $mValue
     * @param mixed $mIndex
     * @param array $aSource
     * @return mixed
     */
    public static function set($mValue, $mIndex, &$aSource)
    {
        $aPath      = explode(self::$sSeparator, $mIndex);
        $mCurSource = &$aSource;
        
        foreach ($aPath as $mPath) {
            if (array_key_exists($mPath, (array) $mCurSource)) {
                $mCurSource = &$mCurSource[$mPath];
            } else {
                $mCurSource[$mPath] = array();
            }
        }
        
        $mCurSource[$mPath] = $mValue;

        return $mValue;
    }
    
    /**
     * remove the index $mIndex from the array $aSource
     * where $mIndex is idx1/idx2/idx3/... and "/" is the current separator
     *
     * returns the removed value or null if the index was not found
     * 
     * @param mixed $mIndex
     * @param array &$aSource
     * @return mixed $mValue
     */
    public static function remove($mIndex, &$aSource)
    {
        $aPath          = explode(self::$sSeparator, $mIndex);
        $mCurSource     = &$aSource;
        $mLastIndex     = null;
        $mValue         = null;
        $iLastI         = count($aPath) > 1 ? count($aPath) - 2: count($aPath) - 1;
        $bExists        = true;
        $mVeryLastIndex = array_pop($aPath);

        $i = 0;
        foreach ($aPath as $i => $mPath) {
            if (array_key_exists($mPath, (array) $mCurSource)) {
                $mCurSource = &$mCurSource[$mPath];
                $mLastIndex = $mPath;
            } else {
                $bExists = false;
                break;
            }
        }

        if ($bExists && $i == $iLastI) {
            $mValue = $mCurSource[$mVeryLastIndex];
            unset($mCurSource[$mVeryLastIndex]);
        }
        
        return $mValue;
    }
    
    /**
     * checks if the given $mIndex index exists in the array $aSource
     * where $mIndex is idx1/idx2/idx3/... and "/" is the current separator
     * 
     * @param mixed $mIndex
     * @param array $aSource
     * @return boolean
     */
    public static function exists($mIndex, $aSource)
    {
        $aPath      = explode(self::$sSeparator, $mIndex);
        $mCurSource = $aSource;
        $bExists    = true;
        
        foreach ($aPath as $mPath) {
            if (array_key_exists($mPath, (array) $mCurSource)) {
                $mCurSource  = $mCurSource[$mPath];
            } else {
                $bExists = false;
                break;
            }
        }
        
        return $bExists;

    }
<<<<<<< HEAD
}
=======
    
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
>>>>>>> 5e9617bd5872fed2833d0a6f27931c39de392191
