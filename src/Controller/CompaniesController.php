<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Companies Controller
 *
 * @property \App\Model\Table\CompaniesTable $Companies
 *
 * @method \App\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompaniesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function initComp()
    {
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();
        $session->delete('is_search');
        $session->delete('filter');
        return $this->redirect(['action' => 'index']);
    }


    public function index()
    {
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $this->set('is_search', 0);
        // Búsqueda

        if ($this->request->is('post')) {
            $session->delete('is_search');
            $session->delete('filter');
    
            $filter = $this->request->getData()['filter'];

            $session->write('is_search', 1);
            $session->write('filter', $filter);
            
            $companies = $this->getCompanies($filter);

            $this->set('is_search', 1);
            $this->set('filter', $filter);
        } else {
            $companies = $this->getCompanies();
        }

        // Set Users
        $this->set(compact('companies'));
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
        $company = $this->Companies->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['company_name'] = strtoupper($data['company_name']);
            $company = $this->Companies->patchEntity($company, $data);
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('La compañía ha sido guardada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde...'));
        }
        $this->set(compact('company'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $session = $this->getRequest()->getSession();
        $this->viewBuilder()->setLayout('asdra-layout');
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['company_name'] = strtoupper($data['company_name']);
            $company = $this->Companies->patchEntity($company, $data);
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('La compañía ha sido guardada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Hubo un error! Intente más tarde...'));
        }
        $this->set(compact('company'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $company = $this->Companies->get($id);
        if ($this->Companies->delete($company)) {
            $this->Flash->success(__('La compañia ha sido eliminada.'));
        } else {
            $this->Flash->error(__('La compañia no ha sido eliminada. Intente más tarde...'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function getCompanies($filter = null)
    {
        $filter = strtoupper($filter);
        $companyTable = TableRegistry::get('companies');

        $companies = $companyTable->find()
            ->select([
                'id' => 'companies.company_id',
                'name' => 'companies.company_name'
                ])
            ->where(['companies.company_name LIKE' => '%'.$filter.'%'])
            ->enableHydration(false)
            ->toList();

        return $companies;
    }
}
