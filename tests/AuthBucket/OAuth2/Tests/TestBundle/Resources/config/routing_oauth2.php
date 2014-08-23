<?php

/**
 * This file is part of the authbucket/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->get('/oauth2', 'authbucket_oauth2.tests.oauth2_controller:oauth2IndexAction')
    ->bind('oauth2');

$app->get('/oauth2/login', 'authbucket_oauth2.tests.oauth2_controller:oauth2LoginAction')
    ->bind('oauth2_login');

$app->get('/oauth2/authorize/http', 'authbucket_oauth2.authorize_controller:authorizeAction')
    ->bind('oauth2_authorize_http');

$app->match('/oauth2/authorize', 'authbucket_oauth2.tests.authorize_controller:authorizeAction')
    ->bind('oauth2_authorize');

$app->match('/oauth2/token', 'authbucket_oauth2.token_controller:tokenAction')
    ->bind('oauth2_token');

$app->match('/oauth2/debug', 'authbucket_oauth2.debug_controller:debugAction')
    ->bind('oauth2_debug');

$app->post('/oauth2/model/{type}.{_format}', 'authbucket_oauth2.model_controller:createModelAction')
    ->bind('oauth2_model_type_create')
    ->assert('_format', 'json|xml');

$app->get('/oauth2/model/{type}/{id}.{_format}', 'authbucket_oauth2.model_controller:readModelAction')
    ->bind('oauth2_model_type_read')
    ->assert('_format', 'json|xml');

$app->put('/oauth2/model/{type}/{id}.{_format}', 'authbucket_oauth2.model_controller:updateModelAction')
    ->bind('oauth2_model_type_update')
    ->assert('_format', 'json|xml');

$app->delete('/oauth2/model/{type}/{id}.{_format}', 'authbucket_oauth2.model_controller:deleteModelAction')
    ->bind('oauth2_model_type_delete')
    ->assert('_format', 'json|xml');

$app->get('/oauth2/model/{type}.{_format}', 'authbucket_oauth2.model_controller:listModelAction')
    ->bind('oauth2_model_type_list')
    ->assert('_format', 'json|xml');
