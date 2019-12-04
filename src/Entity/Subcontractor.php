<?php

namespace App\Entity;

use App\Entity\Taxonomy\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_subcontractor")
 */
class Subcontractor implements ResourceInterface
{
    public const STATE_NEW = 'new';
    public const STATE_VALIDATED = 'validated';
    public const STATE_ARCHIVED = 'archived';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $state = self::STATE_NEW;

    /**
     * @var Collection|Taxon[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Taxonomy\Taxon")
     * @ORM\JoinTable(
     *     name="app_subcontractors_taxons"
     *     joinColumns={@ORM\JoinColumn(name="subcontractor_id", referencedColumnName="id")}
     *     inverseJoinColumns={@ORM\JoinColumn(name="taxon_id", referencedColumnName="id")}
     * )
     */
    private $taxons;

    public function __construct()
    {
        $this->taxons = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getTaxons(): Collection
    {
        return $this->taxons;
    }

    public function addTaxon(Taxon $taxon)
    {
        $this->taxons->add($taxon);
    }

    public function removeTaxon(Taxon $taxon)
    {
        $this->taxons->removeElement($taxon);
    }
}
