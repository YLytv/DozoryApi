<?php

namespace DozoryApi;


use Doctrine\Instantiator\Exception\InvalidArgumentException;
use DozoryApi\Profile\ClassType;
use DozoryApi\Profile\MagicAlign;
use DozoryApi\Profile\Sex;
use DozoryApi\Profile\Tendency;
use UnexpectedValueException;

class Profile
{
    const MAX_COUNT = 50;

    protected $person_id;
    protected $nick;
    protected $magic_level;
    protected $magic_align;
    protected $org_id;
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
     * @param Integer $magic_level
     * @param Integer $org_id
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
    private function __construct(
                    $person_id,
                    $nick,
                    $magic_level,
        MagicAlign  $magic_align,
                    $org_id,
                    $max_stamina,
                    $max_energy,
        ClassType   $class_type_id,
        Sex         $sex,
        Tendency    $tendency,
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
        $this->org_id           = $org_id;

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


    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * @return String
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @return int
     */
    public function getMagicLevel()
    {
        return $this->magic_level;
    }

    /**
     * @return MagicAlign
     */
    public function getMagicAlign()
    {
        return $this->magic_align;
    }

    /**
     * @return int
     */
    public function getOrgId()
    {
        return $this->org_id;
    }

    /**
     * @return int
     */
    public function getMaxStamina()
    {
        return $this->max_stamina;
    }

    /**
     * @return int
     */
    public function getMaxEnergy()
    {
        return $this->max_energy;
    }

    /**
     * @return ClassType
     */
    public function getClassTypeId()
    {
        return $this->class_type_id;
    }

    /**
     * @return Sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @return Tendency
     */
    public function getTendency()
    {
        return $this->tendency;
    }

    /**
     * @return int
     */
    public function getRegDate()
    {
        return $this->reg_date;
    }

    /**
     * @return int
     */
    public function getInitDate()
    {
        return $this->init_date;
    }

    /**
     * @return int
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * @return int
     */
    public function getOnlineTime()
    {
        return $this->online_time;
    }

    /**
     * @return int
     */
    public function getCntWins()
    {
        return $this->cnt_wins;
    }

    /**
     * @return int
     */
    public function getCntLose()
    {
        return $this->cnt_lose;
    }

    /**
     * @param  array[int] $person_ids
     * @return array[DozoryApi\Profile]
     */
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

                $fields = [];

                $enumFields = [
                    'magic_align'   => MagicAlign::class,
                    'class_type_id' => ClassType::class,
                    'tendency'      => Tendency::class,
                    'sex'           => Sex::class,
                ];

                foreach ($enumFields as $field => $fieldClass)
                {
                    try {
                        $item = (string)$row[$field];
                        $item = strtolower($item);
                        $fields[$field] = new $fieldClass($item);
                    } catch (UnexpectedValueException $ex) {
                        throw new UnexpectedValueException("Incorrect {{$field}} for profileID: {{$person_id}}, 
                                                        value: " . (string)$row[$field]);
                    }
                }

                $result[$person_id] = new Profile(
                    $person_id,
                    (string)$row['nick'],
                    (string)$row['magic_level'],
                    $fields['magic_align'],
                    (string)$row['org_id'],
                    (string)$row['max_stamina'],
                    (string)$row['max_energy'],
                    $fields['class_type_id'],
                    $fields['sex'],
                    $fields['tendency'],
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