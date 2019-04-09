<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use App\Model\Entity\User;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('login');
    }

    // LOGIN

    public function login()
    {
        $this->viewBuilder()->setLayout('asdra-login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Usuario o Password inválido.'));
            }
        }
    }

    // LOGOUT
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }


    // INIT DASHBOARD

    public function initDashboard()
    {
        $this->autoRender = false;
        $session = $this->request->session();
        $session->delete('is_search');
        $session->delete('filter');
        return $this->redirect(['action' => 'dashboard']);
    }

    // DASHBOARD

    public function dashboard()
    {
        $session = $this->request->session();
        $this->viewBuilder()->setLayout('asdra-layout');
        $this->set('is_search', 0);

        // Búsqueda

        if ($this->request->is('post')) {
            $session->delete('is_search');
            $session->delete('filter');
    
            $filter = $this->request->getData()['filter'];

            $session->write('is_search', 1);
            $session->write('filter', $filter);
            
            // Get Users
            $users = $this->getUsers($this->Auth->user('user_id'), $filter);

            $this->set('is_search', 1);
            $this->set('filter', $filter);
        } else {
            $users = $this->getUsers($this->Auth->user('user_id'), null);
        }



        // Set Users
        $this->set(compact('users'));
    }


    public function initInCharge()
    {
        $this->autoRender = false;
        $session = $this->request->session();
        $session->delete('is_search');
        $session->delete('filter');
        return $this->redirect(['action' => 'inCharge']);
    }

    // Personas a Cargo

    public function inCharge()
    {
        $session = $this->request->session();
        $this->viewBuilder()->setLayout('asdra-layout');
        $this->set('is_search', 0);

        // Búsqueda

        if ($this->request->is('post')) {
            $session->delete('is_search');
            $session->delete('filter');
    
            $filter = $this->request->getData()['filter'];

            $session->write('is_search', 1);
            $session->write('filter', $filter);
            
            $users = $this->getSupervisedUsers($this->Auth->user('user_id'), $filter);

            $this->set('is_search', 1);
            $this->set('filter', $filter);
        } else {
            $users = $this->getSupervisedUsers($this->Auth->user('user_id'));
        }


        // Set Users
        $this->set(compact('users'));
    }

        // ABM


    public function index()
    {
        $this->paginate = [
            'contain' => ['Locales']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Locales']
        ]);

        $this->set('user', $user);
    }

    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $locales = $this->Users->Locales->find('list', ['limit' => 200]);
        $this->set(compact('user', 'locales'));
    }

    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $locales = $this->Users->Locales->find('list', ['limit' => 200]);
        $this->set(compact('user', 'locales'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    // Returns all persons supervised by the user, with and without pending tasks
    // ID = Supervisor ID
    // Author: Pablo Rodriguez
    // Arranged: Ricardo Andrés Nakanishi || Last Update: 08/04/2019  
    private function getUsers($id, $filter = null)
    {
        // users_array will contain and return the users
        $users_array = [
            'withPendingTasks' => [],
            'withoutPendingTasks' => []
        ];

        $allUsers = $this->getSupervisedUsers($id, $filter);

        foreach ($allUsers as $person) {
            // Use a Placeholder, it's just an aux var
            $placeholder = $person; // Take $person values to the placeholder
            // Get Tasks
            $placeholder['tasks'] = $this->getPendingTasks($person['id']);
            if (!empty($placeholder['tasks'])){
                // If the user has pending tasks
                $users_array['withPendingTasks'][] = $placeholder;
            } else {
                // If the user has not pending tasks
               $users_array['withoutPendingTasks'][] = $placeholder;
            }
        }

        // Return users
        return $users_array;
    }


    // Query to get all persons supervised by the user
    // ID = Supervisor ID
    // FILTER = FILTER, usually a string
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019  
    private function getSupervisedUsers($id, $filter = null)
    {
        $usersTable = TableRegistry::get('users');
        $users = $usersTable->find()
            ->select([
                'company' => "IFNULL(companies.company_name, ' - ')",
                'id' => 'users.user_id',
                'name' => 'users.name',
                'phone' => 'users.phone',
                'photo' => 'users.photo'
                ])
            ->where([
                'AND' =>
                    ['supervisors.supervisor_id' => $id],
                    ['supervisors.rol' => 'TUT'],
                    ['users.name LIKE' => '%'.$filter.'%']
            ])
            ->join([[
                'table' => 'supervisors',
                'alias' => 'supervisors',
                'type' => 'INNER',
                'conditions' => ['supervisors.person_id = users.user_id']
            ]])
            ->join([[
                'table' => 'companies',
                'alias' => 'companies',
                'type' => 'LEFT OUTER',
                'conditions' => ['companies.company_id = supervisors.company_id']
            ]])
            ->hydrate(false)
            ->toList();

        return $users;
    }

    // Query to get Pending Tasks
    // @id == USER_ID
    // Author: Pablo Rodriguez
    // Arranged: Ricardo Andrés Nakanishi || Last Update: 08/04/2019
    private function getPendingTasks($id)
    {
        $connection = ConnectionManager::get('default');

        $query = 
            "SELECT 
                COUNT(tsk.task_id) pendingTasks,
                max(tlg.end_date) lastTask,
                grp.title,
                gus.repetition,
                gus.rep_days repDays,
                gus.start_time startTimeConf,
                gus.end_time endTimeConf,
                CAST(now() as time) now
            FROM users per 
            INNER JOIN group_users gus ON per.user_id = gus.user_id
            INNER JOIN groups grp ON gus.group_id = grp.group_id
            INNER JOIN tasks tsk ON grp.group_id = tsk.group_id
            LEFT JOIN task_log tlg ON tsk.task_id = tlg.task_id AND date_format(tlg.start_date,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')
            WHERE (gus.rep_days LIKE concat('%',ELT(WEEKDAY(now())+1, 'LU', 'MA', 'MI', 'JU', 'VI', 'SA', 'DO'),'%') OR gus.rep_days = 'TODOS')
                AND per.user_id = :user_id
                AND gus.start_time < CAST(now() as time)
            GROUP BY grp.title, gus.repetition, gus.rep_days, gus.start_time, gus.end_time
            HAVING COUNT(tsk.task_id) > 0;";

        $statement = $connection->execute($query,
            [
                'user_id' => $id
            ],
            [   
                'user_id' => 'integer'
            ]
        );

        $response = $statement->fetchAll();

        return $response;
    }
}
