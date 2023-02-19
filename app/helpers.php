<?php

use Spatie\Color\Hsl;

if (! function_exists('make_color')) {
    function make_color($value, $min = 0, $max = 100)
    {
        $ratio = $value;
        if ($min > 0 || $max < 1) {
            if ($value < $min) {
                $ratio = 1;
            } elseif ($value > $max) {
                $ratio = 0;
            } else {
                $range = $min - $max;
                $ratio = ($value - $max) / $range;
            }
        }

        $hue = ($ratio * 1.2) / 3.60;
        $rgb = hsl_to_rgb($hue, 1, 0.5);

        $r = round($rgb['r'], 0);
        $g = round($rgb['g'], 0);
        $b = round($rgb['b'], 0);

        return "rgb($r,$g,$b)";
    }
}

if (! function_exists('hsl_to_rgb')) {
    function hsl_to_rgb($h, $s, $l)
    {
        $r = $l;
        $g = $l;
        $b = $l;
        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
        if ($v > 0) {
            $m = $l + $l - $v;
            $sv = ($v - $m) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;

            switch ($sextant) {
                case 0:
                    $r = $v;
                    $g = $mid1;
                    $b = $m;
                    break;
                case 1:
                    $r = $mid2;
                    $g = $v;
                    $b = $m;
                    break;
                case 2:
                    $r = $m;
                    $g = $v;
                    $b = $mid1;
                    break;
                case 3:
                    $r = $m;
                    $g = $mid2;
                    $b = $v;
                    break;
                case 4:
                    $r = $mid1;
                    $g = $m;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $m;
                    $b = $mid2;
                    break;
            }
        }

        return ['r' => $r * 255.0, 'g' => $g * 255.0, 'b' => $b * 255.0];
    }
}

if (! function_exists('percent_to_color')) {
    function percent_to_color($value, $min, $max)
    {
        $percentage = bcmul(bcdiv(bcsub($value, $min), bcsub($max, $min), 2), 100);

        return 'bg-color-range-'.(int) round(bcdiv($percentage, 10, 2));
        $hue = ($percentage / 100) * (120 - 0);
        $hsl = Hsl::fromString('hsl('.$hue.', 100%, 50%)');
        $hex = $hsl->toHex();

        return $hex;
    }
}
