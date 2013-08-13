<?php

namespace NinjaTooken\ClanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Clan
 *
 * @ORM\Table(name="nt_clan")
 * @ORM\Entity(repositoryClass="NinjaTooken\ClanBundle\Entity\ClanRepository")
 */
class Clan
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="old_id", type="integer")
     */
    private $old_id;

    /**
     * @ORM\OneToMany(targetEntity="NinjaTooken\ClanBundle\Entity\ClanUtilisateur", mappedBy="clan", cascade={"persist", "remove"})
     */
    private $membres;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=5)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="kamon", type="string", length=255)
     */
    private $kamon;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime")
     */
    private $dateAjout;

    /**
     * @var boolean
     *
     * @ORM\Column(name="online", type="boolean")
     */
    private $online;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_recruting", type="boolean")
     */
    private $isRecruting;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Clan
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return Clan
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Clan
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
     * Set url
     *
     * @param string $url
     * @return Clan
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set kamon
     *
     * @param string $kamon
     * @return Clan
     */
    public function setKamon($kamon)
    {
        $this->kamon = $kamon;

        return $this;
    }

    /**
     * Get kamon
     *
     * @return string 
     */
    public function getKamon()
    {
        return $this->kamon;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return Clan
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime 
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Clan
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Clan
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set old_id
     *
     * @param integer $oldId
     * @return Clan
     */
    public function setOldId($oldId)
    {
        $this->old_id = $oldId;

        return $this;
    }

    /**
     * Get old_id
     *
     * @return integer 
     */
    public function getOldId()
    {
        return $this->old_id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add membres
     *
     * @param \NinjaTooken\ClanBundle\Entity\ClanUtilisateur $membres
     * @return Clan
     */
    public function addMembre(\NinjaTooken\ClanBundle\Entity\ClanUtilisateur $membres)
    {
        $this->membres[] = $membres;

        return $this;
    }

    /**
     * Remove membres
     *
     * @param \NinjaTooken\ClanBundle\Entity\ClanUtilisateur $membres
     */
    public function removeMembre(\NinjaTooken\ClanBundle\Entity\ClanUtilisateur $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * Set isRecruting
     *
     * @param boolean $isRecruting
     * @return Clan
     */
    public function setIsRecruting($isRecruting)
    {
        $this->isRecruting = $isRecruting;

        return $this;
    }

    /**
     * Get isRecruting
     *
     * @return boolean 
     */
    public function getIsRecruting()
    {
        return $this->isRecruting;
    }
}