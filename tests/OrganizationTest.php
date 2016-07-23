<?php
namespace DozoryApi;


use DozoryApi\Organization\StorageType;
use DozoryApi\Organization\TreasuryType;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{

    private $option_password = "";
    private $option_org_id = "";

    public function testOrgMembers()
    {
        $org_ids = [];
        $list = Organization::getOrgMembers($org_ids);
        $this->assertEquals(count($list), count($org_ids));
    }

    public function testMembers()
    {
        $org = Organization::get($this->option_org_id, $this->option_password);
        $list = $org->getMembers();

        $this->assertNotEmpty($list);
    }

    public function testOnline()
    {
        $ids = [];
        $org = Organization::get($this->option_org_id, $this->option_password);
        $list = $org->getOnline($ids);

        $this->assertNotEmpty($list);
    }

    public function testTreasureMoney()
    {
        $org = Organization::get($this->option_org_id, $this->option_password);
        $date = new \DateTime();
        $list = $org->getTreasury(new TreasuryType(TreasuryType::MONEY), $date);

        $this->assertNotEmpty($list);
    }

    public function testStorageMain()
    {
        $org = Organization::get($this->option_org_id, $this->option_password);
        $date = new \DateTime();
        $list = $org->getStorage(new StorageType(StorageType::MAIN), $date);

        $this->assertNotEmpty($list);
    }
}
