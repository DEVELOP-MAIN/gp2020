﻿<?php
set_time_limit(800);
include_once('class.cliente.php');
include_once('class.database.php');
$db = new Database();

//a todos estos idcliente
$idparticular = ""; //recibimos id por GET cuando es uno solo
$ids_a_cero = array(512,1024,1280,1536,1792,2048,2560,2816,3072,3328,1,513,769,1025,1281,1537,1793,2049,2305,2817,3073,3329,3585,770,1026,1538,1794,2050,2306,2562,2818,3074,3330,3586,3,515,771,1027,1283,2051,2563,2819,3331,3587,516,1028,1284,1540,1796,2052,2308,2820,3076,3332,3588,261,1029,1797,2053,2565,2821,3333,3589,262,1030,1286,1798,2054,2310,2566,2822,3078,3334,3590,7,263,1031,1799,2055,2311,2567,2823,3079,3591,8,520,776,1032,1288,1544,1800,2056,2312,2824,3336,3592,9,265,1033,1545,2057,2313,3081,3337,3593,10,266,778,1034,1546,1802,2314,2570,2826,3082,3338,3594,267,523,779,1291,1803,2315,2827,3595,268,1036,1292,1804,2060,2316,3084,3340,3596,13,269,525,1037,1293,2061,2317,2829,3085,3341,14,1294,1550,1806,3086,3342,3598,271,527,783,1039,1807,3087,3343,3599,272,528,784,1040,1296,1552,1808,2576,3088,3344,3600,17,529,785,1041,2321,2833,3089,3345,3601,18,530,1042,1298,1554,1810,2066,2322,2834,3090,3346,787,1043,1555,2067,2579,2835,3091,3347,3603,20,532,788,1044,1556,1812,2068,2324,2836,3092,533,1301,1557,2325,2581,2837,3093,534,790,1046,1302,1558,1814,2070,2326,2582,2838,3094,23,791,1047,1303,1559,1815,2071,2583,2839,3095,3351,24,792,1048,1304,2072,2328,2584,2840,3352,25,793,1049,1305,1561,1817,2073,2329,2585,3097,3353,26,538,794,1050,1306,1562,2074,2330,3098,3354,3610,27,1051,1307,1819,2075,2331,3355,28,1052,1308,1564,1820,2076,2844,3100,29,541,1053,1309,1565,1821,2077,2333,2845,3101,3357,3613,30,286,798,1054,1566,1822,2078,2846,3102,3358,3614,31,543,799,1055,1311,1567,1823,2079,2847,3359,3615,32,288,544,800,1056,1312,1568,1824,3104,3360,3616,33,801,1057,1313,1569,2081,2849,3105,3361,3617,34,1058,1314,1826,2082,2338,2850,3106,3362,3618,35,547,1059,1315,1571,1827,2083,2339,2851,3107,3363,3619,804,1316,1572,2340,2596,2852,3108,3364,3620,37,549,805,1061,1317,1829,2085,2853,3109,3365,3621,806,1062,1318,1574,1830,2598,2854,3110,3366,3622,551,1319,1575,1831,2087,2343,2599,2855,3367,3623,40,808,1320,1832,2344,2856,3368,3624,297,809,1065,1321,1577,1833,2089,2857,3113,3369,3625,42,1066,1322,1578,2090,2602,2858,3114,3370,3626,43,555,1067,1323,1579,2091,2347,2603,2859,3115,3371,3627,556,1068,1324,1580,1836,2092,2348,2604,2860,3116,3372,3628,45,813,1069,1325,1581,2093,2605,3117,3373,3629,46,1070,1326,1838,2094,2606,2862,3118,3374,3630,47,559,1071,1327,1839,2095,2607,2863,3119,3375,3631,48,304,1072,2096,2352,2608,2864,3376,3632,305,561,1073,1329,1585,2097,2353,2609,2865,3377,3633,818,1074,1330,1586,1842,2610,3378,3634,563,1075,1331,1587,2099,2355,2611,3123,3379,3635,52,308,564,1076,1332,1588,1844,2100,2612,3124,3380,3636,53,821,1077,1333,1589,1845,2101,2357,2613,2869,3381,3637,54,566,822,1078,1334,2102,2358,2614,2870,3638,55,567,1335,1591,1847,2103,2615,3383,3639,56,568,1336,1592,1848,2616,2872,3128,3384,3640,57,825,1081,1849,2105,2361,2617,2873,3385,3641,314,826,1594,1850,2106,2362,2618,3130,3386,3642,827,1851,2107,2363,2619,2875,3131,3387,3643,60,1084,1596,1852,2108,2876,3132,3644,1597,1853,2109,2365,2877,3133,3389,3645,62,574,1854,2110,2366,2622,2878,3134,3646,319,1087,2111,2367,2879,3135,3391,3647,832,1088,1344,1600,1856,2368,2880,3136,3648,321,1345,1857,2113,2369,3137,3649,1090,1346,1602,2114,2370,3394,3650,323,579,835,1091,1603,1859,2371,2627,3651,68,580,1348,1860,2628,3140,3396,3652,325,581,837,1093,1349,1861,2117,2373,2629,2885,3141,3397,3653,70,582,838,1350,1606,2118,2374,2630,3398,3654,1351,1607,2119,2375,2631,3143,3399,3655,1352,1608,1864,2120,2376,2632,3144,3400,3656,841,1097,1353,1609,1865,2121,2633,2889,3145,3401,3657,842,1354,1610,1866,2122,2378,2634,3146,3658,1099,1355,1867,2123,2635,3147,3659,844,1100,1356,1612,1868,2124,2636,3148,3404,3660,845,1101,1357,1613,1869,2125,2637,2893,3405,3661,590,846,1102,1358,1870,2126,2382,2638,3406,3662,591,1103,1359,1615,2127,2639,2895,3151,3407,3663,1104,1360,1872,2128,2384,2640,3152,3408,3664,593,849,1105,1361,1617,1873,2129,2385,2641,2897,3153,3409,82,850,1106,1362,1618,1874,2386,2642,2898,3154,3410,3666,1107,1363,1875,2387,3155,3411,3667,596,1108,1364,1620,1876,2132,2388,2900,3156,3412,3668,85,597,853,1109,1877,2133,2901,3157,3413,3669,854,1878,2134,2390,2646,2902,3158,3414,3670,87,343,1111,1367,1879,2135,2391,2647,2903,3415,3671,88,1112,1368,1624,1880,2136,2392,2904,3416,3672,345,857,1369,1881,2137,2905,3417,3673,602,1114,1370,1882,2138,2394,3418,3674,603,1115,1627,1883,2139,2395,2907,3419,604,860,1116,1372,1628,1884,2140,2396,2908,3420,3676,605,861,1629,1885,2141,2397,2909,3165,3421,3677,350,606,1118,1374,1886,2142,2398,2910,3422,1119,1631,1887,2143,2399,2655,3423,3679,352,608,864,1120,1632,1888,2144,2912,3424,3680,1121,1377,1633,1889,2145,2401,2913,98,866,1122,1634,1890,2146,2402,2658,2914,3426,99,1123,1635,2147,2403,3427,3683,100,1124,1892,2148,2404,2916,3172,3428,3684,357,869,1125,1381,1637,1893,2149,2405,2917,3429,3685,870,1126,1638,1894,2150,2406,2918,3430,3686,103,359,871,1127,1383,1639,2151,2407,2919,3431,616,872,1128,1384,1640,1896,2152,2920,3432,3688,361,617,873,1129,1641,1897,2153,2409,3177,3433,362,874,1130,1642,1898,2154,2410,2666,363,875,1131,1387,1643,1899,2155,2411,3179,3691,364,620,876,1132,1388,1644,2156,2412,3692,621,877,1133,1389,1645,1901,2157,2413,2925,3181,3693,110,366,622,1134,1390,1646,1902,2158,2414,2670,2926,3182,3694,111,623,1135,1647,1903,2159,2415,2671,2927,3183,3695,368,1136,1392,1648,1904,2160,2416,2928,3184,3440,3696,625,1137,1393,1905,2161,2417,3441,3697,114,1138,1394,1650,1906,2162,2418,2674,2930,627,883,1139,1395,1651,2163,2675,3187,3443,3699,116,628,1396,1652,1908,2164,2420,2676,3188,3444,3700,373,629,1141,1397,1653,1909,2165,2421,3189,3445,3701,1142,1398,1910,2166,2422,2934,3446,3702,375,1143,1399,1911,2167,2423,2679,2935,3447,3703,376,1144,1400,1656,1912,2168,2424,2936,3448,3704,377,889,1145,1401,1913,2169,2425,2937,3449,3705,122,1146,1402,1658,1914,2170,2426,2682,2938,3194,3450,3706,379,1147,1403,1659,1915,2171,2683,2939,3195,3451,3707,124,380,1148,1404,1660,2172,2428,2940,3196,3452,3708,637,1149,1405,1661,1917,2173,2685,3197,3453,3709,1150,1406,1662,1918,2174,2430,2942,3710,383,1151,1407,1663,1919,2175,2687,3199,3455,640,1152,1408,1664,2176,3200,3456,3712,385,641,1153,1409,1665,1921,2177,2433,2689,2945,3201,3457,3713,386,1154,1410,1922,2178,2434,2690,2946,3202,3458,3714,1411,1667,1923,2179,2947,3203,3715,644,1156,1412,1668,1924,2180,2948,3460,3716,133,645,1157,1413,1669,2181,2437,2693,2949,3205,3461,3717,134,646,1158,1414,1670,2182,2950,3206,3462,647,903,1159,1415,1671,1927,2183,2951,3207,648,904,1416,1672,2184,2696,2952,3208,3720,649,905,1161,1417,2441,2953,3209,3721,138,650,1162,1674,2186,2442,2954,3210,3466,3722,139,395,651,1163,1419,1675,1931,2187,2955,3211,3467,3723,396,652,1164,1676,1932,2188,2444,2700,2956,3212,3724,653,909,1165,1421,2189,2701,2957,3213,3469,3725,910,1166,1422,1678,1934,2190,2958,3214,3470,3726,655,911,1167,1423,1679,1935,2191,2447,2703,2959,3215,3471,3727,656,1168,1680,2192,2704,2960,3216,3472,3728,913,1169,1425,1937,2193,2705,2961,3217,3473,3729,146,914,1426,1682,1938,2194,2962,3218,3474,3730,659,915,1427,1683,2195,2451,2963,3219,3475,3731,660,1172,1428,1684,1940,2452,2964,3220,3476,3732,149,917,1173,1429,1685,1941,2197,2453,2709,2965,3221,3477,3733,406,662,1174,1686,1942,2454,2966,3222,3478,3734,407,663,919,1175,1431,1687,1943,2967,3223,3479,3735,408,920,1176,1688,1944,2456,2968,3224,3480,3736,409,921,1433,1689,2969,3225,3481,3737,922,1690,2202,2714,2970,3226,3738,411,667,1179,1435,1691,1947,2203,2459,2971,3227,3483,3739,412,1180,1436,1692,1948,2204,2460,3228,3484,3740,1181,1437,1693,2461,2717,2973,3229,3741,158,414,926,1182,1438,1694,1950,2206,2718,2974,3230,3486,3742,415,671,1951,3231,3487,3743,160,928,1184,1696,1952,2464,2976,3232,3488,3744,673,929,1697,1953,2977,3233,3489,3745,674,930,1442,1954,2210,2466,2978,3234,3490,3746,163,419,675,931,1187,1699,1955,2211,3235,3491,3747,420,932,1700,1956,2212,2468,2724,3236,3492,3748,421,933,1189,1445,1701,1957,2213,2725,2981,3237,3749,934,1190,1446,1702,1958,2726,3238,3494,3750,167,679,1191,1447,1703,1959,2727,3239,3495,3751,424,680,1960,2472,2728,2984,3240,3496,3752,681,1193,1449,1705,1961,2729,2985,3241,3497,3753,938,1194,1450,1706,1962,2986,3242,3498,3754,939,1195,1451,1707,1963,2731,2987,3243,3755,684,1196,1708,1964,2220,2732,3244,3500,3756,429,685,1197,1453,1709,1965,2221,2477,2989,3245,3501,3757,430,686,942,1198,1454,1710,1966,2222,2478,2990,3246,3502,3758,175,431,687,943,1199,1455,1711,1967,2223,2735,3247,3503,176,944,1200,1456,1712,1968,2480,2992,3248,3504,3760,433,945,1457,1713,1969,2481,2993,3249,3505,3761,690,946,1202,2226,2738,2994,3250,3762,179,435,691,1459,1715,1971,2227,2483,2739,3251,3507,3763,692,948,1204,1716,1972,2740,2996,3252,3764,949,1205,1461,1973,2229,2485,2741,3253,3509,3765,182,694,1718,1974,2230,2742,2998,3254,3510,3766,183,439,695,951,1463,1975,3255,3511,3767,184,696,952,1208,1720,1976,2488,2744,3256,3512,3768,441,697,1209,1465,1721,1977,2745,3257,3513,3769,186,442,954,1210,1466,1722,1978,2234,2490,2746,3258,3514,3770,187,443,699,1211,1467,2235,2491,2747,3259,3515,444,956,2492,2748,3260,3516,3772,701,957,1213,1469,1725,2493,2749,3261,3517,3773,190,702,1214,1470,1982,2238,2494,2750,3262,3518,703,1215,1471,2495,2751,3263,3519,3775,192,448,960,1216,1472,1984,2240,2752,449,961,1217,1473,1729,1985,2241,2497,2753,3265,3777,450,962,1218,1474,2242,2498,2754,3010,3266,707,963,1219,1475,1987,2499,2755,3523,3779,452,964,1732,1988,2244,2500,2756,3012,3268,3524,3780,197,965,1221,1477,1989,2501,2757,3525,3781,454,1222,1478,1734,2758,3270,3782,455,967,1479,1735,1991,3271,3783,456,968,1736,1992,2248,2504,2760,3016,3272,3528,3784,713,969,1225,1481,1993,2249,2505,2761,3017,3273,3785,458,714,970,1226,1994,2506,2762,3274,3786,971,1483,1995,2251,2507,2763,3275,3787,716,972,1228,1484,1996,2252,2508,2764,3020,3276,3788,717,973,1229,1485,1741,1997,2253,2509,2765,3021,3277,3533,3789,206,462,718,974,1230,1742,1998,2254,2510,2766,3022,3278,3534,3790,463,975,1231,1487,1743,1999,2255,2511,2767,3279,3791,464,976,1232,1488,1744,2000,2512,2768,3280,3792,977,1489,2001,2257,2513,2769,3281,3537,3793,722,978,1234,1490,1746,2002,2258,2770,3282,3538,3794,467,723,979,1235,2003,2515,2771,3027,3539,3795,212,724,980,1236,1748,2004,2260,2772,3028,3540,3796,213,469,981,1237,1493,1749,2005,2261,2773,3029,3541,3797,470,726,982,1238,1494,1750,2006,2518,2774,3030,3542,3798,471,727,983,1239,1495,2007,2775,3031,3287,3543,3799,728,1240,1752,2008,2520,2776,3032,3544,3800,985,1241,1497,1753,2009,2265,2777,3033,3545,3801,986,1242,1498,1754,2010,2778,3034,3290,3546,3802,731,987,1243,1499,2011,2779,3291,3803,732,988,1244,1756,2012,2524,2780,3292,3548,3804,733,989,1757,2013,2525,2781,3037,3293,3549,478,734,990,1246,1502,2014,2270,2526,3294,3550,3806,223,479,1247,1503,2015,2271,2527,3039,3295,3551,480,736,992,1248,1504,1760,2016,2528,2784,3040,3296,3552,481,993,1249,1505,1761,2017,2273,2785,3297,3553,226,738,994,1250,1762,2018,2530,2786,3042,3298,3554,3810,227,483,739,995,1251,1507,1763,2019,2275,2531,3043,3299,3555,3811,228,996,1252,1508,1764,2020,2276,2532,2788,3300,3556,3812,229,485,1253,1509,2021,2533,2789,3557,3813,486,998,1254,1510,2278,2534,2790,3558,3814,487,999,1511,2279,2791,3047,3303,3559,3815,488,744,1000,1256,1768,2024,2280,2536,2792,3048,3304,3560,3816,489,745,1001,1257,1513,1769,2281,2537,2793,3305,3561,3817,1002,1514,2026,2282,2794,3306,3562,3818,491,747,1003,1259,1515,1771,2027,2283,2795,3051,3307,3563,3819,492,1004,1260,1516,1772,2028,2284,2540,2796,3052,3308,3564,3820,237,1005,1261,1773,2029,2541,2797,3565,3821,494,1006,1262,1518,2030,2286,2542,2798,3822,1007,1263,2287,2543,2799,3567,1008,1264,1520,2032,2288,2544,2800,3056,3312,753,1009,1265,1521,2033,2289,2545,2801,3057,3313,498,754,1010,1266,1522,1778,2034,2290,2546,2802,3058,3314,3570,755,1011,1267,1523,1779,2035,2291,2547,3059,3571,1012,1268,1524,2036,2292,2548,2804,3060,3316,3572,245,757,1013,1269,1525,2037,2293,2549,2805,3061,3317,3573,502,1014,1270,1526,1782,2038,2550,2806,3062,3318,3574,503,1015,1271,1527,1783,2039,2551,2807,3063,3575,504,1016,1528,2040,2296,2552,2808,3064,3576,505,761,1017,1529,1785,2041,2297,2809,3065,506,762,1018,1274,1530,1786,2042,2554,2810,3066,3322,3578,2043,3579,1020,1532,1788,2044,2300,2556,3068,3324,509,765,1021,1277,1533,1789,2045,2301,2557,3069,3325,3581,254,510,1022,1278,1534,1790,2046,2302,2558,2814,3070,3326,767,1023,1279,1535,2047,2303,2559,3071,3327,3583); //aca rellenamos con todos los ids cuando son muchos
$desde = 0;
$hasta = count($ids_a_cero);

