<?php 
// src/AppBundle/Twig/GlobalsExtension.php
namespace AppBundle\Twig;

use Symfony\Component\Yaml\Yaml;

class GlobalsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
	private $title;
	private $description;
	private $customMenu;
	private $footerContent;
	private $homepage;

	public function __construct($em, $container)
	{
		$configFile = $container->get('kernel')->getRootDir() . '/config/parameters.yml';

        $parameters = Yaml::parse(file_get_contents($configFile))['parameters'];
        if((isset($parameters["settings_flag"]) && $parameters['settings_flag']))
        {
			$settings = $em->getRepository('AppBundle:Settings')->findOneBy([]);
			if($settings)
			{
				$this->title = $settings->getTitle();
				$this->description = $settings->getDescription();
				$this->customMenu = $settings->getCustomMenu();
				$this->footerContent = $settings->getFooterContent();
				$this->homepage = $settings->getHomepage();
			}
			else
			{
				$this->notSet();
			}        	
		}
    	else
    	{
    		$this->notSet();
    	}
	}
	private function notSet()
	{
		$this->title = "Title not set yet.";
		$this->description = "No description.";
		$this->menuCustom = false;
		$this->footerContent = "";
	}

	public function getGlobals()
	{
		return ['pageTitle' => $this->title, 'pageDesc' => $this->description, 'customMenu' => $this->customMenu, 'footerContent' => $this->footerContent, 'homepage' => $this->homepage];
	}
}
?>