<?php
function findM3U8Url($channelNumber) {
    $channelMap = [
        1 => 'bein-sports-1',
        2 => 'bein-sports-2',
        3 => 'bein-sports-3',
        4 => 'bein-sports-4',
        5 => 'bein-sports-5',
        6 => 'bein-sports-max-1',
        7 => 'bein-sports-max-2',
        8 => 'tivibu-spor-1',
        9 => 'tivibu-spor-2',
        10 => 'tivibu-spor-3',
        11 => 'tivibu-spor-4',
        12 => 's-sport',
        13 => 's-sport-2',
        14 => 'exxen-sport-1',
    ];

    if (!isset($channelMap[$channelNumber])) {
        return null;
    }

    $channelName = $channelMap[$channelNumber];

    // Dinamik base URL'yi belirlemek için bir aralıkta döngü yap
    $baseUrlStart = 205;
    $baseUrlEnd = 999;

    for ($i = $baseUrlStart; $i <= $baseUrlEnd; $i++) {
        $baseUrl = "https://www.xyzsports$i.xyz/player.php?channel=" . $channelName;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if (preg_match('/(https?:\/\/[^\s"]+\/live\/[^\s"]+\/playlist\.m3u8)/', $response, $matches)) {
            return $matches[1];
        } elseif (preg_match('/(\/live\/[^\s"]+\/playlist\.m3u8)/', $response, $matches)) {
            $parsedUrl = parse_url($baseUrl);
            $finalUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $matches[1];
            return $finalUrl;
        }
    }

    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gpik TV</title>
    <style>
        *:not(input):not(textarea) {
          -moz-user-select: -moz-none;
          -khtml-user-select: none;
          -webkit-user-select: none;
          -o-user-select: none;
          -ms-user-select: none;
          user-select: none
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #000000;
            color: white;
            font-family: sans-serif;
            font-weight: 500;
            -webkit-tap-highlight-color: transparent;
            line-height: 20px;
            -webkit-text-size-adjust: 100%;
            text-decoration: none;
        }

        a {
            text-decoration: none;
            color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: rgba(23, 43, 67, 0.8);
            backdrop-filter: blur(5px);
            border-bottom: 1px solid #000;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 99999;
        }

        .logo {
            width: 55px;
            height: 55px;
            margin-right: 5px;
        }

        .title {
            font-size: 16px;
            margin-right: auto;
            color: #e1e1e1;
        }

        .subtitle {
            font-size: 16px;
        }

        .player {
            width: 100%;
            max-width: 600px;
            margin: 100px auto 20px; /* Burada margin-top değerini artırdık */
        }

        .channel-list {
            padding: 0;
            margin: 0;
            margin-top: 20px; /* Header ve player arasındaki boşluk için margin-top ekledik */
        }

        .channel-item {
            display: flex;
            align-items: center;
            background-color: #16202a;
            transition: background-color 0.3s;
            cursor: pointer;
            border-bottom: 2px solid #9400d3;
        }

        .channel-item:last-child {
            border-bottom: none;
        }

        .channel-item a {
            text-decoration: none;
            color: #e1e1e1;
            padding: 10px;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .channel-item img {
            width: 55px;
            height: 55px;
            border-radius: 0px;
            margin-right: 10px;
        }

        .channel-item a:hover {
            background-color: rgba(136, 141, 147, 0.9);
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://i.hizliresim.com/t75soiq.png" alt="Logo" class="logo">
        <div class="title">
            TITAN TV
            <div class="subtitle"></div>
        </div>
    </div>
    <div class="player">
        <iframe id="playerIframe" src="" frameborder="0" width="100%" height="auto" allowfullscreen></iframe>
    </div>
    <div class="channel-list">
        <?php
        $channels = [
            1 => 'bein-sports-1',
            2 => 'bein-sports-2',
            3 => 'bein-sports-3',
            4 => 'bein-sports-4',
            5 => 'bein-sports-5',
            6 => 'bein-sports-max-1',
            7 => 'bein-sports-max-2',
            8 => 'tivibu-spor-1',
            9 => 'tivibu-spor-2',
            10 => 'tivibu-spor-3',
            11 => 'tivibu-spor-4',
            12 => 's-sport',
            13 => 's-sport-2',
            14 => 'exxen-sport-1',
        ];

        foreach ($channels as $channelNumber => $channelName) {
            $m3u8Url = findM3U8Url($channelNumber);
            if ($m3u8Url) {
                echo "<div class='channel-item' data-url='{$m3u8Url}'><a><img src='https://i.hizliresim.com/t75soiq.png' alt='Logo'><span>{$channelName}</span></a></div>";
            } else {
                echo "<div class='channel-item' data-url='' class='disabled'><a><img src='https://i.hizliresim.com/t75soiq.png' alt='Logo'><span>{$channelName} (Yayın Bulunamadı)</span></a></div>";
            }
        }
        ?>
    </div>

    <script>
        document.querySelectorAll('.channel-item').forEach(item => {
            item.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const playerIframe = document.getElementById('playerIframe');
                if (url) {
                    playerIframe.src = `https://bradmax.com/client/embed-player/a75680a661c3aa3e5137f6923513738c12dae3e2_3496?mediaUrl=${encodeURIComponent(url)}`;
                }
            });
        });
    </script>
</body>
</html>
