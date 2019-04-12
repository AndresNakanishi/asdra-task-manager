<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SupervisorsFixture
 */
class SupervisorsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'person_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'user_id de la persona que es supervisada', 'precision' => null, 'autoIncrement' => null],
        'supervisor_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'user_id del supervisor de las personas.', 'precision' => null, 'autoIncrement' => null],
        'rol' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => null, 'collate' => 'utf8_spanish_ci', 'comment' => 'Los roles de supervision que pueden ser asignados son: CHF: Jefe  o TUT: Tutor', 'precision' => null, 'fixed' => null],
        'company_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'srv_usr_fk2' => ['type' => 'index', 'columns' => ['supervisor_id'], 'length' => []],
            'srv_cpy_fk_idx' => ['type' => 'index', 'columns' => ['company_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['person_id', 'supervisor_id', 'rol'], 'length' => []],
            'srv_cpy_fk' => ['type' => 'foreign', 'columns' => ['company_id'], 'references' => ['companies', 'company_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'srv_usr_fk' => ['type' => 'foreign', 'columns' => ['person_id'], 'references' => ['users', 'user_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'srv_usr_fk2' => ['type' => 'foreign', 'columns' => ['supervisor_id'], 'references' => ['users', 'user_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_spanish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'person_id' => 1,
                'supervisor_id' => 1,
                'rol' => '86cb4078-156f-480f-8688-be463e28c6b6',
                'company_id' => 1
            ],
        ];
        parent::init();
    }
}
