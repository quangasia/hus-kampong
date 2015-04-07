<?php
namespace Aseagle\Backend\Manager\Base;

interface ObjectManagerInterface {
    public function getRepository();
        
    public function createObject();
    
    public function getObject($gid);
}