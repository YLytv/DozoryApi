<?php
namespace DozoryApi\Organization;


class StorageOperation extends \SplEnum
{
    const FROM_OFFICE_TO_PRIVATESHOPSTORAGE                 = "from_office_to_privateshopstorage";
    const FROM_PRIVATESHOPSTORAGE_TO_OFFICE                 = "from_privateshopstorage_to_office";
    const FROM_OFFICE_AUXILIARYINV_TO_PRIVATESHOPSTORAGE    = "from_office_auxiliaryinv_to_privateshopstorage";
    const FROM_PRIVATESHOPSTORAGE_TO_OFFICE_AUXILIARYINV    = "from_privateshopstorage_to_office_auxiliaryinv";
    const FROM_OFFICE_TO_ACTOR                              = "from_office_to_actor";
    const FROM_ACTOR_TO_OFFICE                              = "from_actor_to_office";
    const FROM_ARTEFACTCREATOR_TO_OFFICE                    = "from_artefactcreator_to_office";
    const FROM_OFFICE_TO_ARTEFACTRECYCLE                    = "from_office_to_artefactrecycle";
    const FROM_OFFICEMACHINE_TO_OFFICE                      = "from_officemachine_to_office";
    const FROM_OFFICE_TO_OFFICEMACHINE                      = "from_office_to_officemachine";
    const FROM_OFFICEMACHINE_TO_ACTOR                       = "from_officemachine_to_actor";
    const FROM_ACTOR_TO_OFFICE_MODS                         = "from_actor_to_office_mods";
    const FROM_RUCKSACK_TO_OFFICE                           = "from_rucksack_to_office";
    const FROM_OFFICE_AUXILIARYINV_TO_ACTOR                 = "from_office_auxiliaryinv_to_actor";
    const FROM_OFFICE_MODS_TO_ACTOR                         = "from_office_mods_to_actor";
    const FROM_ACTOR_TO_OFFICE_LIBRARY                      = "from_actor_to_office_library";
    const FROM_OFFICE_LIBRARY_TO_ACTOR                      = "from_office_library_to_actor";
    const FROM_ACTOR_TO_OFFICE_AUXILIARYINV                 = "from_actor_to_office_auxiliaryinv";
    const FROM_ACTOR_TO_OFFICE_PROF_INVENTORY               = "from_actor_to_office_prof_inventory";
    const FROM_OFFICE_PROF_INVENTORY_TO_ACTOR               = "from_office_prof_inventory_to_actor";
}