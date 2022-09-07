<?php

namespace App\Services;

use Ip2location\IP2LocationLaravel\Facade\IP2LocationLaravel;			//use IP2LocationLaravel class

class Ip2location
{
    public static function lookup($ip)
    {
        //Try query the geolocation information of 8.8.8.8 IP address
        $records = IP2LocationLaravel::get($ip, 'bin');
        $info['ipNumber'] = $records['ipNumber'];
        $info['ipVersion'] = $records['ipVersion'];
        $info['ipAddress'] = $records['ipAddress'];
        $info['countryCode'] = $records['countryCode'];
        $info['countryName'] = $records['countryName'];
        $info['regionName'] = $records['regionName'];
        $info['cityName'] = $records['cityName'];
        $info['latitude'] = $records['latitude'];
        $info['longitude'] = $records['longitude'];
        $info['timeZone'] = $records['timeZone'];
        $info['zipCode'] = $records['zipCode'];

        if (@$info['countryCode']) {
            return $info;
        }

        return false;
    }
}
