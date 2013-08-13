<?php
namespace NinjaTooken\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="nt_fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="NinjaTooken\GameBundle\Entity\Ninja", mappedBy="user")
     */
    private $ninja;

    /**
     * @ORM\OneToOne(targetEntity="NinjaTooken\ClanBundle\Entity\ClanUtilisateur", mappedBy="membre", cascade={"persist", "remove"})
     */
    private $clan;

    /**
     * @ORM\OneToMany(targetEntity="NinjaTooken\ClanBundle\Entity\ClanUtilisateur", mappedBy="recruteur", cascade={"persist", "remove"})
     */
    private $recruts;


     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    private $facebookId;

    /**
     * @var int
     *
     * @ORM\Column(name="old_id", type="integer")
     */
    private $old_id;

    /**
     * @Gedmo\Slug(fields={"username"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=4)
     */
    private $langue;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1)
     */
    private $sexe;

    /**
     * @var date
     *
     * @ORM\Column(name="date_naissance", type="date")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="receive_newsletter", type="boolean")
     */
    private $receiveNewsletter;

    /**
     * @var string
     *
     * @ORM\Column(name="receive_avertissement", type="boolean")
     */
    private $receiveAvertissement;

    /**
     * @var string
     *
     * @ORM\Column(name="old_username", type="text", nullable=true)
     */
    private $oldUsername;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime")
     */
    private $dateAjout;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set slug
     *
     * @param string $slug
     * @return User
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
     * Set langue
     *
     * @param string $langue
     * @return User
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return string 
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return User
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return User
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
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set oldUsername
     *
     * @param string $oldUsername
     * @return User
     */
    public function setOldUsername($oldUsername)
    {
        $this->oldUsername = $oldUsername;

        return $this;
    }

    /**
     * Get oldUsername
     *
     * @return string 
     */
    public function getOldUsername()
    {
        return $this->oldUsername;
    }

    /**
     * Set receive_newsletter
     *
     * @param boolean $receiveNewsletter
     * @return User
     */
    public function setReceiveNewsletter($receiveNewsletter)
    {
        $this->receiveNewsletter = $receiveNewsletter;

        return $this;
    }

    /**
     * Get receive_newsletter
     *
     * @return boolean 
     */
    public function getReceiveNewsletter()
    {
        return $this->receiveNewsletter;
    }

    /**
     * Set receive_avertissement
     *
     * @param boolean $receiveAvertissement
     * @return User
     */
    public function setReceiveAvertissement($receiveAvertissement)
    {
        $this->receiveAvertissement = $receiveAvertissement;

        return $this;
    }

    /**
     * Get receive_avertissement
     *
     * @return boolean 
     */
    public function getReceiveAvertissement()
    {
        return $this->receiveAvertissement;
    }

    /**
     * Set old_id
     *
     * @param integer $oldId
     * @return User
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


    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setSurname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
    }

    /**
     * Set ninja
     *
     * @param \NinjaTooken\GameBundle\Entity\Ninja $ninja
     * @return User
     */
    public function setNinja(\NinjaTooken\GameBundle\Entity\Ninja $ninja = null)
    {
        $this->ninja = $ninja;

        return $this;
    }

    /**
     * Get ninja
     *
     * @return \NinjaTooken\GameBundle\Entity\Ninja 
     */
    public function getNinja()
    {
        return $this->ninja;
    }

    /**
     * Set clan
     *
     * @param \NinjaTooken\ClanBundle\Entity\ClanUtilisateur $clan
     * @return User
     */
    public function setClan(\NinjaTooken\ClanBundle\Entity\ClanUtilisateur $clan = null)
    {
        $this->clan = $clan;

        return $this;
    }

    /**
     * Get clan
     *
     * @return \NinjaTooken\ClanBundle\Entity\ClanUtilisateur 
     */
    public function getClan()
    {
        return $this->clan;
    }

    /**
     * Add recruts
     *
     * @param \NinjaTooken\ClanBundle\Entity\ClanUtilisateur $recruts
     * @return User
     */
    public function addRecrut(\NinjaTooken\ClanBundle\Entity\ClanUtilisateur $recruts)
    {
        $this->recruts[] = $recruts;

        return $this;
    }

    /**
     * Remove recruts
     *
     * @param \NinjaTooken\ClanBundle\Entity\ClanUtilisateur $recruts
     */
    public function removeRecrut(\NinjaTooken\ClanBundle\Entity\ClanUtilisateur $recruts)
    {
        $this->recruts->removeElement($recruts);
    }

    /**
     * Get recruts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecruts()
    {
        return $this->recruts;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return User
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
}