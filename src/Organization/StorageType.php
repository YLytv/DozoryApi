<?php
namespace DozoryApi\Organization;

class StorageType extends \SplEnum
{
    const MAIN          = 'storage';
    const ADDITIONAL    = 'aux_storage';
    const MODIFICATIONS = 'mods_storage';
    const PROFESSIONAL  = 'prof_storage';
    const LIBRARY       = 'mods_storage';
}