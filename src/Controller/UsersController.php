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
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();
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

        $this->set(compact('user','supervisors'));
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
            // Set Photo
            $data['user_type'] = 'PER';
            $data['photo'] = $this->setAvatar($data['photo'], $data['name']);
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
    public function addTutor($id = null)
    {
        // Set Session
        $session = $this->getRequest()->getSession();
        
        // Set Layout
        $this->viewBuilder()->setLayout('asdra-layout');
        // Set User
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Set Photo
            $rol = $data['rol'];
            $company = $data['company'];
            $data['user_type'] = 'PER';
            $data['photo'] = $this->setAvatar($data['photo'], $data['name']);
            unset($data['rol']);
            unset($data['company']);
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->createSupevisorRelationship($id, $user, $rol, $company);
                $this->Flash->success(__('Agregaste correctamente un tutor.'));
            } else {
                $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
            }
            return $this->redirect(['action' => 'person',$id]);
        }
        $companies = TableRegistry::get('companies')->find('list');
        $this->set(compact('user','companies'));
    }

    public function edit($id = null)
    {
        $this->autoRender = false;
        $user = $this->Users->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            // Set Photo
            if ($data['photo']['tmp_name'] == '') {
                unset($data['photo']);
            } else {
                $data['photo'] = $this->setAvatar($data['photo'], $data['name']);
            }
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

        return $this->redirect( Router::url( $this->referer(), true ) );
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

        return $this->redirect( Router::url( $this->referer(), true ) );
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
            'supervisor_id' => $supervisor->user_id,
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

    // Set or Update User Photo / Avatar
    // Author: Ricardo Andrés Nakanishi || Last Update: 10/04/2019
    private function setAvatar($avatar, $name){
        // Esta URL se usará una vez se configure de manera correcta la escritura.
        // $url = 'http://190.188.102.60:8089/organizerApi/public/';
        $url = WWW_ROOT;
        // Use a HASH with SHA1 to save our img
        $hash = sha1($avatar["tmp_name"].$name);
        // We'll save our users img in this folder
        $imgFolder = "img/users/";
        // Link that we are going to save
        $folder = $url . $imgFolder;
        // Out FileName
        $fileName = "$hash.jpg";
        // Temporal File
        $fileTmp = $avatar["tmp_name"];
        // Entire URL
        $fileDest = $folder . $fileName;
            
        if ($avatar['name'] == '') {
            if (!file_exists($fileDest)) {   
                $newAvatar = 'https://ui-avatars.com/api/?size=256&font-size=0.33&background=0D8ABC&color=fff&name='.$name;
            }
        } else {
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            if (file_exists($fileDest)) {   
                unlink($fileDest);
                $success = move_uploaded_file($fileTmp, $fileDest);                         
            } else {
                $success = move_uploaded_file($fileTmp, $fileDest);                         
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
       $chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789";
       $password = substr(str_shuffle($chars), 0, $length);
       return $password;
    }
}
