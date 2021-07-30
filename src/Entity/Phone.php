<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;




/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     * @OA\Property(description="Identifiant du telephone")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "show"})
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Length(min="2", minMessage="Ce champ doit contenir un minimum de {{ max }}caractères", max="255",maxMessage="Ce champ doit contenir un maximum de {{ max }} caractères")
     * @OA\Property(description="Nom du téléphone")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list","show"})
     * @OA\Property(description="Couleur du téléphone")
     */
    private $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"show"})
     * @OA\Property(description="Mémoire du téléphone")
     */
    private $memory;

    /**
     * @ORM\Column(type="string", length=355)
     * @Groups({"show"})
     * @OA\Property(description="Informations sur le téléphone")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Range(min="1", minMessage="La somme minimum acceptée est {{ min }}euros", max="1500",maxMessage="La valeur maximum autorisée est {{ min }} euros")
     * @OA\Property(
     *      type = "number",
     *      format = "float",
     *      description =" Prix du téléphone"
     * )
     */
    private $price;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(?int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
