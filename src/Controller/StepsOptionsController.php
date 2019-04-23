<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

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
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $stepsOption = $this->StepsOptions->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['step_id'] = $id;
            $stepsOption = $this->StepsOptions->patchEntity($stepsOption, $data);

            try {
                $insertDetails = TableRegistry::get('steps_options')->query(); 
                $insertDetails->insert(['step_id', 'option_description', 'option_order', 'next_step_id'])
                            ->values([
                                'step_id' => $data['step_id'],
                                'option_description' => $data['option_description'],
                                'option_order' => $data['option_order'],
                                'next_step_id' => $data['next_step_id']
                                ])
                            ->execute();
                $this->Flash->success(__('La opción fue guardada.'));
                return $this->redirect(['controller' => 'Steps', 'action' => 'view', $id]);
            } catch (Exception $e) {            
                $this->Flash->error(__('La opción no pudo ser guardada. Intente más tarde.'));
            }
        }
        $stepsOptions = $this->StepsOptions->find('all', ['conditions' => ['step_id' => $id]])->count();
        $step = TableRegistry::get('steps')->find('all', ['conditions' => ['step_id' => $id]])->first();
        $nextSteps = $this->StepsOptions->Steps->find('list', ['conditions' => ['task_id' => $step->task_id]]);
        $this->set(compact('stepsOption', 'stepsOptions', 'nextSteps'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Steps Option id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($step_id = null, $option_order = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stepsOption = $this->StepsOptions->find('all', ['conditions' => ['StepsOptions.step_id' => $step_id, 'option_order' => $option_order]])->first();
        if ($this->StepsOptions->delete($stepsOption)) {
            $this->Flash->success(__('La opción ha sido correctamente eliminada.'));
        } else {
            $this->Flash->error(__('La opción no puedo ser eliminada.'));
        }

        return $this->redirect(['controller' => 'Steps', 'action' => 'view',$step_id]);
    }
}
