<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\Tests\Entity;

use Pantarei\OAuth2\Tests\Entity\Codes;
use Pantarei\OAuth2\Tests\WebTestCase;

/**
 * Test authorizes entity functionality.
 *
 * @author Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 */
class CodesTest extends WebTestCase
{
    public function testAbstract()
    {
        $data = new Codes();
        $data->setCode('f0c68d250bcc729eb780a235371a9a55')
            ->setClientId('http://democlient2.com/')
            ->setRedirectUri('http://democlient2.com/redirect_uri')
            ->setExpires(time() + 3600)
            ->setUsername('demousername2')
            ->setScope(array(
                'demoscope1',
                'demoscope2',
            ));
        $this->assertEquals('f0c68d250bcc729eb780a235371a9a55', $data->getCode());
        $this->assertEquals('http://democlient2.com/', $data->getClientId());
        $this->assertEquals('http://democlient2.com/redirect_uri', $data->getRedirectUri());
        $this->assertTrue($data->getExpires() > time());
        $this->assertEquals('demousername2', $data->getUsername());
        $this->assertEquals(array('demoscope1', 'demoscope2'), $data->getScope());
    }

    public function testFind()
    {
        $result = $this->app['oauth2.orm']->find('Pantarei\OAuth2\Tests\Entity\Codes', 1);
        $this->assertEquals('Pantarei\OAuth2\Tests\Entity\Codes', get_class($result));
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('f0c68d250bcc729eb780a235371a9a55', $result->getCode());
        $this->assertEquals('http://democlient2.com/', $result->getClientId());
        $this->assertEquals('http://democlient2.com/redirect_uri', $result->getRedirectUri());
        $this->assertTrue($result->getExpires() > time());
        $this->assertEquals('demousername2', $result->getUsername());
        $this->assertEquals(array('demoscope1', 'demoscope2'), $result->getScope());
    }

    public function testExpired()
    {
        $data = new Codes();
        $data->setCode('5ddaa68ac1805e728563dd7915441408')
            ->setClientId('http://democlient4.com/')
            ->setRedirectUri('http://democlient4.com/redirect_uri')
            ->setExpires(time() - 3600)
            ->setUsername('demousername4')
            ->setScope(array(
                'demoscope1',
            ));
        $this->app['oauth2.orm']->persist($data);
        $this->app['oauth2.orm']->flush();

        $result = $this->app['oauth2.orm']->getRepository('Pantarei\OAuth2\Tests\Entity\Codes')->findOneBy(array(
            'code' => '5ddaa68ac1805e728563dd7915441408',
        ));
        $this->assertTrue($result !== null);
        $this->assertTrue($result->getExpires() < time());
    }
}
