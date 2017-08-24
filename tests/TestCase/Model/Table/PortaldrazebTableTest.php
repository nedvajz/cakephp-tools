<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PortaldrazebTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PortaldrazebTable Test Case
 */
class PortaldrazebTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PortaldrazebTable
     */
    public $Portaldrazeb;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.portaldrazeb'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Portaldrazeb') ? [] : ['className' => PortaldrazebTable::class];
        $this->Portaldrazeb = TableRegistry::get('Portaldrazeb', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Portaldrazeb);

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
