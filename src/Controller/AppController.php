<?php

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class AppController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'user',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'users',
                'action' => 'dashboard',
            ],
            'logoutRedirect' => [
                'controller' => 'users',
                'action' => 'login',
            ],
            'authError' => 'No tiene permiso para acceder a la página solicitada.',
        ]);

    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if (!is_null($this->Auth->user('user_id'))) {
            $user = User::get_user($this->Auth->user('user_id'));
            $userProfileCode = $user->user_type;
            $this->set('Auth', $this->Auth);
            $this->set('userProfileCode', $userProfileCode);
            $this->set('authUser', $user);
        }
    }
}
