<?php
/**
 * Desc:
 * Author: Ivone <i@ivone.me>
 * Date: 2022/6/23
 * Time: 15:49
 */

namespace Coords;

class Rectangle
{

    private $_west;
    private $_north;
    private $_east;
    private $_south;

    /**
     * China region - raw data
     * @var null
     */
    private static $_region = null;

    /**
     * China excluded region - raw data
     * @var null
     */
    private static $_exclude = null;

    /**
     * Rectangle constructor.
     * @param float $lng1
     * @param float $lat1
     * @param float $lng2
     * @param float $lat2
     */
    public function __construct($lng1, $lat1, $lng2, $lat2)
    {
        $this->_west  = min($lng1, $lng2);
        $this->_north = max($lat1, $lat2);
        $this->_east  = max($lng1, $lng2);
        $this->_south = min($lat1, $lat2);
    }

    /**
     * @param float $lon
     * @param float $lat
     * @return bool
     */
    public function contain($lon, $lat)
    {
        return $this->_west <= $lon && $this->_east >= $lon && $this->_north >= $lat && $this->_south <= $lat;
    }

    /**
     * initData
     */
    private static function initData()
    {
        if (!self::$_region) {
            self::$_region = [];
            self::$_region[] = new self(79.446200, 49.220400, 96.330000, 42.889900);
            self::$_region[] = new self(109.687200, 54.141500, 135.000200, 39.374200);
            self::$_region[] = new self(73.124600, 42.889900, 124.143255, 29.529700);
            self::$_region[] = new self(82.968400, 29.529700, 97.035200, 26.718600);
            self::$_region[] = new self(97.025300, 29.529700, 124.367395, 20.414096);
            self::$_region[] = new self(107.975793, 20.414096, 111.744104, 17.871542);
        }

        if (!self::$_exclude) {
            self::$_exclude = [];
            self::$_exclude[] = new self(119.921265, 25.398623, 122.497559, 21.785006);
            self::$_exclude[] = new self(101.865200, 22.284000, 106.665000, 20.098800);
            self::$_exclude[] = new self(106.452500, 21.542200, 108.051000, 20.487800);
            self::$_exclude[] = new self(109.032300, 55.817500, 119.127000, 50.325700);
            self::$_exclude[] = new self(127.456800, 55.817500, 137.022700, 49.557400);
            self::$_exclude[] = new self(131.266200, 44.892200, 137.022700, 42.569200);
            self::$_exclude[] = new self(73.124600, 35.398637, 77.948114, 29.529700);
        }
    }

    /**
     * 判断是否是国内
     * @param float $lon
     * @param float $lat
     * @return bool true: 国内 | false: 国外
     */
    public static function isInChina($lon, $lat)
    {
        self::initData();
        foreach (self::$_region as $region) {
            if ($region->contain($lon, $lat)) {
                foreach (self::$_exclude as $exclude) {
                    if ($exclude->contain($lon, $lat)) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

}