<?php
/**
 * ArrayPath
 *
 * @link      http://github.com/mathiasgrimm/arraypath for the canonical source repository
 * @copyright Copyright (c) 2014 Mathias Grimm. (http://github.com/mathiasgrimm)
 * @license   https://github.com/mathiasgrimm/arraypath/blob/master/LICENSE.txt Mathias Grimm License
 */
namespace MathiasGrimm\ArrayPath;

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
        $sPrevSeparator     = static::$sSeparator;
        static::$sSeparator = $sSeparator;

        return $sPrevSeparator;
    }

    /**
     * returns the current separator
     *
     * @return string
     */
    public static function getSeparator()
    {
        return static::$sSeparator;
    }

    /**
     * gets the value of an index $mIndex from the array $aSource
     * in the format idx1/idx2/idx3/... where "/" is the current separator
     *
     * $mDefault is returned in case the requested index does not exists
     *
     * @param array $aSource
     * @param mixed $mIndex
     * @param mixed $mDefault
     * @return mixed $mCurSource
     */
    public static function get($aSource, $mIndex, $mDefault = null)
    {
        $aPath      = explode(static::$sSeparator, $mIndex);
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
     * sets $mValue in the array $sSource for the given $mIndex key
     * where $mIndex is idx1/idx2/idx3/... and "/" is the current separator
     *
     * @param array $aSource
     * @param mixed $mIndex
     * @param mixed $mValue
     * @return mixed
     */
    public static function set(&$aSource, $mIndex, $mValue)
    {
        $aPath      = explode(static::$sSeparator, $mIndex);
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
     * removes the index $mIndex from the array $aSource
     * where $mIndex is idx1/idx2/idx3/... and "/" is the current separator
     *
     * returns the removed value or null if the index was not found
     *
     * @param array &$aSource
     * @param mixed $mIndex
     * @return mixed $mValue
     */
    public static function remove(&$aSource, $mIndex)
    {
        $aPath          = explode(static::$sSeparator, $mIndex);
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
     * @param array $aSource
     * @param mixed $mIndex
     * @return boolean
     */
    public static function exists($aSource, $mIndex)
    {
        $aPath      = explode(static::$sSeparator, $mIndex);
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

    public static function registerClassAlias($alias = 'A')
    {
        class_alias('\MathiasGrimm\ArrayPath\ArrayPath', $alias);
    }
}
