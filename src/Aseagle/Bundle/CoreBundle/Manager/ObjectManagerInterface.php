<?php
namespace Aseagle\Bundle\CoreBundle\Manager;

interface ObjectManagerInterface {
    public function getRepository();
        
    public function createObject();
    
    public function getObject($gid);
}