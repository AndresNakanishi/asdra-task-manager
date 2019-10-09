<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Steps Controller
 *
 * @property \App\Model\Table\StepsTable $Steps
 *
 * @method \App\Model\Entity\Step[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StepsController extends AppController
{

    /**
     * View method
     *
     * @param string|null $id Step id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $step = $this->Steps->get($id);
        $task = TableRegistry::get('tasks')->find('all', ['contain' => ['Groups']],['conditions' => ['task_id' => $step->task_id]])->first();
        $stepOptions = TableRegistry::get('steps_options')->find('all', ['conditions' => ['steps_options.step_id' => $step->step_id]])
        ->select([
            'step_id' => 'steps_options.step_id',
            'option_order' => 'steps_options.option_order',
            'option_description' => 'steps_options.option_description',
            'next_step' => 'steps.title'
        ])
        ->join([[
            'table' => 'steps',
            'alias' => 'steps',
            'type' => 'INNER',
            'conditions' => ['steps.step_id = steps_options.next_step_id']
        ]])->all();

        $this->set(compact('step','task','stepOptions'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $step = $this->Steps->newEntity();
        $steps = $this->Steps->find('all', ['conditions' => ['task_id' => $id]])->count();
        $task = TableRegistry::get('tasks')->find('all', ['conditions' => ['task_id' => $id]])->first();
        $taskTitle = $task->description_1; 
        $tid = $id;
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Check Order
            $data['step_order'] = $steps + 1;
            $order = $this->checkStepOrder($id, $data['step_order']);
            $data['required'] = 1;
            $data['task_id'] = $id;
            if ($data['photo'] == '' && $data['gif']['tmp_name'] == '') {
                $data['photo'] = null;
                unset($data['gif']);
                unset($data['avatar_code']);
            } elseif($data['photo'] == '' && $data['gif']['tmp_name'] !== ''){
                unset($data['avatar_code']);
                $data['photo'] = $this->setGif($data['gif']);
                unset($data['gif']);
            } else {
                unset($data['gif']);
                // Image            
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                // Set Photo
                $data['photo'] = $this->setAvatar($data['photo'], $data['title'], $fileData);
                // Image
            }            $data['title'] = strtoupper($data['title']);
            $data['sub_title'] = strtoupper($data['sub_title']);
            $step = $this->Steps->patchEntity($step, $data);
            if (!$order) {
                if ($this->Steps->save($step)) {
                    $this->Flash->success(__('<b>El paso fue configurado correctamente!</b>'));
                    return $this->redirect(['controller' => 'Tasks', 'action' => 'view',$id]);
                } else {
                    $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
                }
            }
        }
        $this->set(compact('step', 'tid','taskTitle'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Step id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $step = $this->Steps->get($id, [
            'contain' => ['Tasks']
        ]);
        $steps = $this->Steps->find('all', ['conditions' => ['task_id' => $step->task_id]])->count();
        $task = TableRegistry::get('tasks')->find('all', ['conditions' => ['task_id' => $step->task_id]])->first();
        $taskTitle = $task->description_1;
        $tid = $step->task_id;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            // Check Order
            $order = $this->checkStepOrder($step->task->task_id, $data['step_order']);
            // Order == true means that there's been a change
            if ($order == true) {
                $this->orderAllRemainingStepsOnUpdate($step->step_order, $data['step_order'], $step->task_id);
            }
            if ($data['photo'] == '' && $data['gif']['tmp_name'] == '') {
                unset($data['photo']);
                unset($data['gif']);
                unset($data['avatar_code']);
            } elseif($data['photo'] == '' && $data['gif']['tmp_name'] !== ''){
                unset($data['avatar_code']);
                $data['photo'] = $this->setGif($data['gif']);
                unset($data['gif']);
            } else {
                unset($data['gif']);
                // Image            
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                // Set Photo
                $data['photo'] = $this->setAvatar($data['photo'], $data['title'], $fileData);
                // Image
            }
            $data['title'] = strtoupper($data['title']);
            $data['sub_title'] = strtoupper($data['sub_title']);
            $step = $this->Steps->patchEntity($step, $data);
            if ($this->Steps->save($step)) {
                $this->Flash->success(__('<b>Los datos han sido guardados correctamente!</b>'));
                return $this->redirect(['controller' => 'Tasks', 'action' => 'view',$step->task_id]);
            } else {
                $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
            }
        }
        $this->set(compact('step', 'steps','tid','taskTitle'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Step id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $step = $this->Steps->get($id);
        $deleted = false;
        
        try {
            $reorder = $this->orderAllRemainingStepsOnDelete($step);
            $deleted = $this->Steps->delete($step);
        } catch(\Exception $e) {
            
        }

        if ($deleted) {
            $this->Flash->success(__('<b>El paso ha sido borrado!</b>'));
        } else {
            $this->Flash->warning(__('<b>Este paso tiene opciones!</b> Para poder borrar el paso, debe <b>primero borrar sus opciones!.</b>'));
        }
        
        return $this->redirect(['controller' => 'Tasks', 'action' => 'view',$step->task_id]);
    }

    private function orderAllRemainingStepsOnDelete($step)
    {
        $step_order = $step->step_order;

        $steps = $this->Steps->find('all', ['conditions' => ['task_id' => $step->task_id, "step_order > $step_order"]])->all();

        foreach ($steps as $step) {
            $step->step_order -= 1; 
            $this->Steps->save($step);
        }

        return true;
    }

    private function orderAllRemainingStepsOnUpdate($old, $new, $task_id)
    {
        if ($old > $new) {
            $steps = $this->Steps->find('all', ['conditions' => ['task_id' => $task_id, "step_order < $old", "step_order >= $new"]])->all();
            foreach ($steps as $step) {
                $step->step_order += 1; 
                $this->Steps->save($step);
            }
        } else {
            $steps = $this->Steps->find('all', ['conditions' => ['task_id' => $task_id, "step_order > $old", "step_order <= $new"]])->all();
            foreach ($steps as $step) {
                $step->step_order -= 1; 
                $this->Steps->save($step);
            }
        }
        return true;
    }
    private function checkStepOrder($task_id, $step_order)
    {
        $step_order = (int)$step_order; 
        $step = $this->Steps->find('all', ['conditions' => ['task_id' => $task_id, 'step_order' => $step_order]])->first();    
        if ($step) {
            return true;
        } else {
            return false;
        }
    }

    // Set or Update User Photo / Avatar
    // Author: Ricardo Andrés Nakanishi || Last Update: 10/04/2019
    private function setAvatar($avatar, $name, $img){
        $url = WWW_ROOT;
        // Use a HASH with SHA1 to save our img
        $hash = sha1($avatar.$name);
        // We'll save our users img in this folder
        $imgFolder = "img/steps/";
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


    private function setGIF($avatar){
        $url = WWW_ROOT;
        // Use a HASH with SHA1 to save our img
        $hash = sha1($avatar['tmp_name']);
        // We'll save our users img in this folder
        $imgFolder = "img/steps/";
        // Link that we are going to save
        $folder = $url . $imgFolder;
        // Out FileName
        $fileName = "$hash.gif";
        // Temporal File
        $fileTmp = $avatar["tmp_name"];
        // Entire URL
        $fileDest = $folder . $fileName;

        
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if (file_exists($fileDest)) {   
            unlink($fileDest);
            $success = move_uploaded_file($fileTmp, $fileDest);                         
        } else {
            $success = move_uploaded_file($fileTmp, $fileDest);                         
        }

        $newAvatar = APP_URL . $imgFolder . $fileName;
    
        return $newAvatar;
    }
}
