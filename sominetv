import youtube_dl
import re

def get_m3u8_url(youtube_url):
    ydl_opts = {
        'format': 'best',
        'extract_audio': False,
        'outtmpl': '%(title)s.%(ext)s',
    }
    with youtube_dl.YoutubeDL(ydl_opts) as ydl:
        info_dict = ydl.extract_info(youtube_url, download=False)
        m3u8_formats = [f['url'] for f in info_dict['formats'] if re.match(r'.*\.m3u8', f['url'])]
        if m3u8_formats:
            return m3u8_formats[0]
        else:
            return None

# YouTube canlı yayın URL'sini buraya yerleştirin
youtube_url = "https://www.youtube.com/live/W-23ZX_9tkY?si=INy8MbTSukbMfRyF"

m3u8_url = get_m3u8_url(youtube_url)
if m3u8_url:
    print("M3U8 URL:", m3u8_url)
else:
    print("M3U8 URL bulunamadı.")
