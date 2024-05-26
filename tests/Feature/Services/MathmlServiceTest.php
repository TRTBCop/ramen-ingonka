<?php

use App\Services\MathmlService;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class)->beforeEach(function () {
    $this->ref = new ReflectionClass(MathmlService::class);
});

test('MathmlService.setMathmlToImage mml to image 생성', function () {
    $text = <<<TEXT
<math xmlns="http://www.w3.org/1998/Math/MathML"><mn>15</mn><mo>&#176;</mo><mo>&#60;</mo><mi>x</mi><mo>&#60;</mo><mn>60</mn><mo>&#176;</mo></math>
TEXT;

    /** @see MathmlService::setMathmlToImage() */
    $method = $this->ref->getMethod('setMathmlToImage');
    $method->setAccessible(true);
    $result = $method->invoke(new MathmlService(), $text);


    $this->assertTrue(str_contains($result, '<img'));
});


test('MathmlService.setFromCache 캐시생성', function () {
    $md5Key = '123';


    /** @see MathmlService::setFromCache() */
    $method = $this->ref->getMethod('setFromCache');
    $method->setAccessible(true);
    $method->invoke(new MathmlService(), $md5Key, [
        'svg' => '123',
        'width' => '30'
    ]);

    $this->assertTrue(cache()->has((new MathmlService())->getCachePrefix().$md5Key));
});
