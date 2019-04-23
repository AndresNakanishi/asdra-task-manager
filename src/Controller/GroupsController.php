<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 *
 * @method \App\Model\Entity\Group[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $groups = $this->paginate($this->Groups);

        $this->set(compact('groups'));
    }

    /**
     * View method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        $tasks = TableRegistry::get('tasks')->find('all', ['conditions' => ['group_id' => $id]])->order(['task_order' => 'ASC'])->all();
        $this->set(compact('group','tasks'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $group = $this->Groups->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['image'] = $this->setImage($data['image'], $data['title']);
            $group = $this->Groups->patchEntity($group, $data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('<b>El grupo de tareas se configuró correctamente!</b>'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $this->set(compact('group'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('asdra-layout');
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if ($data['image']['tmp_name'] == '') {
                unset($data['image']);
            } else {
                $data['image'] = $this->setImage($data['image'], $data['title']);
            }
            $group = $this->Groups->patchEntity($group, $data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('<b>Se editó correctamente!</b>'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $this->set(compact('group'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $group = $this->Groups->get($id);

        if ($this->Groups->delete($group)) {
            $this->Flash->success(__('<b>El Grupo de tareas ha sido borrado!</b>'));
        } else {
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // Set or Update Group Image
    // Author: Ricardo Andrés Nakanishi || Last Update: 10/04/2019
    private function setImage($avatar, $name){
        // Esta URL se usará una vez se configure de manera correcta la escritura.
        // $url = 'http://190.188.102.60:8089/organizerApi/public/';
        $url = WWW_ROOT;
        // Use a HASH with SHA1 to save our img
        $hash = sha1($avatar["tmp_name"].$name);
        // We'll save our users img in this folder
        $imgFolder = "img/groups/";
        // Link that we are going to save
        $folder = $url . $imgFolder;
        // Out FileName
        $fileName = "$hash.jpg";
        // Temporal File
        $fileTmp = $avatar["tmp_name"];
        // Entire URL
        $fileDest = $folder . $fileName;
            
        if ($avatar['name'] == '') {
            $newAvatar = null;
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
            $newAvatar = $imgFolder . $fileName;
        }
        return $newAvatar;
    }

}