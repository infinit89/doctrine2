<?php

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\Tests\OrmFunctionalTestCase;

/**
 * @group issue-2577
 */
class Issue2577Test extends OrmFunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();

        $conn = $this->_em->getConnection();

        if (strpos($conn->getDriver()->getName(), 'sqlite') === false) {
            $this->markTestSkipped('Tables with dots just with SQLite');
        }
    }

    public function testCreateTableAndDropTableConvertsDotsEqually()
    {

        $classes = [
            $this->_em->getClassMetadata(Aaabbb::class),
        ];

        $createSchemaSql = $this->_schemaTool->getCreateSchemaSql($classes);

        self::assertEquals(
            $createSchemaSql,
            ['CREATE TABLE aaa__bbb (id INTEGER NOT NULL, PRIMARY KEY(id))']
        );

        $this->_schemaTool->createSchema($classes);

        $dropSchemaSql = $this->_schemaTool->getDropSchemaSQL($classes);

        self::assertEquals(
            $dropSchemaSql,
            ['DROP TABLE aaa__bbb']
        );

    }
}

/**
 * @Table(name="aaa.bbb")
 * @Entity()
 */
class Aaabbb
{
    /**
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    public $id;
}