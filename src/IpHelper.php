<?php

namespace ChinaIpHelper;

class IpHelper
{
    /**
     * @resource https://github.com/mayaxcn/china-ip-list
     */
    const IP_LIST_RESOURCE = "https://raw.githubusercontent.com/mayaxcn/china-ip-list/master/chn_ip.txt";
    const IP_COUNTRY_CN = 'cn';
    const IP_COUNTRY_OTHER = 'other';

    /**
     * @param $myIp
     * @return string
     */
    public function checkIpCountry($myIp) :string
    {
        $handle = fopen(IpHelper::IP_LIST_RESOURCE, "r");
        while (1) {
            $ipPairString = trim(fgets($handle));
            if (!$ipPairString) {
                break;
            }

            $ipPair = self::splitIpString($ipPairString);
            if (!$ipPair) {
                return self::IP_COUNTRY_CN;
            }

            $result = $this->checkIsChinaIp($myIp, $ipPair);
            if ($result) {
                return self::IP_COUNTRY_CN;
            }
        }
        return self::IP_COUNTRY_OTHER;
    }

    /**
     * @param $myIp
     * @param $ipPair
     * @return bool
     */
    public function checkIsChinaIp($myIp, $ipPair) :bool
    {
        $ipCheckStart = $ipPair[0];
        $ipCheckEnd   = $ipPair[1];
        return $this->checkIsIpInInterval($myIp, $ipCheckStart, $ipCheckEnd);
    }

    /**
     * @param $ipPairString
     * @param $seperator
     * @return false|string[]
     */
    public function splitIpString($ipPairString, $seperator = null)
    {
        $seperator = $seperator ?? chr(32);
        return explode($seperator, $ipPairString);
    }

    /**
     * @param $myIp
     * @param $ipStart
     * @param $ipEnd
     * @return bool
     */
    public function checkIsIpInInterval($myIp, $ipStart, $ipEnd):bool
    {

        $myIpLong     = ip2long($myIp);
        $ipCheckStart = ip2long($ipStart);
        $ipCheckEnd   = ip2long($ipEnd);
        if ($ipCheckStart <= $myIpLong && $myIpLong <= $ipCheckEnd) {
            return true;
        }
        return false;
    }
}
