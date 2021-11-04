<?php

namespace App\Entity\Clan;

use App\Entity\Forum\Forum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Clan
 *
 * @ORM\Table(name="nt_clan")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\ClanRepository")
 */
class Clan implements SluggableInterface, \Serializable
{
    use SluggableTrait;

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
     * @ORM\Column(name="old_id", type="integer", nullable=true)
     */
    private $old_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clan\ClanUtilisateur", mappedBy="clan", cascade={"remove"})
     * @ORM\OrderBy({"dateAjout" = "ASC"})
     */
    private $membres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Forum\Forum", mappedBy="clan", cascade={"persist","remove"})
     */
    private $forums;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=5, nullable=true)
     * @Assert\Length(max=5)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="accroche", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $accroche;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     * @Assert\Url()
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="kamon", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $kamon;

    /**
     * @var string
     *
     * @ORM\Column(name="kamon_upload", type="string", length=255, nullable=true)
     */
    private $kamonUpload;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime")
     */
    private $dateAjout;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="online", type="boolean")
     */
    private $online = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_recruting", type="boolean")
     */
    private $isRecruting = true;

    /**
     * @Ignore()
     */

    private $tempKamon;

    /**
     * @Ignore()
     */
    public $file;


    /**
     * @Ignore()
     */
    public $delete = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->forums = new \Doctrine\Common\Collections\ArrayCollection();

        $this->setDateAjout(new \DateTime());
    }

    public function __toString(){
        return $this->nom;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->nom,
            $this->tag,
            $this->accroche,
            $this->description,
            $this->url,
            $this->id
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->nom,
            $this->tag,
            $this->accroche,
            $this->description,
            $this->url,
            $this->id
        ) = unserialize($serialized);
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['nom'];
    }

    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
    }

    public function generateSlugValue($values): string
    {
        $usableValues = [];
        foreach ($values as $fieldValue) {
            if (! empty($fieldValue)) {
                $usableValues[] = $fieldValue;
            }
        }

        $this->ensureAtLeastOneUsableValue($values, $usableValues);

        // generate the slug itself
        $sluggableText = implode(' ', $usableValues);

        $unicodeString = (new \Symfony\Component\String\Slugger\AsciiSlugger())->slug($sluggableText, $this->getSlugDelimiter());

        $slug = strtolower($unicodeString->toString());

        if (empty($slug)) {
            $slug = md5($this->id);
        }

        return $slug;
    }

    public function getAbsoluteKamon()
    {
        return null === $this->kamonUpload || "" === $this->kamonUpload ? null : $this->getUploadRootDir().'/'.$this->kamonUpload;
    }

    public function getWebKamon()
    {
        return null === $this->kamonUpload || "" === $this->kamonUpload  ? null : $this->getUploadDir().'/'.$this->kamonUpload;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../public/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'kamon';
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->dateAjout = new \DateTime();
        $this->updatedAt = new \DateTime();

        if (null !== $this->file) {
            $this->setKamonUpload(uniqid(mt_rand(), true).".".$this->file->guessExtension());
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();

        if (null !== $this->file) {
            $file = $this->id.'.'.$this->file->guessExtension();

            $fileAbsolute = $this->getUploadRootDir().$file;
            if(file_exists($fileAbsolute)) {
                unlink($fileAbsolute);
            }

            $this->setKamonUpload($file);
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->getKamonUpload());

        unset($this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->tempKamon = $this->getAbsoluteKamon();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($this->tempKamon && file_exists($this->tempKamon)) {
            unlink($this->tempKamon);
        }
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
     * Set accroche
     *
     * @param string $accroche
     * @return Clan
     */
    public function setAccroche($accroche)
    {
        $this->accroche = $accroche;

        return $this;
    }

    /**
     * Get accroche
     *
     * @return string 
     */
    public function getAccroche()
    {
        return $this->accroche;
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
     * Add membres
     *
     * @param \App\Entity\Clan\ClanUtilisateur $membre
     * @return Clan
     */
    public function addMembre(\App\Entity\Clan\ClanUtilisateur $membre)
    {
        $this->membres[] = $membre;
        $membre->setClan($this);

        return $this;
    }

    /**
     * Remove membres
     *
     * @param \App\Entity\Clan\ClanUtilisateur $membre
     */
    public function removeMembre(\App\Entity\Clan\ClanUtilisateur $membre)
    {
        $this->membres->removeElement($membre);
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

    /**
     * Add forums
     *
     * @param \App\Entity\Forum\Forum $forums
     * @return Clan
     */
    public function addForum(\App\Entity\Forum\Forum $forums)
    {
        $this->forums[] = $forums;
        $forums->setClan($this);

        return $this;
    }

    /**
     * Remove forums
     *
     * @param \App\Entity\Forum\Forum $forums
     */
    public function removeForum(\App\Entity\Forum\Forum $forums)
    {
        $this->forums->removeElement($forums);
    }

    /**
     * Get forums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * Set kamonUpload
     *
     * @param string $kamonUpload
     * @return Clan
     */
    public function setKamonUpload($kamonUpload)
    {
        $this->kamonUpload = $kamonUpload;

        return $this;
    }

    /**
     * Get kamonUpload
     *
     * @return string 
     */
    public function getKamonUpload()
    {
        return $this->kamonUpload;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
