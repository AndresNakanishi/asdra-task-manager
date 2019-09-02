<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use App\Model\Entity\User;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('login');
    }

    // Login
    // Author: Ricardo Andrés Nakanishi || Last Update 03/04/2019

    public function login()
    {
        $this->viewBuilder()->setLayout('asdra-login');
        if ($this->Auth->user('user_id') !== null) {
            return $this->redirect(['action' => 'initDashboard']);
        }
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

    // Logout
    // Author: Ricardo Andrés Nakanishi || Last Update 03/04/2019 

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    // Dashboard Initialization - Screen 1
    // Purpose: Clear session
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019
    
    public function initDashboard()
    {
        $session = $this->getRequest()->getSession();
        $this->autoRender = false;
        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }
        $session->delete('is_search');
        $session->delete('filter');
        return $this->redirect(['action' => 'dashboard']);
    }

    // Dashboard - Screen 1
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019
    
    public function dashboard()
    {
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $this->set('is_search', 0);
        
        // Según
        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }


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

    // People in Charge Initialization - Screen 2
    // Purpose: Clear session
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019

    public function initInCharge()
    {
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();
        $session->delete('is_search');
        $session->delete('filter');
        return $this->redirect(['action' => 'inCharge']);
    }

    // People in Charge - Screen 2
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019

    public function inCharge()
    {
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $this->set('is_search', 0);
        // Búsqueda

        if ($this->request->is('post') && $this->Auth->user('user_type') == 'ADM') {
            $session->delete('is_search');
            $session->delete('filter');
    
            $filter = $this->request->getData()['filter'];

            $session->write('is_search', 1);
            $session->write('filter', $filter);
            
            $users = $this->getSupervisedUsers($this->Auth->user('user_id'), $filter);

            $this->set('is_search', 1);
            $this->set('filter', $filter);
        } elseif($this->Auth->user('user_type') == 'ADM') {
            $users = $this->getSupervisedUsers($this->Auth->user('user_id'));
        }
        // Tutores
        if ($this->request->is('post') && $this->Auth->user('user_type') == 'TUT') {
            $session->delete('is_search');
            $session->delete('filter');
    
            $filter = $this->request->getData()['filter'];

            $session->write('is_search', 1);
            $session->write('filter', $filter);
            
            $users = $this->getSupervisedUsersByTutors($this->Auth->user('user_id'), $filter);

            $this->set('is_search', 1);
            $this->set('filter', $filter);
        } elseif($this->Auth->user('user_type') == 'TUT') {
            $users = $this->getSupervisedUsersByTutors($this->Auth->user('user_id'));
        }


        // Set Users
        $this->set(compact('users'));
    }


    // People in Charge Initialization - Screen 2
    // Purpose: Clear session
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019

    public function initTutors()
    {
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();
        $session->delete('is_search');
        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }
        $session->delete('filter');
        return $this->redirect(['action' => 'tutors']);
    }

    // People in Charge - Screen 2
    // Author: Ricardo Andrés Nakanishi || Last Update: 08/04/2019

    public function tutors()
    {
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $this->set('is_search', 0);
        // Búsqueda
        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }
        if ($this->request->is('post')) {
            $session->delete('is_search');
            $session->delete('filter');
    
            $filter = $this->request->getData()['filter'];

            $session->write('is_search', 1);
            $session->write('filter', $filter);
            
            $users = $this->getTutors($filter);

            $this->set('is_search', 1);
            $this->set('filter', $filter);
        } else {
            $users = $this->getTutors();
        }


        // Set Users
        $this->set(compact('users'));
    }


    // Person - Screen 3
    // Author: Ricardo Andrés Nakanishi || Last Update: 09/04/2019
    public function person($id = null)
    {
        // Set Session
        $session = $this->getRequest()->getSession();
        
        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        
        // Get User
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        // Get Person Supervisor (Just for safety)
        // Purpose: The supervisor MUST be the user
        $supTable = TableRegistry::get('supervisors');
        $supervisor = $supTable->find('all', ['conditions' => [
            ['supervisor_id' => $this->Auth->user('user_id')],
            ['person_id' => $id]
        ]])->first();

        // Same as above, if supervisor == null means that the user
        // IN NOT THE PERSON SUPERVISOR
        if ($supervisor == null) {
            return $this->redirect( Router::url( $this->referer(), true ) );
        }

        // All supervisiors
        
        $supervisors = $supTable->find('all', ['conditions' => ['person_id' => $user->user_id]])
        ->select([
            'id' => 'users.user_id',
            'name' => 'users.name',
            'phone' => 'users.phone',
            'role' => 'supervisors.rol'
        ])
        ->join([[
            'table' => 'users',
            'alias' => 'users',
            'type' => 'INNER',
            'conditions' => ['supervisors.supervisor_id = users.user_id']
        ]])->order(['supervisors.rol' => 'DESC'])->all();

        $checkIfNaturalSupport = $supTable->find('all', ['conditions' => ['person_id' => $user->user_id, 'rol' => 'CHF']])->count();

        $this->set(compact('user','supervisors','checkIfNaturalSupport'));
    }


    // Add Person || Story 3
    // Author: Ricardo Andrés Nakanishi
    public function add()
    {
        // Set Session
        $session = $this->getRequest()->getSession();
        
        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        // Set User
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Image            
                // Check If letters are in the string
                if (preg_match("/[a-zA-Z]/i", $data['phone'])) {
                    $this->Flash->error(__('El número de teléfono <b>no puede tener caracteres (letras).</b>'));            
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                // Set Photo
                $data['photo'] = $this->setAvatar($data['photo'], $data['name'], $fileData);
            // Image
            $data['name'] = strtoupper($data['name']);
            $data['address'] = strtoupper($data['address']);
            $data['user_type'] = 'PER';
            $data['token'] = $this->generate_token(8);
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->createSupevisorTutorRelationship($user, $this->Auth->user('user_id'));
                $this->Flash->success(__('Agregaste una persona a la base de datos.'));
            } else {
                $this->Flash->error(__('Hubo un error! Por favor, intente más tarde.'));
            }
            return $this->redirect(['action' => 'inCharge']);
        }
        $this->set(compact('user'));
    }

    // Add Tutor Screen 4
    // Author: Ricardo Andrés Nakanishi || Last Update: 15/04/2019
    public function addTutor()
    {
        // Set Session
        $session = $this->getRequest()->getSession();
        
        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }
        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        // Set User
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Set Photo
            $data['user_type'] = 'TUT';
            $data['name'] = strtoupper($data['name']);
            $data['address'] = strtoupper($data['address']);
            // Image            
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                // Set Photo
                $data['photo'] = $this->setAvatar($data['photo'], $data['name'], $fileData);
            // Image
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Agregaste correctamente un tutor.'));
            } else {
                $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
            }
            return $this->redirect(['action' => 'tutors']);
        }
        $companies = TableRegistry::get('companies')->find('list')->order(['company_name' => 'ASC']);
        $this->set(compact('user','companies'));
    }

    public function assignTutor($id = null)
    {   
        // Set Session
        $session = $this->getRequest()->getSession();
        
        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        
        // Get User
        $user = $this->Users->get($id);
        $tutors = $this->Users->find('all', ['conditions' => ['user_type' => 'TUT']])->all();
        $companies = TableRegistry::get('companies')->find('list')->order(['company_name' => 'ASC']);
        $checkIfNaturalSupport = TableRegistry::get('supervisors')->find('all', ['conditions' => ['person_id' => $user->user_id, 'rol' => 'CHF']])->count();

        if ($this->Auth->user('user_type') !== 'ADM' || $checkIfNaturalSupport > 0) {
            $this->Flash->error(__("No se puede asignar más de un apoyo natural por usuario."));            
            return $this->redirect( Router::url( $this->referer(), true ) );
        }
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $rol = $data['rol'];
            $company = $data['company'];
            $person = $data['user_id'];
            $supervisor = $data['supervisor_id'];
            if ($this->createSupevisorRelationship($person, $supervisor, $rol, $company)) {
                $this->Flash->success(__('Agregaste correctamente un tutor.'));
            } else {
                $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
            }
            return $this->redirect(['action' => 'person',$data['user_id']]);
        }
        $this->set(compact('user','tutors','companies'));
    }


    public function editTutor($id = null)
    {   
        // Set Session
        $session = $this->getRequest()->getSession();
        
        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }

        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        
        // Get User
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $companies = TableRegistry::get('companies')->find('list')->order(['company_name' => 'ASC']);
        $this->set(compact('user','companies'));
    }


    public function edit($id = null)
    {
        $this->autoRender = false;
        $user = $this->Users->get($id);

        if ($this->Auth->user('user_type') !== 'ADM') {
            return $this->redirect(['action' => 'initInCharge']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Check If letters are in the string
            if (preg_match("/[a-zA-Z]/i", $data['phone'])) {
                $this->Flash->error(__('El número de teléfono <b>no puede tener caracteres (letras).</b>'));            
                return $this->redirect( Router::url( $this->referer(), true ) );
            }
            // If password
            if (isset($data['password']) and $data['password'] == '') {
                unset($data['password']);
            }
            // If company
            if (isset($data['company_id']) and $data['company_id'] == '') {
                $data['company_id'] = null;
            }

            // Set Photo
            if ($data['photo'] == '') {
                unset($data['photo']);
                unset($data['avatar-code']);
            } else {
                // Image            
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                    // Set Photo
                $data['photo'] = $this->setAvatar($data['photo'], $data['name'], $fileData);
                // Image
            }
            $data['name'] = strtoupper($data['name']);
            $data['address'] = strtoupper($data['address']);
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                // Success
                $this->Flash->success(__('Los cambios han sido guardados.'));
            } else {
                // Fail
                $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
            }
            return $this->redirect(['action' => 'person',$user->user_id]);
        }
    }


    public function pendingTasks($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
    
        $pendingTasks = $this->pendingTasksUser($id);
        $user = $this->Users->get($id);

        $this->set(compact('pendingTasks','user'));
    }

    // Delete User || Screen 3
    // Author: Ricardo Andrés Nakanishi || Last Update 10/04/2019
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $supervisorTable = TableRegistry::get('supervisors');  
        $supervisor = $supervisorTable->find('all', ['conditions' => ['person_id' => $id]])->all();
        foreach ($supervisor as $relation) {
            $supervisorTable->delete($relation);
        }
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('El usuario ha sido correctamente borrado del sistema.'));
        } else {
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }

        return $this->redirect(['action' => 'init-dashboard']);
    }

    // Delete Tutor || Screen 3
    // Author: Ricardo Andrés Nakanishi || Last Update 12/04/2019
    public function deleteTutor($id = null, $user_id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $supervisorTable = TableRegistry::get('supervisors');  
        $supervisor = $supervisorTable->find('all', ['conditions' => ['supervisor_id' => $id, 'person_id' => $user_id]])->all();
        foreach ($supervisor as $relation) {
            $supervisorTable->delete($relation);
        }
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('El tutor ha sido correctamente eliminado.'));
        } else {
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }

        return $this->redirect(['action' => 'tutors']);
    }

    public function deleteRelationUserTutor($supervisor_id, $person_id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $supervisorTable = TableRegistry::get('supervisors');  
        $supervisor = $supervisorTable->find('all', ['conditions' => ['supervisor_id' => $supervisor_id, 'person_id' => $person_id]])->all();
        foreach ($supervisor as $relation) {
            $supervisorTable->delete($relation);
        }
        $this->Flash->success(__('El tutor/familiar ha sido removido.'));
        return $this->redirect(['action' => 'person', $person_id]);

    }

    // Profile - Screen 11
    // Author: Ricardo Andrés Nakanishi || Last Update: 22/04/2019
    public function profile($id = null)
    {
        // Set Session
        $session = $this->getRequest()->getSession();
        
        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        
        // Get User
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        // Same as above, if supervisor == null means that the user
        // IN NOT THE PERSON SUPERVISOR
        if ($this->Auth->user('user_id') != $id) {
            return $this->redirect( Router::url( $this->referer(), true ) );
        }

        $this->set(compact('user'));
    }


    // Crea un Supervisión de Tipo Tutor
    // Author: Ricardo Andrés Nakanishi || Last Update: 11/04/2019
    private function createSupevisorTutorRelationship($user, $supervisor)
    {
        $this->autoRender = false;
        $supTable = TableRegistry::get('supervisors');
        $insert = $supTable->query();
        $insert->insert([
            'person_id',
            'supervisor_id',
            'rol',
            'company_id'
        ])->values([
            'person_id' => $user->user_id,
            'supervisor_id' => $supervisor,
            'rol' => 'TUT',
            'company' => null
        ])
        ->execute();

        return true;
    }

        // Crea un Supervisión de Tipo Tutor
    // Author: Ricardo Andrés Nakanishi || Last Update: 11/04/2019
    private function createSupevisorRelationship($person, $supervisor, $rol, $company)
    {
        $this->autoRender = false;
        $supTable = TableRegistry::get('supervisors');
        $insert = $supTable->query();
        $insert->insert([
            'person_id',
            'supervisor_id',
            'rol',
            'company_id'
        ])->values([
            'person_id' => $person,
            'supervisor_id' => $supervisor,
            'rol' => $rol,
            'company_id' => $company
        ])
        ->execute();

        return true;
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

        // Tiene un BUG
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
        $filter = strtoupper($filter);
        $usersTable = TableRegistry::get('users');

        $users = $usersTable->find()
            ->select([
                'company' => "IFNULL(cmp.company_name, ' - ')",
                'id' => 'users.user_id',
                'name' => 'users.name',
                'phone' => 'users.phone',
                'photo' => 'users.photo'
                ])
            ->join([[
                'table' => 'supervisors',
                'alias' => 'sup',
                'type' => 'INNER',
                'conditions' => ["sup.person_id = users.user_id AND sup.rol = 'TUT'"]
            ]])
            ->join([[
                'table' => 'supervisors',
                'alias' => 'chf',
                'type' => 'LEFT OUTER',
                'conditions' => ["chf.person_id = sup.person_id AND chf.rol = 'CHF'"]
            ]])
            ->join([[
                'table' => 'companies',
                'alias' => 'cmp',
                'type' => 'LEFT OUTER',
                'conditions' => ['cmp.company_id = chf.company_id']
            ]]) 
            ->where([
                'AND' =>
                    ['sup.supervisor_id' => $id],
                    ['UPPER(users.name) LIKE' => '%'.$filter.'%']
            ])
            ->enableHydration(false)
            ->toList();

        return $users;
    }

    private function getSupervisedUsersByTutors($id, $filter = null)
    {
        $filter = strtoupper($filter);
        $usersTable = TableRegistry::get('users');

        $users = $usersTable->find()
            ->select([
                'company' => "IFNULL(cmp.company_name, ' - ')",
                'id' => 'users.user_id',
                'name' => 'users.name',
                'phone' => 'users.phone',
                'photo' => 'users.photo'
                ])
            ->join([[
                'table' => 'supervisors',
                'alias' => 'sup',
                'type' => 'INNER',
                'conditions' => ["sup.person_id = users.user_id AND sup.rol = 'CHF'"]
            ]])
            ->join([[
                'table' => 'companies',
                'alias' => 'cmp',
                'type' => 'LEFT OUTER',
                'conditions' => ['cmp.company_id = sup.company_id']
            ]]) 
            ->where([
                'AND' =>
                    ['sup.supervisor_id' => $id],
                    ['UPPER(users.name) LIKE' => '%'.$filter.'%']
            ])
            ->enableHydration(false)
            ->toList();

        return $users;
    }



    private function getTutors($filter = null)
    {
        $filter = strtoupper($filter);
        $usersTable = TableRegistry::get('users');

        $users = $usersTable->find()
            ->select([
                'id' => 'users.user_id',
                'name' => 'users.name',
                'phone' => 'users.phone',
                'photo' => 'users.photo'
                ])
            ->where([
                'AND' =>
                    ['users.user_type' => 'TUT'],
                    ['UPPER(users.name) LIKE' => '%'.$filter.'%']
            ])
            ->enableHydration(false)
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

        $now = date('Y-m-d H:i:s');

        $query = 
                "SELECT 
                SUM(pending) pendingTasks,
                max(lastTask) lastTask,
                title,
                repetition,
                repDays,
                startTimeConf,
                endTimeConf,
                CAST('$now' as time) as 'now'
                FROM (
                    SELECT 
                        tsk.task_id Task,
                        max(tlg.end_date) lastTask,
                        grp.title,
                        gus.repetition,
                        gus.rep_days repDays,
                        gus.start_time startTimeConf,
                        gus.end_time endTimeConf,
                        MAX(IF(isnull(tlg.task_id), 1, 0)
                ) AS pending
                FROM
                    users per
                        INNER JOIN
                    group_users gus ON per.user_id = gus.user_id
                        INNER JOIN
                    groups grp ON gus.group_id = grp.group_id
                        INNER JOIN
                    tasks tsk ON grp.group_id = tsk.group_id
                        INNER JOIN
                    steps stp ON stp.task_id = tsk.task_id
                        LEFT JOIN
                    task_log tlg ON (tsk.task_id = tlg.task_id AND stp.step_id = tlg.step_id
                                     AND date_format(tlg.end_date, '%Y-%m-%d') = date_format('$now', '%Y-%m-%d') 
                                     AND tlg.user_id = 2)
                WHERE
                    ( gus.rep_days = 'TODOS'
                      OR (gus.rep_days LIKE concat('%',ELT(WEEKDAY('$now') + 1,'LU','MA','MI','JU','VI','SA','DO'),'%') and gus.repetition in ('DIA','SEM'))
                      OR (gus.repetition = 'MES' and  DAY('$now') = DAY(gus.date_from))
                     )
                     AND per.user_id = :id
                     AND CAST('$now' AS TIME) BETWEEN gus.start_time AND gus.end_time
                     AND DATE_FORMAT('$now', '%Y-%m-%d') between gus.date_from AND IFnull(gus.date_to, '$now')
                GROUP BY grp.title , gus.repetition , gus.rep_days , gus.start_time , gus.end_time, tsk.task_id
                HAVING COUNT(grp.group_id) > 0) as temp
                GROUP BY temp.title, temp.repetition, temp.repDays, temp.startTimeConf, temp.endTimeConf";

        $statement = $connection->execute($query,
            [
                'id' => $id
            ],
            [   
                'id' => 'integer'
            ]
        );

        $response = $statement->fetchAll();

        return $response;
    }

    // Query to get Pending Tasks by User and other data
    // @id == USER_ID
    // Author: Pablo Rodriguez
    // Arranged: Ricardo Andrés Nakanishi || Last Update: 03/05/2019

    private function pendingTasksUser($id)
    {
        $connection = ConnectionManager::get('default');

        $query = "
            SELECT 
                dias.date_to_compare,
                gop.description,
                MAX(gur.start_time) inicio,
                MAX(gur.repetition) repite,
                MAX(gur.rep_days) dias_rep,
                tsk.description_1 tarea,
                COUNT(stp.step_order) pasos,
                COUNT(tlg.step_id) hechos
            FROM group_users gur
            INNER join groups gop ON gur.group_id = gop.group_id
            LEFT OUTER JOIN tasks tsk ON gur.group_id = tsk.group_id 
            LEFT OUTER JOIN steps stp ON stp.task_id = tsk.task_id
            INNER join (select curdate() date_to_compare, elt(weekday(curdate())+1,'LU','MA','MI','JU','VI','SA','DO') date_name from dual UNION ALL select date_add(curdate(), interval -1 DAY), elt(weekday(date_add(curdate(), interval -1 DAY))+1,'LU','MA','MI','JU','VI','SA','DO') date_name from dual UNION ALL select date_add(curdate(), interval -2 DAY), elt(weekday(date_add(curdate(), interval -2 DAY))+1,'LU','MA','MI','JU','VI','SA','DO') date_name from dual UNION ALL select date_add(curdate(), interval -3 DAY), elt(weekday(date_add(curdate(), interval -3 DAY))+1,'LU','MA','MI','JU','VI','SA','DO') date_name from dual UNION ALL select date_add(curdate(), interval -4 DAY), elt(weekday(date_add(curdate(), interval -4 DAY))+1,'LU','MA','MI','JU','VI','SA','DO') date_name from dual) dias ON (gur.rep_days = 'TODOS' OR (gur.rep_days LIKE concat('%', dias.date_name, '%') and gur.repetition in ('DIA','SEM')) OR (gur.repetition = 'MES' and  DAY(dias.date_to_compare) = DAY(gur.date_from)))
            LEFT OUTER JOIN task_log tlg ON DATE(tlg.start_date) = dias.date_to_compare and tlg.step_id = stp.step_id and tlg.user_id = gur.user_id
            WHERE
                gur.user_id = :id                                                                -- Usuario
                and dias.date_to_compare between gur.date_from and ifnull(date_to,'2999-01-01')   -- Validez
            GROUP BY 
                dias.date_to_compare , gop.description, tsk.task_order, tsk.description_1
            ORDER BY date_to_compare desc";
        
        $statement = $connection->execute($query,
            [
                'id' => $id
            ],
            [   
                'id' => 'integer'
            ]
        );

        $response = $statement->fetchAll();

        return $response;
    }

    // Set or Update User Photo / Avatar
    // Author: Ricardo Andrés Nakanishi || Last Update: 10/04/2019
    private function setAvatar($avatar, $name, $img){
        // Esta URL se usará una vez se configure de manera correcta la escritura.
        // $url = 'http://190.188.102.60:8089/organizerApi/public/';
        $url = WWW_ROOT;
        // Use a HASH with SHA1 to save our img
        $hash = sha1($avatar.$name);
        // We'll save our users img in this folder
        $imgFolder = "img/users/";
        // Link that we are going to save
        $folder = $url . $imgFolder;
        // Out FileName
        $fileName = "$hash.png";
        // Temporal File
        $fileTmp = $img;
        // Entire URL
        $fileDest = $folder . $fileName;

        if ($avatar == '') {
            if (!file_exists($fileDest)) {   
                $newAvatar = 'https://ui-avatars.com/api/?size=256&font-size=0.33&background=0D8ABC&color=fff&name='.$name;
            }
        } else {
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            if (file_exists($fileDest)) {   
                unlink($fileDest);
                $success = file_put_contents($fileDest, $fileTmp);                         
            } else {
                $success = file_put_contents($fileDest, $fileTmp);                         
            }

            // Entire URL or Half
            $newAvatar = APP_URL. $imgFolder . $fileName;
        }
        return $newAvatar;
    }

    public function generateNewToken($id = null)
    {
        $this->autoRender = false;
        $user = $this->Users->get($id);

        if ($this->request->is(['get', 'post', 'put'])) {
            $data['token'] = $this->generate_token(8);
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                // Success
                $this->Flash->success(__("El nuevo código fue generado correctamente: <b>$user->token</b>"));
            } else {
                // Fail
                $this->Flash->error(__('Intente una vez más, hubo un error'));
            }
            return $this->redirect(['action' => 'person',$user->user_id]);
        }
    }

    private function generate_token($length = 8) {
       $chars = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
       $password = substr(str_shuffle($chars), 0, $length);
       return $password;
    }

    // AJAX
    public function getusercompany($user_id) {
        $this->autoRender = false;
        $user = $this->Users->get($user_id);
        $company = TableRegistry::get('companies')->find('all', ['conditions' => ['company_id' => $user->company_id]])->first();
        if ($company !== false) {
            $return = '<option value="' . $company->company_id . '">' . $company->company_name . '</option> ';
            $this->response->body($return);
            return $this->response;
        }
    }
}
