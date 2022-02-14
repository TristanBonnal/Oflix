<?php

namespace App\Tests\Service;

use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbApiTest extends KernelTestCase
{
    public function testSomething(): void
    {
        // (1) on démarre le FW Symfony
        $kernel = self::bootKernel();

        //$this->assertSame('test', $kernel->getEnvironment());
        //$routerService = static::getContainer()->get('router');
        $omdbApi = static::getContainer()->get(OmdbApi::class);

        $result = $omdbApi->fetchOmdb('Interstellar');

        $this->assertIsArray($result);
        $this->assertSame('Interstellar', $result['Title']);
    }
    public function testNotGood(): void
    {
        $kernel = self::bootKernel();

        $omdbApiService = static::getContainer()->get(OmdbApi::class);
        $result = $omdbApiService->fetchOmdb('Nain Porte Koi');
        
        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('Title', $result);
        
        $this->assertArrayHasKey('Error', $result);
        
        $this->assertSame('Movie not found!', $result['Error']);        
    }
}
