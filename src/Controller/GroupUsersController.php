<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * GroupUsers Controller
 *
 * @property \App\Model\Table\GroupUsersTable $GroupUsers
 *
 * @method \App\Model\Entity\GroupUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupUsersController extends AppController
{

    public function view($user_id, $group_type)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
     
        $groupUsers = $this->GroupUsers->find('all', ['conditions' => ['group_type_id' => $group_type, 'user_id' => $user_id]])->contain('Groups')->order(['start_time' => 'ASC'])->all();
        $groupType = TableRegistry::get('groups_types')->find('all', ['conditions' => ['group_type_id' => $group_type]])->first();
        $user = TableRegistry::get('users')->find('all', ['conditions' => ['user_id' => $user_id]])->first();

        $this->set(compact('groupUsers','groupType','user'));
    }


    public function edit($user_id = null, $group_type = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');

    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($user_id = null, $group_type = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');

        $groupUser = $this->GroupUsers->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($data['rep_days'] !== null) {
                $data['rep_days'] = $this->repetitionDaysArrange($data['rep_days']);
            }
            // Add Data
            $data['user_id'] = $user_id;
            $data['group_type_id'] = $group_type;
            // Arrange Date && Time
            $data['start_time'] = date('H:i:s',strtotime($data['start_time']));
            $data['end_time'] = date('H:i:s',strtotime($data['end_time']));
            $data['start_date'] = date('Y-m-d 00:00:00',strtotime($data['start_date']));
            if ($data['end_date'] !== '') {
                $data['end_date'] = date('Y-m-d 00:00:00',strtotime($data['end_date']));
            } else {
                $data['end_date'] = null;
            }
            if ($data['end_date'] == null || (strtotime($data['end_date']) > strtotime($data['start_date']))) {
                try {
                    $supTable = TableRegistry::get('group_users');
                    $insert = $supTable->query();
                    $insert->insert([
                        'user_id',
                        'group_id',
                        'date_from',
                        'date_to',
                        'start_time',
                        'end_time',
                        'repetition',
                        'rep_days',
                        'group_type_id'
                    ])->values([
                        'user_id' => $data['user_id'],
                        'group_id' => $data['group_id'],
                        'date_from' => $data['start_date'],
                        'date_to' => $data['end_date'],
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'repetition' => $data['repetition'],
                        'rep_days' => $data['rep_days'],
                        'group_type_id' => $data['group_type_id']
                    ])
                    ->execute();

                    $this->Flash->success(__('El grupo de tareas fue configurado correctamente.'));
                    return $this->redirect(['action' => 'view',$user_id,$group_type]);
                } catch (Exception $e) {   
                    $this->Flash->error(__('Oh no! Hubo un error. Intente más tarde.'));
                }
            } else {
                $this->Flash->error(__('La fecha <b>Hasta</b> no puede ser anterior a la fecha <b>Desde</b>.'));    
            }
        }
        $groups = TableRegistry::get('groups')->find('list');
        $this->set(compact('groupUser', 'groups'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($group_id = null, $user_id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $groupUser = $this->GroupUsers->find('all', ['conditions' => ['group_id' => $group_id, 'user_id' => $user_id]])->first();
        // Just to improve user experience on our notifications
        $user = TableRegistry::get('users')->get($user_id);
        if ($this->GroupUsers->delete($groupUser)) {
            $this->Flash->success(__("Se eliminó el grupo de tareas asignado a $user->name."));
        } else {
            $this->Flash->error(__('Las tareas no han podido ser eliminadas. Por favor, intente más tarde.'));
        }

        return $this->redirect(['action' => 'view',$user_id,$groupUser->group_type_id]);
    }


    // Little function to arrange Repetition Days Data
    private function repetitionDaysArrange($data)
    {
        if (count($data) == 1) {
            $response = $data[0];
        } else {
            foreach ($data as $value => $day) {
                if ($value == 0 && $day != 'TODOS') {
                    $response = $day;
                } else {
                    $response = $response.'-'.$day;
                }
            }
        }

        return $response;
    }
}
