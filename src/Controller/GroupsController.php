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
        $session = $this->getRequest()->getSession();
        
        $this->viewBuilder()->setLayout('asdra-layout');

        $asdra = TableRegistry::get('companies')->find('all', ['conditions' => ['company_name' => 'ASDRA']])->first();
        
        if ($asdra->company_id == $this->Auth->user('company_id')) {
            $groups = $this->Groups->find('all', ['contain' => ['Companies']])->all();
        } else {
            $groups = $this->Groups->find('all', ['contain' => ['Companies']])
            ->where([
                'Groups.company_id' => $this->Auth->user('company_id')
            ])
            ->all();
        }

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
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $group = $this->Groups->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if ($data['image'] == '') {
                $data['image'] = ' ';
                unset($data['avatar_code']);
            } else {
                // Image            
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                    // Set Photo
                $data['image'] = $this->setAvatar($data['image'], $data['title'], $fileData);
                // Image
            }

            $data['title'] = strtoupper($data['title']);
            $data['description'] = strtoupper($data['description']);
            $group = $this->Groups->patchEntity($group, $data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('<b>El grupo de tareas se configuró correctamente!</b>'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Recuerde que es necesario cargar el <b>título</b> y la <b>imagen</b> como mínimo!'));
        }
        $companies = TableRegistry::get('companies')->find('list')->order(['company_name' => 'ASC']);
        $this->set(compact('group','companies'));
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
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            if ($data['image'] == '') {
                unset($data['image']);
                unset($data['avatar_code']);
            } else {
                // Image            
                $img = $this->request->data('avatar-code');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                unset($data['avatar-code']);
                    // Set Photo
                $data['image'] = $this->setAvatar($data['image'], $data['title'], $fileData);
                // Image
            }

            $data['title'] = strtoupper($data['title']);
            $data['description'] = strtoupper($data['description']);
            $group = $this->Groups->patchEntity($group, $data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('<b>Se editó correctamente!</b>'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde por favor...'));
        }
        $companies = TableRegistry::get('companies')->find('list')->order(['company_name' => 'ASC']);
        $this->set(compact('group','companies'));
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
        $deleted = false;

        try {
            $deleted = $this->Groups->delete($group);
        } catch(\Exception $e) {
            
        }

        if ($deleted) {
            $this->Flash->success(__('<b>El Grupo de tareas ha sido borrado!</b>'));
        } else {
            $this->Flash->warning(__('<b>Por Seguridad:</b> Para poder borrar un grupo de tareas, debe <b>primero borrar sus tareas y pasos.</b>'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // Set or Update User Photo / Avatar
    // Author: Ricardo Andrés Nakanishi || Last Update: 10/04/2019
    private function setAvatar($avatar, $name, $img){
        // Esta URL se usará una vez se configure de manera correcta la escritura.
        // $url = 'http://190.188.102.60:8089/organizerApi/public/';
        $url = WWW_ROOT;
        // Use a HASH with SHA1 to save our img
        $hash = sha1($avatar.$name);
        // We'll save our users img in this folder
        $imgFolder = "img/groups/";
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