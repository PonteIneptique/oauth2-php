<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\Model;

interface ScopeManagerInterface
{
    public function createScope();

    public function deleteScope(ScopeInterface $scope);

    public function findScopes();

    public function reloadScope(ScopeInterface $scope);

    public function updateScope(ScopeInterface $scope);
}