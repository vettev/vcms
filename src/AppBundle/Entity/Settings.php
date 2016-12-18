<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SettingsRepository")
 */
class Settings
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=32, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="customMenu", type="boolean", options={"default"=false}, nullable=true)
     */
    private $customMenu;

    /**
     * @var string
     *
     * @ORM\Column(name="footerContent", type="text", nullable=true)
     */
    private $footerContent;

    /**
     * @var int
     *
     * @ORM\Column(name="homepage", type="integer", nullable=true)
     * @ORM\OneToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="homepage", referencedColumnName="id", onDelete="CASCADE")
     */
    private $homepage;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Settings
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Settings
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set customMenu
     *
     * @param boolean $customMenu
     *
     * @return Settings
     */
    public function setCustomMenu($customMenu)
    {
        $this->customMenu = $customMenu;

        return $this;
    }

    /**
     * Get customMenu
     *
     * @return bool
     */
    public function getCustomMenu()
    {
        return $this->customMenu;
    }

    /**
     * Set footerContent
     *
     * @param string $footerContent
     *
     * @return Settings
     */
    public function setFooterContent($footerContent)
    {
        $this->footerContent = $footerContent;

        return $this;
    }

    /**
     * Get footerContent
     *
     * @return string
     */
    public function getFooterContent()
    {
        return $this->footerContent;
    }
    /**
     * Set homepage
     *
     * @param int $homepage
     *
     * @return Settings
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * Get homepage
     *
     * @return int
     */
    public function getHomepage()
    {
        return $this->homepage;
    }
}

