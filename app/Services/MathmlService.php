<?php

namespace App\Services;

use App\Models\MathmlImages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MathmlService
{
    private const CACHE_PREFIX = 'mathml_svg_';

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getCachePrefix(): string
    {
        return self::CACHE_PREFIX;
    }

    /**
     * svg를 모델에서 가져오기
     *
     * @param array $arrData
     * @param array $md5Keys
     * @return array
     */
    private function getFromModel(array $arrData, array $md5Keys): array
    {
        $mathmlImage = MathmlImages::whereIn('md5_key', $md5Keys)->get();

        $mathmlImage->each(function (MathmlImages $mathmlImage) use (&$arrData) {
            $arrData[$mathmlImage->md5_key] = $mathmlImage->toArray();
            cache::put(self::CACHE_PREFIX.$mathmlImage->md5_key, $arrData[$mathmlImage->md5_key]);
        });

        return $arrData;
    }

    /**
     * md5key 를 받아 svg를 캐시에 저장
     *
     * @param string $md5Key
     * @param array $value
     */
    private function setFromCache(string $md5Key, array $value): void
    {
        cache::put(self::CACHE_PREFIX.$md5Key, $value);
    }


    /**
     * md5key 를 받아 svg를 캐시에서 가져오기
     *
     * @param string $md5Key
     * @return array
     */
    private function getFromCache(string $md5Key): array
    {
        $key = self::CACHE_PREFIX.$md5Key;

        if (cache::has($key)) {
            $data = cache::get($key);
        } else {
            $data = [];
        }

        return $data;
    }

    /**
     * mathml을 svg로 변환한 뒤, 기존 mathml 코드를 대체해서 리턴
     *
     * @param string|array $text
     * @return array|string
     */
    public function setMathmlToImage(string|array $text): array|string
    {
        $returnTypeArray = false;

        $replaceText = $text;
        if (is_array($text)) {
            $returnTypeArray = true;
            $replaceText = json_encode($text);
        }

        preg_match_all('/<math.*?math>/', $replaceText, $matches);

        $noCaches = [];

        if (count($matches[0]) > 0) {
            $arrMml = array_unique($matches[0]); // 중복되는 mml 값들은 제거
            $arrData = [];

            foreach ($arrMml as $mml) {
                $md5Key = md5($mml);
                $replaceText = str_replace($mml, $md5Key, $replaceText); // mml을 md5 키값으로 치환
                $data = $this->getFromCache($md5Key);

                if ($data) {
                    // 캐시 데이터가 있으면 가져오기
                    $arrData[$md5Key] = $data;
                } else {
                    // 없으면 별도로 저장
                    $noCaches[$md5Key] = $mml;
                }
            }

            $md5Keys = array_keys($noCaches); // 캐시에서 찾지 못한 mml 데이터 md5 값을 추출
            $arrData = $this->getFromModel($arrData, $md5Keys); // 테이블에서 조회

            foreach ($arrData as $key => $cache) {
                if (array_key_exists($key, $noCaches)) {
                    // 테이블에서 찾은 mml은 제거
                    unset($noCaches[$key]);
                }
            }

            foreach ($noCaches as $mml) {
                // 없으면 최종적으로 매일국어 서버에서 svg 생성해서 가져오기
                $data = $this->getSvg($mml);
                $arrData[$data['md5_key']] = $data;
            }

            foreach ($arrData as $data) {
                // mml을 이미지로 치환
                $image = str_replace('data:image/svg+xml;charset=utf8,', '', $data['svg']);
                $image = json_encode(rawurldecode($image));
                $image = substr($image, 1);
                $image = substr($image, 0, -1);
                $replaceText = str_replace($data['md5_key'], $image, $replaceText);
            }
        }

        if ($returnTypeArray) {
            $replaceText = json_decode($replaceText, true);
            if ($replaceText === null && json_last_error() !== JSON_ERROR_NONE) {
                return $text;
            }
        } else {
            $replaceText = stripslashes($replaceText);
        }

        return $replaceText;
    }

    /**
     * mathml을 매일국어 mathtype 처리 로직을 통해 svg로 전달받음
     *
     * @param string $mml
     * @return array
     */
    private function getSvg(string $mml): array
    {
        $md5Key = md5($mml);
        $mml = stripslashes($mml);

        try {
            $command = 'php '.public_path('froala_wiris/integration/showimage.php').' '.escapeshellarg($mml);
            $output = shell_exec($command);
            $response = json_decode($output, true);
            Log::channel('mathtype')->info($response);
        } catch (\Exception $e) {
            // 5분에 한번씩
            Cache::remember('log_mathtype', 300, function () use ($e) {
                Log::channel('mathtype')->error($e->getMessage());
            });
        }

        $width = $response['result']['width'];
        $height = $response['result']['height'];
        $content = $response['result']['content'];

        $imageData = str_replace('\'', '%27', $content);
        $imageData = str_replace('"', '%22', $imageData);
        $imageData = str_replace('=', '%3D', $imageData);
        $imageData = str_replace('<', '%3C', $imageData);
        $imageData = str_replace('>', '%3E', $imageData);
        $imageData = str_replace(' ', '%20', $imageData);
        $imageData = str_replace('/', '%2F', $imageData);
        $imageData = str_replace(':', '%3A', $imageData);
        $imageData = str_replace('#', '%23', $imageData);

        //        $mml = str_replace('"', '%22', $mml);

        $svg = 'data:image/svg+xml;charset=utf8,'.$imageData;

        $data = [
            'md5_key' => $md5Key,
            'mml' => $mml,
            'svg' => $svg,
            'width' => $width,
            'height' => $height,
        ];

        MathmlImages::create($data);    // 테이블에 저장
        cache::put(self::CACHE_PREFIX.$md5Key, $data); // 캐시로 저장

        return $data;
    }
}
