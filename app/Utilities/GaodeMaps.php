<?php

namespace App\Utilities;

use GuzzleHttp\Client;

class GaodeMaps
{
    public static function geocodeAddress($address, $city, $state)
    {
        $address = urlencode($state . $city . $address);
        $api_key = config('services.gaode.ws_api_key');

        $response = (new Client())
            ->get('https://restapi.amap.com/v3/geocode/geo?address=' . $address . '&key=' . $api_key)
        ->getBody()
        ->getContents();

        $geocode_data = json_decode($response);

        $coordinates['lat'] = null;
        $coordinates['lng'] = null;

        if (!empty($geocode_data)
            && $geocode_data->status  // 0 表示失败，1 表示成功
            && isset($geocode_data->geocodes)
            && isset($geocode_data->geocodes[0])) {
            list($latitude, $longitude) = explode(',', $geocode_data->geocodes[0]->location);
            $coordinates['lat'] = $latitude;  // 经度
            $coordinates['lng'] = $longitude; // 纬度
        }

        return $coordinates;
    }
}
