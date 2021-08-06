<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use App\Repository\CustomerRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @Hateoas\Relation(
 *    "self",
 *    href = @Hateoas\Route(
 *       "customer_detail",
 *       parameters = { "id" = "expr(object.getId())" },
 *       absolute =true
 *    ),
 *    attributes = {
 *       "actions": {
 *           "read": "GET",
 *           "delete": "DELETE",
 *       }
 *    },
 *    exclusion = @Hateoas\Exclusion(groups = {"show"})
 * )
 * @Hateoas\Relation(
 *     name="all",
 *     href = @Hateoas\Route(
 *        "customers_list",
 *        absolute = true
 *     ),
 *     attributes = {"actions": { "read": "GET" }},
 *     exclusion = @Hateoas\Exclusion(groups = {"list"})
 *     )
 * )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     * @OA\Property(description="Identifiant du client")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=false)
     * @Groups({"list","show"})
     * @OA\Property(description="Mail du client")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "show"})
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @OA\Property(description="Nom du client")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=true)
  
     
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
