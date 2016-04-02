<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Kampong\Bundle\SiteBundle\Service;

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
        $locale = $this->container->get('request')->getLocale();
        if (!isset($this->arrSetting[$locale])) {
            $settings = $this->container->get('backend')->getSettingManager()->getRepository()->findBy(array('locale'=>$locale));
            $arrSetting[$locale] = array();
            foreach ($settings as $item) {
                $arrSetting[$item->getKey()] = $item->getValue();
            }
            $this->arrSetting[$locale] = $arrSetting;
        } else {
            $arrSetting = $this->arrSetting[$locale];
        }
     
        return $arrSetting;
    }
}
?>
