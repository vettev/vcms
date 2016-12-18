<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="routeId", type="integer", nullable=true)
     */
    private $routeId;

    /**
     * @var string
     *
     * @ORM\Column(name="customUrl", type="string", nullable=true)
     */
    private $customUrl;

    /**
     * @var bool
     *
     * @ORM\Column(name="displayFor", type="string", length=16)
     */
    private $displayFor;

    /**
     * @ORM\ManyToOne(targetEntity="Route", inversedBy="links")
     * @ORM\JoinColumn(name="routeId", referencedColumnName="id")
     */
    private $route;


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
     * Set name
     *
     * @param string $name
     *
     * @return Menu
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set routeId
     *
     * @param int $routeId
     *
     * @return Menu
     */
    public function setRouteId($routeId)
    {
        $this->routeId = $routeId;

        return $this;
    }

    /**
     * Get routeId
     *
     * @return int
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    /**
     * Set customUrl
     *
     * @param string $customUrl
     *
     * @return Menu
     */
    public function setCustomUrl($customUrl)
    {
        $this->customUrl = $customUrl;

        return $this;
    }

    /**
     * Get customUrl
     *
     * @return string
     */
    public function getCustomUrl()
    {
        return $this->customUrl;
    }

    /**
     * Set displayFor
     *
     * @param string $displayFor
     *
     * @return Menu
     */
    public function setDisplayFor($displayFor)
    {
        $this->displayFor = $displayFor;

        return $this;
    }

    /**
     * Get displayFor
     *
     * @return string
     */
    public function getDisplayFor()
    {
        return $this->displayFor;
    }

    /**
     * Get route
     *
     * @return Route 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route
     *
     * @param Route $route
     *
     * @return Menu
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }
}

