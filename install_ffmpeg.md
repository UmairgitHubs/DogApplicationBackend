# FFmpeg Installation Guide

This guide provides instructions for installing FFmpeg on different operating systems, which is required for the audio conversion functionality.

## Windows

### Option 1: Using Chocolatey (Recommended)

```bash
# Install Chocolatey if not already installed
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))

# Install FFmpeg
choco install ffmpeg
```

### Option 2: Manual Installation

1. Download FFmpeg from https://ffmpeg.org/download.html
2. Extract the files to a directory (e.g., `C:\ffmpeg`)
3. Add the `bin` folder to your system PATH
4. Restart your command prompt/PowerShell

### Option 3: Using Scoop

```bash
# Install Scoop if not already installed
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
irm get.scoop.sh | iex

# Install FFmpeg
scoop install ffmpeg
```

## macOS

### Option 1: Using Homebrew (Recommended)

```bash
# Install Homebrew if not already installed
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install FFmpeg
brew install ffmpeg
```

### Option 2: Using MacPorts

```bash
sudo port install ffmpeg
```

## Linux (Ubuntu/Debian)

```bash
# Update package list
sudo apt update

# Install FFmpeg
sudo apt install ffmpeg

# Verify installation
ffmpeg -version
```

## Linux (CentOS/RHEL/Fedora)

### CentOS/RHEL

```bash
# Enable EPEL repository
sudo yum install epel-release

# Install FFmpeg
sudo yum install ffmpeg
```

### Fedora

```bash
sudo dnf install ffmpeg
```

## Docker

If you're using Docker, you can use an image that includes FFmpeg:

```dockerfile
FROM php:8.1-fpm

# Install FFmpeg
RUN apt-get update && apt-get install -y ffmpeg

# Your Laravel application setup...
```

## Verification

After installation, verify FFmpeg is working:

```bash
ffmpeg -version
```

You should see output similar to:

```
ffmpeg version 4.4.2 Copyright (c) 2000-2021 the FFmpeg developers
built with gcc 9 (Ubuntu 9.4.0-1ubuntu1~20.04.1)
configuration: --prefix=/usr --extra-version=0ubuntu0.20.04.1 --toolchain=hardened --libdir=/usr/lib/x86_64-linux-gnu --incdir=/usr/include/x86_64-linux-gnu --arch=amd64 --enable-gpl --disable-stripping --enable-avresample --disable-filter=resample --enable-avisynth --enable-gnutls --enable-ladspa --enable-libaom --enable-libass --enable-libbluray --enable-libbs2b --enable-libcaca --enable-libcdio --enable-libcodec2 --enable-libflite --enable-libfontconfig --enable-libfreetype --enable-libfribidi --enable-libgme --enable-libgsm --enable-libjack --enable-libmp3lame --enable-libmysofa --enable-libopenjpeg --enable-libopenmpt --enable-libopus --enable-libpulse --enable-librsvg --enable-librubberband --enable-libshine --enable-libsnappy --enable-libsoxr --enable-libspeex --enable-libssh --enable-libtheora --enable-libtwolame --enable-libvidstab --enable-libvorbis --enable-libvpx --enable-libwavpack --enable-libwebp --enable-libx265 --enable-libxml2 --enable-libxvid --enable-libzmq --enable-libzvbi --enable-lv2 --enable-omx --enable-openal --enable-opencl --enable-opengl --enable-sdl2 --enable-libdc1394 --enable-libdrm --enable-libiec61883 --enable-chromaprint --enable-frei0r --enable-libx264 --enable-shared
libavutil      56. 70.100 / 56. 70.100
libavcodec     58.134.100 / 58.134.100
libavformat    58. 76.100 / 58. 76.100
libavdevice    58. 13.100 / 58. 13.100
libavfilter     7.110.100 /  7.110.100
libavresample   4.  0.  0 /  4.  0.  0
libswscale      5.  9.100 /  5.  9.100
libswresample   3.  9.100 /  3.  9.100
libpostproc    55.  9.100 / 55.  9.100
```

## Testing Audio Conversion

Test the audio conversion functionality:

```bash
# Test with a sample audio file
ffmpeg -i input.m4a -acodec libmp3lame -b:a 192k -ar 44100 -ac 2 -q:a 2 -f mp3 -y output.mp3
```

## Troubleshooting

### FFmpeg Not Found

-   Ensure FFmpeg is installed and in your system PATH
-   Restart your terminal/command prompt after installation
-   On Windows, restart your IDE/development environment

### Permission Issues

-   On Linux/macOS, you might need to use `sudo` for installation
-   Ensure the user running the Laravel application has access to FFmpeg

### Codec Issues

-   Some distributions might not include all codecs
-   Install additional codec packages if needed:

    ```bash
    # Ubuntu/Debian
    sudo apt install ubuntu-restricted-extras

    # CentOS/RHEL
    sudo yum install rpmfusion-free-release
    sudo yum install ffmpeg
    ```

## Laravel Application Testing

After installing FFmpeg, test it in your Laravel application:

```php
// In tinker or a test route
$service = new \App\Services\AudioConversionService();
$isAvailable = $service->isFFmpegAvailable();
echo $isAvailable ? 'FFmpeg is available' : 'FFmpeg is not available';
```



