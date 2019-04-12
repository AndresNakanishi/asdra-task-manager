<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupUsersTable Test Case
 */
class GroupUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupUsersTable
     */
    public $GroupUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GroupUsers',
        'app.Groups',
        'app.Users',
        'app.GroupTypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupUsers') ? [] : ['className' => GroupUsersTable::class];
        $this->GroupUsers = TableRegistry::getTableLocator()->get('GroupUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupUsers);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
