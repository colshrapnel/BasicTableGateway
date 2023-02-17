<?php

class UserGateway extends BasicTableGateway {
    protected $table = 'gw_users';
    protected $fields = ['email', 'password', 'name', 'birthday'];
}

