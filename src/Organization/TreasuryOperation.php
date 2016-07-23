<?php

namespace DozoryApi\Organization;


/**
 * Список операций с казной организаций
 *
 * @package DozoryApi\Organization
 */
class TreasuryOperation extends \SplEnum
{
    /**
     * Положил
     */
    const to_treasury       = "to_treasury";

    /**
     * Взял
     */
    const to_person         = "to_person";

    /**
     * Купил МАЭ
     */
    const to_order_table    = "to_order_table";

    /**
     * Обменял на рубли
     */
    const taler_exchange    = "taler_exchange";

    /**
     * Талерленд
     */
    const talerland         = "talerland";
}
