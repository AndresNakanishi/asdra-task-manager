<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StepsOptionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StepsOptionsTable Test Case
 */
class StepsOptionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StepsOptionsTable
     */
    public $StepsOptions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.StepsOptions',
        'app.Steps',
        'app.NextSteps'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('StepsOptions') ? [] : ['className' => StepsOptionsTable::class];
        $this->StepsOptions = TableRegistry::getTableLocator()->get('StepsOptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StepsOptions);

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
