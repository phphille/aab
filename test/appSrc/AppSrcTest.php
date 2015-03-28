<?php

namespace Anax\DI;

/**
 * Testing the Dependency Injector service container.
 *
 */
class AppSrcTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test
     *
     */
    public function testAppSrcTest()
    {
        $di  = new \Anax\DI\CDIFactoryDefault();
        $ccu = new \Anax\Login\CCheckUser($di);

        $res = $ccu->GetLoginMenu(null);

        $this->assertNotNull($res);


        $res = $ccu->removeCorrectAnswer((object)array('id' => 2), 2, 1);

        $this->assertNotNull($res);
        $this->assertContainsOnly('string', array($res));


        $res = $ccu->editAnswer((object)array('id' => 2), 2, 2, 1);

        $this->assertNotNull($res);
        $this->assertContainsOnly('string', array($res));


        $res = $ccu->editQuestion((object)array('id' => 2), 2, 2);

        $this->assertNotNull($res);
        $this->assertContainsOnly('string', array($res));

        $res = $ccu->editQuestion((object)array('id' => 2), 2, 2, 2);

        $this->assertNotNull($res);
        $this->assertContainsOnly('string', array($res));



        $res = $ccu->voteUpAnswer(null, 2, 2, 2, 'question');

        $this->assertNotNull($res);
        $this->assertContainsOnly('string', array($res));


    }






}
