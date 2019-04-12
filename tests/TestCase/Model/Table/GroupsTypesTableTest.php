<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupsTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupsTypesTable Test Case
 */
class GroupsTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupsTypesTable
     */
    public $GroupsTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GroupsTypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupsTypes') ? [] : ['className' => GroupsTypesTable::class];
        $this->GroupsTypes = TableRegistry::getTableLocator()->get('GroupsTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupsTypes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