if(isset($_GET["d"])) $desde = $_GET["d"];
if(isset($_GET["h"])) $hasta = $_GET["h"];
if(isset($_GET["p"])) $idparticular = $_GET["p"];

if($idparticular=="") {
	$cli = new cliente();
	for ($i=$desde;$i<$hasta;$i++)
	{
		$idc = $ids_a_cero[$i];
		
		//si tiene algun canje entonces lo balanceo por el saldo
		if(count($cli->getCanjes($idc))>0) {
			$balance = 0;
			$balance = $cli->dameBalanceId($idc);
			$balance = -1*$balance;
			if($balance!=0) {
				$sql = "INSERT INTO puntos (idcliente,puntos,fecha,observaciones,motivo,fecha_carga) VALUES ($idc,$balance,'2019-02-22','VENCIMIENTO 2019', 'VENCIMIENTO 2019', '2019-02-22')";	
				$db->query($sql);
				echo $idc ."->". $balance."<BR>";
			}	
		} else {
			//si no tiene algun canje entonces le elimino los puntos
			$sql = "DELETE FROM puntos WHERE idcliente = $idc";	
			$db->query($sql);
			echo $idc ."-> delete all puntos <BR>";			
		}	
	}		
} else {
	$cli = new cliente();
	$idc = $idparticular;
	//si tiene algun canje entonces lo balanceo por el saldo
	if(count($cli->getCanjes($idc))>0) {	
		$balance = 0;
		$balance = $cli->dameBalanceId($idc);
		$balance = -1*$balance;
		if($balance!=0) {
			$sql = "INSERT INTO puntos (idcliente,puntos,fecha,observaciones,motivo,fecha_carga) VALUES ($idc,$balance,'2019-02-22','VENCIMIENTO 2019', 'VENCIMIENTO 2019', '2019-02-22')";	
			$db->query($sql);
			echo $idc ."->". $balance."<BR>";
		} else {
			echo "el balance ya era cero";
		}
	} else {
		//si no tiene algun canje entonces le elimino los puntos
		$sql = "DELETE FROM puntos WHERE idcliente = $idc";	
		$db->query($sql);
		echo "elimine todos los puntos";
	}	
}
?>