<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StepsOptions Controller
 *
 * @property \App\Model\Table\StepsOptionsTable $StepsOptions
 *
 * @method \App\Model\Entity\StepsOption[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StepsOptionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Steps', 'NextSteps']
        ];
        $stepsOptions = $this->paginate($this->StepsOptions);

        $this->set(compact('stepsOptions'));
    }

    /**
     * View method
     *
     * @param string|null $id Steps Option id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stepsOption = $this->StepsOptions->get($id, [
            'contain' => ['Steps', 'NextSteps']
        ]);

        $this->set('stepsOption', $stepsOption);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stepsOption = $this->StepsOptions->newEntity();
        if ($this->request->is('post')) {
            $stepsOption = $this->StepsOptions->patchEntity($stepsOption, $this->request->getData());
            if ($this->StepsOptions->save($stepsOption)) {
                $this->Flash->success(__('The steps option has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The steps option could not be saved. Please, try again.'));
        }
        $steps = $this->StepsOptions->Steps->find('list', ['limit' => 200]);
        $nextSteps = $this->StepsOptions->NextSteps->find('list', ['limit' => 200]);
        $this->set(compact('stepsOption', 'steps', 'nextSteps'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Steps Option id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stepsOption = $this->StepsOptions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stepsOption = $this->StepsOptions->patchEntity($stepsOption, $this->request->getData());
            if ($this->StepsOptions->save($stepsOption)) {
                $this->Flash->success(__('The steps option has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The steps option could not be saved. Please, try again.'));
        }
        $steps = $this->StepsOptions->Steps->find('list', ['limit' => 200]);
        $nextSteps = $this->StepsOptions->NextSteps->find('list', ['limit' => 200]);
        $this->set(compact('stepsOption', 'steps', 'nextSteps'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Steps Option id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stepsOption = $this->StepsOptions->get($id);
        if ($this->StepsOptions->delete($stepsOption)) {
            $this->Flash->success(__('The steps option has been deleted.'));
        } else {
            $this->Flash->error(__('The steps option could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
