<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\Provider;

use Pantarei\OAuth2\Exception\InvalidRequestException;
use Pantarei\OAuth2\Exception\UnsupportedResponseTypeException;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authorization service provider for OAuth2.
 *
 * @author Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 */
class AuthorizationServiceProvider implements ServiceProviderInterface
{
  public function register(Application $app)
  {
    $app['oauth2.auth.default_options'] = array(
      'response_type' => array(
        'code' => 'Pantarei\OAuth2\Extension\ResponseType\CodeResponseType',
        'token' => 'Pantarei\OAuth2\Extension\ResponseType\TokenResponseType',
      ),
    );

    $app['oauth2.auth.options.initializer'] = $app->protect(function () use ($app) {
      static $initialized = FALSE;

      if ($initialized) {
        return FALSE;
      }
      $initialized = TRUE;

      if (!isset($app['oauth2.auth.options'])) {
        $app['oauth2.auth.options'] = $app['oauth2.auth.default_options'];
      }
      return TRUE;
    });

    $app['oauth2.auth.response_type.config'] = $app->share(function($app) {
      $app['oauth2.auth.options.initializer']();

      $configs = new \Pimple();
      foreach ($app['oauth2.auth.options']['response_type'] as $name => $options) {
        $configs[$name] = new $options($app);
      }
      return $configs;
    });

    $app['oauth2.auth.response_type'] = $app->share(function ($app) {
      $request = Request::createFromGlobals();
      $query = $request->query->all();

      // Prepare the filtered query.
      $params = array('client_id', 'redirect_uri', 'response_type', 'scope', 'state');
      $filtered_query = $app['oauth2.param.filter']($query, $params);
      foreach ($params as $param) {
        if (isset($query[$param])) {
          if (!isset($filtered_query[$param]) || $filtered_query[$param] !== $query[$param]) {
            throw new InvalidRequestException();
          }
        }
      }

      // response_type is required.
      if (!isset($filtered_query['response_type'])) {
        throw new InvalidRequestException();
      }

      // Check if response_type is supported.
      if (!isset($app['oauth2.auth.response_type.config'][$query['response_type']])) {
        throw new UnsupportedResponseTypeException();
      }

      // Create, build and return the respone type.
      $response_type = $app['oauth2.auth.response_type.config'][$query['response_type']];
      $response_type->buildType($query, $filtered_query);
      return $response_type;
    });
  }

  public function boot(Application $app)
  {
  }
}