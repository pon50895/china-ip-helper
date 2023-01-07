# china-ip-helper
using china ip helper to help you check ip,
it give you 2 results: "cn" or "other"
that's all, having fun.
```
$ip = "8.8.8.8"; // google ip
$ipHelper = new ChinaIpHelper/IpHelper();
$ipHelper->checkIpCountry($ip); // other

$chinaIp = "1.0.1.0"; // example china ip
$ipHelper->checkIpCountry($ip); // cn
```
