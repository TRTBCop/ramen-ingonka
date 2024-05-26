<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (!function_exists('dbcode')) {
    function dbcode($key = null, $default = null): mixed
    {
        if (is_null($key)) {
            return app('config');
        }

        try {
            return app('config')->get('dailykor.dbcode.'.$key, $default);
        } catch (Throwable $e) {
            return $default;
        }
    }
}
if (!function_exists('fileTempMove')) {
    function fileTempMove($data = '', array|string|null $oldData = '', string $dir = '')
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // 배열 또는 객체의 경우 재귀적으로 함수 호출
                $data[$key] = fileTempMove($value, $oldData[$key] ?? '', $dir);
            }
        } elseif (is_string($data)) {
            //request 이미지 중에 존재 s3에 없는거는 추가,
            preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $data, $matches);
            foreach ($matches[1] as $tempUrl) {
                if (!str_contains($tempUrl, 'temp/images')) {
                    continue;
                }
                $imagePath = $dir.'/'.basename($tempUrl);

                $tempPath = Str::after($tempUrl, 'temp/images/');

                // 이미지 이동
                if (Storage::disk('s3')->move('temp/images/'.$tempPath, $imagePath)) {
                    $data = str_replace($tempUrl, Storage::disk('s3')->url($imagePath), $data);
                }
            }

            preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $oldData, $matches);
            foreach ($matches[1] as $oldImage) {
                if (str_contains($data, $oldImage)) {
                    continue;
                } //포함되어있다면 취소

                $path = $dir.Str::after($oldImage, $dir);

                // 이전 이미지가 새로운 html 에 없다면 삭제
                Storage::disk('s3')->delete($path);
            }
        }

        return $data;
    }
}

if (!function_exists('setting')) {
    function setting($key = null, $value = null)
    {
        if ($value) {
            cache()->forget('settings');
            Setting::updateOrCreate([
                'name' => $key,
            ], [
                'value' => $value,
            ]);
        }

        return Arr::get(cache()->remember('settings', 1000, function () {
            return Setting::pluck('value', 'name')->all();
        }), $key);
    }
}

function isJson($string): bool
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
