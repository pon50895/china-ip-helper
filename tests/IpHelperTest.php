<?php

use ChinaIpHelper\IpHelper;
use PHPUnit\Framework\TestCase;

class IpHelperTest extends TestCase
{
    static protected $ipProvider;

    /**
     * @depends      testSplitIpString
     * @depends      testCheckIsChinaIp
     * @dataProvider ipProvider
     * @covers       ChinaIpHelper\IpHelper::checkIpCountry
     */
    public function testCheckIpCountry(string $ip, $country)
    {
        $ipHelper = new IpHelper();
        $this->assertSame($ipHelper->checkIpCountry($ip), $country);
    }

    /**
     * @depends testCheckIsIpInInterval
     * @covers  ChinaIpHelper\IpHelper::checkIsChinaIp
     */
    public function testCheckIsChinaIp()
    {
        $ipHelper = new IpHelper();
        for ($i = 0; $i < 100; $i++) {
            $myIpIndex = rand(0, 2);
            $ipSet = $this->ipSetGenerator($myIpIndex);

            if ($myIpIndex == 1) {
                $this->assertTrue(
                    $ipHelper->checkIsChinaIp(
                        $ipSet["myIp"],
                        [$ipSet["startIp"], $ipSet["endIp"]]
                    ),
                    sprintf(
                        "myIp:%s, startIp:%s, endIp:%s, myIpIndex:%s",
                        $ipSet["myIp"],
                        $ipSet["startIp"],
                        $ipSet["endIp"],
                        $myIpIndex
                    )
                );
            } else {
                $this->assertFalse(
                    $ipHelper->checkIsChinaIp($ipSet["myIp"], [
                    $ipSet["startIp"],
                    $ipSet["endIp"]
                ]),
                    sprintf(
                        "myIp:%s, startIp:%s, endIp:%s, myIpIndex:%s",
                        $ipSet["myIp"],
                        $ipSet["startIp"],
                        $ipSet["endIp"],
                        $myIpIndex
                    )
                );
            }
        }
    }

    /**
     * @covers ChinaIpHelper\IpHelper::splitIpString
     * @return void
     */
    public function testSplitIpString()
    {
        $ipHelper = new IpHelper();
        $handle   = fopen(IpHelper::IP_LIST_RESOURCE, "r");
        while (1) {
            $ipPairString = trim(fgets($handle));
            if (!$ipPairString) {
                break;
            }

            $ipPair = $ipHelper->splitIpString($ipPairString);
            $this->assertIsArray($ipPair);
            $this->assertIsString($ipPair[0]);
            $this->assertIsString($ipPair[1]);
            $this->assertIsInt(ip2long($ipPair[0]));
            $this->assertIsInt(ip2long($ipPair[1]));
        }
    }

    /**
     * @covers ChinaIpHelper\IpHelper::checkIsIpInInterval
     * @return void
     */
    public function testCheckIsIpInInterval()
    {
        $ipHelper = new IpHelper();
        for ($i = 0; $i < 100; $i++) {
            $myIpIndex = rand(0, 2);
            $ipSet = $this->ipSetGenerator($myIpIndex);

            if ($myIpIndex == 1) {
                $this->assertTrue(
                    $ipHelper->checkIsIpInInterval($ipSet["myIp"], $ipSet["startIp"], $ipSet["endIp"]),
                    sprintf("myIp:%s, startIp:%s, endIp:%s", $ipSet["myIp"], $ipSet["startIp"], $ipSet["endIp"])
                );
            } else {
                $this->assertFalse(
                    $ipHelper->checkIsIpInInterval($ipSet["myIp"], $ipSet["startIp"], $ipSet["endIp"]),
                    sprintf("myIp:%s, startIp:%s, endIp:%s", $ipSet["myIp"], $ipSet["startIp"], $ipSet["endIp"])
                );
            }
        }
    }

    /**
     * @coversNothing
     * @return array[]
     */
    static public function ipProvider()
    : array
    {
        return [
            'china Ip 1'  => ["61.170.227.225", IpHelper::IP_COUNTRY_CN],
            'china Ip 2'  => ["101.24.167.36", IpHelper::IP_COUNTRY_CN],
            'china Ip 3'  => ["183.198.73.212", IpHelper::IP_COUNTRY_CN],
            'china Ip 4'  => ["27.185.22.241", IpHelper::IP_COUNTRY_CN],
            'Taipei Ip 1' => ["61.216.18.75", IpHelper::IP_COUNTRY_OTHER],
            'Taipei Ip 2' => ["122.116.238.164", IpHelper::IP_COUNTRY_OTHER]
        ];
    }

    /**
     * @param $myIpIndex
     * @return array
     */
    public function ipSetGenerator($myIpIndex)
    : array
    {
        $array = [];
        for ($i = 0; $i < 3; $i++) {
            $rand1         = rand(0, 255);
            $rand2         = rand(0, 255);
            $rand3         = rand(0, 255);
            $rand4         = rand(0, 255);
            $index         = ((($rand1 * 256) + $rand2) * 256 + $rand3) * 256 + $rand4;
            $array[$index] = sprintf("%s.%s.%s.%s", $rand1, $rand2, $rand3, $rand4);
        }
        ksort($array);

        switch ($myIpIndex) {
            case 0:
                $endIp   = array_pop($array);
                $startIp = array_pop($array);
                $myIp    = array_pop($array);
                break;
            case 1:
                $endIp   = array_pop($array);
                $myIp    = array_pop($array);
                $startIp = array_pop($array);
                break;
            case 2:
                $myIp    = array_pop($array);
                $endIp   = array_pop($array);
                $startIp = array_pop($array);
                break;
            default:
                break;
        }

        return [
            "myIp"    => $myIp,
            "startIp" => $startIp,
            "endIp"   => $endIp
        ];
    }
}
