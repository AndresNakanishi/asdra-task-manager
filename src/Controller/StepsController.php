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
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['required'] = 1;
            $data['task_id'] = $id;
            if ($data['photo'] == '') {
                $data['photo'] = null;
                unset($data['avatar_code']);
            } else {
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
                $this->Flash->success(__('<b>El paso fue configurado correctamente!</b>'));

                return $this->redirect(['controller' => 'Tasks', 'action' => 'view',$id]);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $this->set(compact('step', 'steps'));
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
            'contain' => []
        ]);
        $steps = $this->Steps->find('all', ['conditions' => ['task_id' => $step->task_id]])->count();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if ($data['photo'] == '') {
                $data['photo'] = null;
                unset($data['avatar_code']);
            } else {
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
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $this->set(compact('step', 'steps'));
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
        if ($this->Steps->delete($step)) {
            $this->Flash->success(__('<b>El paso fue eliminado correctamente!</b>'));
        } else {
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }

        return $this->redirect(['controller' => 'Tasks', 'action' => 'view',$step->task_id]);
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
}
