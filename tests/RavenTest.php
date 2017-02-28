<?php

use otsec\yii2\sentry\Raven;
use PHPUnit\Framework\TestCase;

/**
 * @covers Raven
 */
class RavenTest extends TestCase
{
    public function testCreatePublicDsn()
    {
        $privateDsn = 'https://0e3213f0349b12dac22697c49febd1eb:6c4144b50be7619288a69bfd0305ea6a@sentry.io/12345';
        $publicDsn = 'https://0e3213f0349b12dac22697c49febd1eb@sentry.io/12345';

        $raven = new Raven();

        $this->assertEquals(
            $publicDsn,
            $raven->createPublicDsn($privateDsn)
        );
    }
}