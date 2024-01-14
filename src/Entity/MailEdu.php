<?php

namespace App\Entity;

use App\Repository\MailEduRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MailEduRepository::class)]
class MailEdu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:'datetime_immutable')]
    private ?\DateTimeImmutable $dateEnvoi = null;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $objet = null;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?int $idEducateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEnvoi(): ?\DateTimeImmutable
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeImmutable $dateEnvoi): static
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getEducateur(): ?int
    {
        return $this->idEducateur;
    }

    public function setEducateur(int $idEducateur): static
    {
        $this->idEducateur = $idEducateur;

        return $this;
    }

    private array $educateurs = [];

    public function addEducateur(int $idEducateur): static
    {
        if (!in_array($idEducateur, $this->educateurs)) {
            $this->educateurs[] = $idEducateur;
        }

        return $this;
    }

    public function removeEducateurs(int $idEducateur): static
    {
        $key = array_search($idEducateur, $this->educateurs);
        if ($key !== false) {
            unset($this->educateurs[$key]);
        }

        return $this;
    }

    public function getEducateurs(): array
    {
        return $this->educateurs;
    }
}
