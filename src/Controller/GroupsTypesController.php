<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GroupsTypes Controller
 *
 * @property \App\Model\Table\GroupsTypesTable $GroupsTypes
 *
 * @method \App\Model\Entity\GroupsType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupsTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $groupsTypes = $this->paginate($this->GroupsTypes);

        $this->set(compact('groupsTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Groups Type id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $groupsType = $this->GroupsTypes->get($id, [
            'contain' => []
        ]);

        $this->set('groupsType', $groupsType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $groupsType = $this->GroupsTypes->newEntity();
        if ($this->request->is('post')) {
            $groupsType = $this->GroupsTypes->patchEntity($groupsType, $this->request->getData());
            if ($this->GroupsTypes->save($groupsType)) {
                $this->Flash->success(__('The groups type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The groups type could not be saved. Please, try again.'));
        }
        $this->set(compact('groupsType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Groups Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $groupsType = $this->GroupsTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $groupsType = $this->GroupsTypes->patchEntity($groupsType, $this->request->getData());
            if ($this->GroupsTypes->save($groupsType)) {
                $this->Flash->success(__('The groups type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The groups type could not be saved. Please, try again.'));
        }
        $this->set(compact('groupsType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Groups Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $groupsType = $this->GroupsTypes->get($id);
        if ($this->GroupsTypes->delete($groupsType)) {
            $this->Flash->success(__('The groups type has been deleted.'));
        } else {
            $this->Flash->error(__('The groups type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
