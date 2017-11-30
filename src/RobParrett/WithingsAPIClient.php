<?php

namespace RobParrett;

/**
 * A Partial implementation of a client for the Withings API
 */
class WithingsAPIClient
{
    private $oauth_token = "";
    private $oauth_token_secret = "";
    private $oauth_consumer_key = "";
    private $oauth_consumer_secret = "";

    const MEASTYPE_WEIGHT = 1;
    const MEASTYPE_HEIGHT = 4;
    const MEASTYPE_FAT_FREE_MASS = 5;
    const MEASTYPE_FAT_RATIO = 6;
    const MEASTYPE_FAT_MASS_WEIGHT = 8;
    const MEASTYPE_DIASTOLIC_BLOOD_PRESSURE = 9;
    const MEASTYPE_SYSTOLIC_BLOOD_PRESSURE = 10;
    const MEASTYPE_HEART_PULSE = 11;
    const MEASTYPE_SPO2 = 54;

    const CATEGORY_MEASUREMENT = 1;
    const CATEGORY_OBJECTIVE = 2;

    public function __construct($oauth_token, $oauth_token_secret, $oauth_consumer_key, $oauth_consumer_secret)
    {
        $this->oauth_token = $oauth_token;
        $this->oauth_token_secret = $oauth_token_secret;
        $this->oauth_consumer_key = $oauth_consumer_key;
        $this->oauth_consumer_secret = $oauth_consumer_secret;
    }

    public function getMeas($userid, $fromTimestamp = 0)
    {
        OAuthStore::instance("2Leg", [
            'consumer_key' => $this->oauth_consumer_key,
            'consumer_secret' => $this->oauth_consumer_secret,
        ]);

        $request = new OAuthRequester(
            "http://wbsapi.withings.net/measure?action=getmeas&userid=" . $userid . '&startdate=' . $fromTimestamp,
            "GET"
        );
        $response = $request->doRequest();

        return json_decode($response);
    }
}
