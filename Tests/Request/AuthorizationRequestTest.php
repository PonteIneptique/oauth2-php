<?php

/*
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\Oauth2\Test\Request;

use Pantarei\Oauth2\Request\AuthorizationRequest;

/**
 * Test authorization code grant type functionality.
 *
 * @author Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 */
class AuthorizationRequestTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @expectedException \Pantarei\Oauth2\Exception\InvalidClientException
   */
  public function testvalidateRequestNoClientId()
  {
    $request = new AuthorizationRequest();
    $request->validateRequest();
  }
}
