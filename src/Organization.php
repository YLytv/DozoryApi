<?php
namespace DozoryApi;


use DateTime;
use DozoryApi\Organization\StorageType;
use DozoryApi\Organization\TreasuryType;
use DozoryApi\Profile\Status;
use Exception;
use InvalidArgumentException;

class Organization
{
    protected $org_id = "";
    protected $password = "";

    private function __construct($org_id, $password)
    {
        $this->org_id   = $org_id;
        $this->password = $password;
    }

    public function getMembers()
    {
        $result = Organization::getOrgMembers([$this->org_id]);

        if (!empty($result) && !empty($result[$this->org_id])) {
            $result = $result[$this->org_id];
        } else {
            throw new InvalidArgumentException("Empty list for org_id: {{$this->org_id}}");
        }

        return $result;
    }

    public function getOnline($person_ids)
    {
        $result = [];

        if (!is_array($person_ids)) {
            throw new InvalidArgumentException("person_ids must be array");
        }

        if (!count($person_ids)) {
            return $result;
        }

        $org_id = $this->org_id;
        $dt = new DateTime();
        $dt = $dt->format("d.m.Y");
        $sign = md5($org_id.$this->password.$dt);

        $url = "http://api.dozory.ru/query/?rm=org_member_status&org_id={$org_id}&date={$dt}&sign={$sign}";
        foreach ($person_ids as $id) {
            $url .= "&person_id=" . $id;
        }

        $xml = simplexml_load_file($url);
        if (empty($xml)) {
            throw new InvalidArgumentException("Can't load data from host");
        } else {
            if (isset($xml->error)) {
                throw new Exception("API error: ".(string)$xml->error);
            }

            foreach ($xml->children() as $elem)
            {
                $profile_id = (int)$elem['id'];
                $profile_status = (string)$elem['status'];
                $result[$profile_id] = new Status($profile_status);
            }
        }

        return $result;
    }

    public function getTreasury(TreasuryType $type, \DateTime $date)
    {
        $result = [];

        $org_id = $this->org_id;

        $dt = $date->format("d.m.Y");
        $tp = (string)$type;
        $sign = md5($org_id.$this->password.$dt.$tp);

        $url = "http://api.dozory.ru/query/?rm=org_treasury_info&org_id={$org_id}&date={$dt}&type={$tp}&sign={$sign}";

        $xml = simplexml_load_file($url);
        if (empty($xml)) {
            throw new InvalidArgumentException("Can't load data from host");
        } else {
            if (isset($xml->error)) {
                throw new Exception("API error: ".(string)$xml->error);
            }

            foreach ($xml->children() as $elem)
            {
                $result[] = [
                    'date'      => DateTime::createFromFormat("d/m/y H:i", $elem['date']),
                    'nick'      => (string)$elem->nick,
                    'direction' => (string)$elem->direction,
                    'value'     => (float)$elem->value,
                ];
            }
        }
        return $result;
    }

    public function getStorage(StorageType $type, \DateTime $date)
    {
        $result = [];

        $org_id = $this->org_id;

        $dt = $date->format("d.m.Y");
        $tp = (string)$type;
        $sign = md5($org_id.$this->password.$dt.$tp);

        $url = "http://api.dozory.ru/query/?rm=org_storage_info&org_id={$org_id}&date={$dt}&type={$tp}&sign={$sign}";

        $xml = simplexml_load_file($url);
        if (empty($xml)) {
            throw new InvalidArgumentException("Can't load data from host");
        } else {
            if (isset($xml->error)) {
                $message = (string)$xml->error;
                if ($message == "Нет данных") {
                    return $result;
                }
                throw new Exception("API error: " . $message);
            }

            foreach ($xml->children() as $elem)
            {
                $result[] = [
                    'date'      => DateTime::createFromFormat("d/m/y H:i", $elem['date']),
                    'nick'      => (string)$elem->person,
                    'person_id' => (int)$elem->person_id,
                    'direction' => (string)$elem->type_action,
                    'item'      => (float)$elem->item,
                    'item_id'   => (float)$elem->instance_id,
                ];
            }
        }
        return $result;
    }

    public static function get($org_id, $password)
    {
        if (empty($org_id)) {
            throw new InvalidArgumentException("org_id can't be empty");
        }

        if (empty($password)) {
            throw new InvalidArgumentException("password can't be empty");
        }

        return new Organization($org_id, $password);
    }

    public static function getOrgMembers($org_ids)
    {
        $result = [];

        if (!is_array($org_ids)) {
            throw new InvalidArgumentException("org_ids must be array");
        }

        if (!count($org_ids)) {
            return $result;
        }

        $url = "http://api.dozory.ru/query/?rm=org_members";
        foreach ($org_ids as $id) {
            $url .= "&org_id=" . $id;
        }

        $xml = simplexml_load_file($url);

        if (empty($xml)) {
            throw new Exception("Can't load data from host");
        } else {
            foreach ($xml->children() as $row){
                $org_id     = (string)$row['id'];
                $org_name   = (string)$row['name'];

                $members = [];
                $reserve = [];

                foreach ($row->members->person as $person) {
                    $members[(string)$person['id']] = (string)$person->position;
                }

                foreach ($row->reserve->person as $person) {
                    $reserve[(string)$person['id']] = (string)$person->position;
                }

                $result[$org_id] = [
                    'name'      => $org_name,
                    'members'   => $members,
                    'reserve'   => $reserve,
                ];
            }
        }

        return $result;
    }
}