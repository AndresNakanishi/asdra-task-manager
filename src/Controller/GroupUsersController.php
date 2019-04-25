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
            debug($data);
            die;
            $groupUser = $this->GroupUsers->patchEntity($groupUser, $data);
            if ($this->GroupUsers->save($groupUser)) {
                $this->Flash->success(__('El grupo de tareas fue configurado correctamente.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oh no! Hubo un error. Intente más tarde.'));
        }
        $groups = TableRegistry::get('groups')->find('list')->enableHydration()->toList();
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

        return $this->redirect(['action' => 'index']);
    }
}
