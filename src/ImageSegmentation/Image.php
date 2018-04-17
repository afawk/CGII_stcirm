<?php

namespace ImageSegmentation;

class Image {

    public static function get_by_pixel_array(string $file) : array
    {
        $content = file_get_contents($file);
        $content = trim(preg_replace('/[\r|\n|\r\n]+/is', ' ', $content));
        $content = preg_split("/[\t|\s]+/", $content);

        return Rounded::rearrange_to_limit($content, 255);
    }

    function show_gray(array $content)
    {
        $lines = count($content);
        $cache_allocate = [];
        $size = intval(sqrt($lines));
        $img = imagecreatetruecolor($size, $size);

        for ($i = 0; $i < $lines; $i++) {
            $color_val = $content[$i];

            if (!isset($cache_allocate[$color_val])) {
                $cache_allocate[$color_val] = imagecolorallocate(
                    $img,
                    $color_val,
                    $color_val,
                    $color_val
                );
            }

            $y = floor($i / $size);
            $x = $i % $size;

            imagesetpixel($img, $x, $y, $cache_allocate[$color_val]);
        }

        header('Content-Type: image/png');
        imagepng($img);
    }

    function show_histogram(array $content, int $heigth = 255, int $width = 210, array $marks = [])
    {
        for ($i = 0; $i < 255; $i++) {
            $content[$i] = abs($content[$i] - $width);
        }

        $img = imagecreatetruecolor($heigth, $width);
        $div_factor = floor($heigth / 255);

        for ($x = 0; $x < $heigth; $x++) {
            $_x = floor($x / $div_factor);

            for ($y = 0; $y < $width; $y++) {
                $color = 0;

                if (isset($content[$_x]) and $y >= $content[$_x]) {
                    $color = 230;
                }

                if (in_array($_x, $marks)) {
                    $color = 128;
                }

                $allocate = imagecolorallocate($img,
                    $color,
                    $color,
                    $color
                );

                imagesetpixel($img, $x, $y, $allocate);
            }
        }

        header('Content-Type: image/png');
        imagepng($img);
    }

    public function show_colorized(array $content, $callback)
    {
        $lines = count($content);
        $cache_allocate = [];

        $size = intval(sqrt($lines));
        $img  = imagecreatetruecolor($size, $size);

        for ($i = 0; $i < $lines; $i++) {
            $y = floor($i / $size);
            $x = $i % $size;

            $colors = $content[$i];

            /*$color = imagecolorallocate($img,
                $colors['red'] ?: $colors['intensity'],
                $colors['red'] ? 0 : $colors['intensity'],
                $colors['red'] ? 0 : $colors['intensity']
            );*/

            $color = $callback($img, $colors);

            imagesetpixel($img, $x, $y, $color);
        }

        header('Content-Type: image/png');
        imagepng($img);
    }
};