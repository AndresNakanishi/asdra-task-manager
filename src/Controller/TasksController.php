<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * Tasks Controller
 *
 *
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
{

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $task = $this->Tasks->get($id, [
            'contain' => ['Groups']
        ]);
        $steps = TableRegistry::get('steps')->find('all', ['conditions' => ['task_id' => $id]])->order(['step_order' => 'ASC'])->all();
        $this->set(compact('task','steps'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $task = $this->Tasks->newEntity();
        $tasks = $this->Tasks->find('all', ['conditions' => ['group_id' => $id]])->count();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['required'] = 1;
            $data['priority'] = 1;
            $data['group_id'] = $id;
            $data['description_1'] = strtoupper($data['description_1']);
            $data['description_2'] = strtoupper($data['description_2']);
            $task = $this->Tasks->patchEntity($task, $data);
            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('<b>La tarea fue configurada correctamente!</b>'));

                return $this->redirect(['controller' => 'Groups', 'action' => 'view',$id]);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $this->set(compact('task','tasks'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        $tasks = $this->Tasks->find('all', ['conditions' => ['group_id' => $task->group_id]])->count();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['description_1'] = strtoupper($data['description_1']);
            $data['description_2'] = strtoupper($data['description_2']);
            $task = $this->Tasks->patchEntity($task, $data);
            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('<b>Los datos se guardaron correctamente!</b>'));

                return $this->redirect(['controller' => 'Groups', 'action' => 'view',$task->group_id]);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $this->set(compact('task','tasks'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $task = $this->Tasks->get($id);
        $deleted = false;

        try {
            $deleted = $this->Tasks->delete($task);
        } catch(\Exception $e) {
            
        }

        if ($deleted) {
            $this->Flash->success(__('<b>La tareas ha sido borrado!</b>'));
        } else {
            $this->Flash->warning(__('<b>Por Seguridad:</b> Para poder borrar una tarea, debe <b>primero borrar sus pasos.</b>'));
        }

        return $this->redirect(['controller' => 'Groups', 'action' => 'view',$task->group_id]);
    }
}
