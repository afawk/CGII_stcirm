<?php

namespace ImageSegmentation;

class Rounded
{
    public static function get_center_val(array $content) : int
    {
        sort($content);
        return $content[floor(count($content)/2)];
    }

    public static function rearrange_to_limit(array $content, int $limit) : array
    {
        $max_val = array_reduce($content, function ($carry, $color) {
            $carry = floatval($carry);
            $color = floatval($color);

            return ($color > $carry) ? $color : $carry;
        }, 0.0);

        $tax_div = $max_val / $limit;

        $content = array_map(function ($val) use ($tax_div) {
            $val = floatval($val);
            return ($val > 0) ? floor($val / $tax_div) : $val;
        }, $content);

        return $content;
    }

    public static function create_histogram(array $content, int $limit) : array
    {
        $histogram = [];
        $lines = count($content);

        for ($i = 0; $i < $lines; $i++) {
            $color = $content[$i];

            if (!isset($histogram[$color])) {
                $histogram[$color] = 0;
            }

            $histogram[$color]++;
        }

        // Sem cor nenhuma
        unset($histogram[0]);

        $histogram = Rounded::rearrange_to_limit($histogram, $limit);

        for ($i = 0; $i < 255; $i++) {
            $qtd = $histogram[$i] ?? 0;
            $histogram[$i] = $qtd;
            //$histogram[$i] = abs($qtd - $limit);
        }

        ksort($histogram);
        unset($histogram[255]);

        return $histogram;
    }
}