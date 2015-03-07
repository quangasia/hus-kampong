<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\FrontBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Settings Service
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class Settings
{
    private $container;
    private $arrSetting;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getAll()
    {
        if (!isset($this->arrSetting)) {
            $settings = $this->container->get('setting_manager')->getRepository()->findAll();
            $arrSetting = array();
            foreach ($settings as $item) {
                $arrSetting[$item->getKey()] = $item->getValue();
            }
        } else {
            $arrSetting = $this->arrSetting;
        }
     
        return $arrSetting;
    }
}
?>
