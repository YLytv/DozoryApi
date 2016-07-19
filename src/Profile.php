<?php

namespace DozoryApi;


use Doctrine\Instantiator\Exception\InvalidArgumentException;
use DozoryApi\Profile\ClassType;
use DozoryApi\Profile\MagicAlign;
use DozoryApi\Profile\Sex;
use DozoryApi\Profile\Tendency;
use phpDocumentor\Reflection\Types\Integer;

class Profile
{

    const MAX_COUNT = 50;

    protected $person_id;
    protected $nick;
    protected $magic_level;
    protected $magic_align;
    protected $max_stamina;
    protected $max_energy;
    protected $class_type_id;
    protected $sex;
    protected $tendency;
    protected $reg_date;
    protected $init_date;
    protected $last_login;
    protected $online_time;
    protected $cnt_wins;
    protected $cnt_lose;

    /**
     * Profile constructor.
     * @param Integer $person_id
     * @param String $nick
     * @param $magic_level
     * @param \DozoryApi\Profile\MagicAlign $magic_align
     * @param Integer $max_stamina
     * @param Integer $max_energy
     * @param \DozoryApi\Profile\ClassType $class_type_id
     * @param \DozoryApi\Profile\Sex $sex
     * @param \DozoryApi\Profile\Tendency $tendency
     * @param Integer $reg_date
     * @param Integer $init_date
     * @param Integer $last_login
     * @param Integer $online_time
     * @param Integer $cnt_wins
     * @param Integer $cnt_lose
     */
    public function __construct(
        $person_id,
        $nick,
        $magic_level,
        $magic_align,
        $max_stamina,
        $max_energy,
        $class_type_id,
        $sex,
        $tendency,
        $reg_date,
        $init_date,
        $last_login,
        $online_time,
        $cnt_wins,
        $cnt_lose
    ) {
        $this->person_id        = (int)$person_id;
        $this->nick             = $nick;

        $this->magic_level      = (int)$magic_level;
        $this->magic_align      = $magic_align;

        $this->max_stamina      = (int)$max_stamina;
        $this->max_energy       = (int)$max_energy;

        $this->class_type_id    = $class_type_id;
        $this->sex              = $sex;
        $this->tendency         = $tendency;

        $this->reg_date         = (int)$reg_date;
        $this->init_date        = (int)$init_date;
        $this->last_login       = (int)$last_login;
        $this->online_time      = (int)$online_time;

        $this->cnt_wins         = (int)$cnt_wins;
        $this->cnt_lose         = (int)$cnt_lose;
    }

    public static function load($person_ids)
    {
        $result = [];

        if (!is_array($person_ids)) {
            throw new InvalidArgumentException("person_ids must be array");
        }

        if (!count($person_ids)) {
            return $result;
        }

        if (count($person_ids) > static::MAX_COUNT) {
            throw new InvalidArgumentException("person_ids can't be more then {{self::MAX_COUNT}} items");
        }

        $url = "http://api.dozory.ru/query/?rm=person_info";
        foreach ($person_ids as $id) {
            $url .= "&person_id=" . $id;
        }


        $xml = simplexml_load_file($url);

        if (empty($xml)) {
            throw new InvalidArgumentException("Can't load data from host");
        } else {
            foreach ($xml->children() as $row){

                $person_id = (string)$row['person_id'];

                $magic_align = MagicAlign::get((string)$row['magic_align']);
                if (empty($magic_align)) {
                    throw new InvalidArgumentException("Incorrect magic_align for profileID: {{$person_id}}, 
                                                        value: " . (string)$row['magic_align']);
                }

                $class_type_id = ClassType::get((string)$row['class_type_id']);
                if (empty($class_type_id)) {
                    throw new InvalidArgumentException("Incorrect magic_align for profileID: {{$person_id}}, 
                                                        value: " . (string)$row['class_type_id']);
                }

                $tendency = Tendency::get((string)$row['tendency']);
                if (is_null($tendency)) {
                    throw new InvalidArgumentException("Incorrect tendency for profileID: {{$person_id}}, 
                                                        value: " . (string)$row['tendency']);
                }                
                
                $sex = Sex::get((string)$row['sex']);
                if (is_null($sex)) {
                    throw new InvalidArgumentException("Incorrect sex for profileID: {{$person_id}}, 
                                                        value: " . (string)$row['sex']);
                }

                $result[] = new Profile(
                    $person_id,
                    (string)$row['nick'],
                    (string)$row['magic_level'],
                    $magic_align,
                    (string)$row['org_id'],
                    (string)$row['max_stamina'],
                    (string)$row['max_energy'],
                    $class_type_id,
                    $sex,
                    $tendency,
                    (string)$row['reg_date'],
                    (string)$row['init_date'],
                    (string)$row['last_login'],
                    (string)$row['online_time'],
                    (string)$row['cnt_wins'],
                    (string)$row['cnt_lose']
                );
            }
        }

        return $result;
    }
}