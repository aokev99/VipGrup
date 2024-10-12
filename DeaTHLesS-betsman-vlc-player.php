<?php
function fetch_url_with_headers($url, $referer, $origin) {
    $headers = array(
        'Accept: */*',
        'Origin: ' . $origin,
        'Referer: ' . $referer,
        'sec-ch-ua: "Not A;Brand";v="99", "Android WebView";v="127", "Chromium";v="127"',
        'sec-ch-ua-mobile: ?1',
        'sec-ch-ua-platform: "Android"',
        'User-Agent: Mozilla/5.0 (Linux; Android 14; Infinix X6833B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.6533.103 Mobile Safari/537.36'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

function find_dynamic_url($pattern_base) {
    for ($i = 10; $i <= 99; $i++) { // 10'dan 99'a kadar dene
        $test_url = "https://{$i}{$pattern_base}.online";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $test_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Eğer 200 yanıtı alırsak, bu doğru URL'dir
        if ($http_code == 200) {
            return $test_url;
        }
    }
    return null; // Doğru URL bulunamazsa
}

// Kanal numarasını URL parametresinden al
$channel_number = isset($_GET['url']) ? intval($_GET['url']) : 700; // Varsayılan değer 700
$pattern_base = 'betmantv'; // Dinamik URL'nin sabit kısmı

// Dinamik URL'yi bul
$dynamic_url = find_dynamic_url($pattern_base);

if ($dynamic_url) {
    $target_url = "https://0109wooxangel.shop/{$channel_number}/mono.m3u8";
    
    // Referer ve Origin'i dinamik URL'den al
    $referer = $dynamic_url . '/';
    $origin = $dynamic_url;

    // M3U8 içeriğini fetch et
    $response = fetch_url_with_headers($target_url, $referer, $origin);

    // M3U8 içeriğini yönlendir
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $response;
} else {
    echo "Dinamik URL bulunamadı.";
}
?>
