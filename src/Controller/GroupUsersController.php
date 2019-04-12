<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GroupUsers Controller
 *
 * @property \App\Model\Table\GroupUsersTable $GroupUsers
 *
 * @method \App\Model\Entity\GroupUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Groups', 'Users', 'GroupTypes']
        ];
        $groupUsers = $this->paginate($this->GroupUsers);

        $this->set(compact('groupUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Group User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $groupUser = $this->GroupUsers->get($id, [
            'contain' => ['Groups', 'Users', 'GroupTypes']
        ]);

        $this->set('groupUser', $groupUser);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $groupUser = $this->GroupUsers->newEntity();
        if ($this->request->is('post')) {
            $groupUser = $this->GroupUsers->patchEntity($groupUser, $this->request->getData());
            if ($this->GroupUsers->save($groupUser)) {
                $this->Flash->success(__('The group user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The group user could not be saved. Please, try again.'));
        }
        $groups = $this->GroupUsers->Groups->find('list', ['limit' => 200]);
        $users = $this->GroupUsers->Users->find('list', ['limit' => 200]);
        $groupTypes = $this->GroupUsers->GroupTypes->find('list', ['limit' => 200]);
        $this->set(compact('groupUser', 'groups', 'users', 'groupTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $groupUser = $this->GroupUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $groupUser = $this->GroupUsers->patchEntity($groupUser, $this->request->getData());
            if ($this->GroupUsers->save($groupUser)) {
                $this->Flash->success(__('The group user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The group user could not be saved. Please, try again.'));
        }
        $groups = $this->GroupUsers->Groups->find('list', ['limit' => 200]);
        $users = $this->GroupUsers->Users->find('list', ['limit' => 200]);
        $groupTypes = $this->GroupUsers->GroupTypes->find('list', ['limit' => 200]);
        $this->set(compact('groupUser', 'groups', 'users', 'groupTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $groupUser = $this->GroupUsers->get($id);
        if ($this->GroupUsers->delete($groupUser)) {
            $this->Flash->success(__('The group user has been deleted.'));
        } else {
            $this->Flash->error(__('The group user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
