<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StepsOptionsFixture
 */
class StepsOptionsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'step_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'option_order' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'Orden de aparicion que aparece en pantalla la opcion, numero entero a partir de 1. En la version demo, solo utilizaremos: 1= Boton Rojo, 2=Boton Verde.', 'precision' => null, 'autoIncrement' => null],
        'option_description' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'collate' => 'utf8_spanish_ci', 'comment' => 'descripcion que se verá en el boton en pantalla', 'precision' => null, 'fixed' => null],
        'next_step_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'Paso al que direccionará el sistema si el usuario presiona la opción.', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'son_stp_fk2_idx' => ['type' => 'index', 'columns' => ['next_step_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['step_id', 'option_order'], 'length' => []],
            'sop_stp_fk' => ['type' => 'foreign', 'columns' => ['step_id'], 'references' => ['steps', 'step_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'step_id' => 1,
                'option_order' => 1,
                'option_description' => 'Lorem ipsum dolor ',
                'next_step_id' => 1
            ],
        ];
        parent::init();
    }
}
