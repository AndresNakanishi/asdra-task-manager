<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupUsersFixture
 */
class GroupUsersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'group_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'date_from' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => 'Fecha a partir de la cual es valido este grupo de tareas para el usuario asignado.', 'precision' => null],
        'date_to' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => 'Fecha hasta la cual es valido el grupo de tareas.', 'precision' => null],
        'start_time' => ['type' => 'time', 'length' => null, 'null' => false, 'default' => null, 'comment' => 'Horario a partir del cual se debe ejecutar el grupo de tareas.', 'precision' => null],
        'end_time' => ['type' => 'time', 'length' => null, 'null' => true, 'default' => null, 'comment' => 'Horario hasta el cual se puede ejecutar este grupo de tareas.', 'precision' => null],
        'repetition' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => null, 'collate' => 'utf8_spanish_ci', 'comment' => 'determina cada cuantos dias se tiene que repetir la tarea, Valores aceptados son DIA, SEM, MES, o un valor numerico que representa la cantidad de días.', 'precision' => null, 'fixed' => null],
        'rep_days' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'utf8_spanish_ci', 'comment' => 'En caso que la tarea sea semanal, puede repetirse algunos dias de la semana, aqui va ese valor: Ej: LU,MA,MI,JU,VI o MA,JU. En caso de ser MES, este valor toma un  numero que identifica el dia en que se repite: Ej: 12, 7, 28. en caso que ese dia no exista en un mes especifico, entonces devolvera el proximo anterior.', 'precision' => null, 'fixed' => null],
        'group_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'gur_usr_fk_idx' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'gur_gtp_fk_idx' => ['type' => 'index', 'columns' => ['group_type_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['group_id', 'user_id', 'date_from'], 'length' => []],
            'gur_grp_fk' => ['type' => 'foreign', 'columns' => ['group_id'], 'references' => ['groups', 'group_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'gur_usr_fk' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'user_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'group_id' => 1,
                'user_id' => 1,
                'date_from' => '2019-04-12 18:47:30',
                'date_to' => '2019-04-12 18:47:30',
                'start_time' => '18:47:30',
                'end_time' => '18:47:30',
                'repetition' => 'L',
                'rep_days' => 'Lorem ipsum dolor sit amet',
                'group_type_id' => 1
            ],
        ];
        parent::init();
    }
}
