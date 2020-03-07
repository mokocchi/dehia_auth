<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @ExclusionPolicy("all")
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    private $googleid;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Client", cascade={"persist", "remove"})
     */
    private $oauthClient;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->actividadesCreadas = new ArrayCollection();
        $this->tareas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
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

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getGoogleid(): ?string
    {
        return $this->googleid;
    }

    public function setGoogleid(string $googleid): self
    {
        $this->googleid = $googleid;

        return $this;
    }

    public function getRoles()
    {
        $roles = ['ROLE_USER'];
        foreach ($this->roles as $role) {
            $roles[] = $role->getName();
        }

        return $roles;
    }

    /**
     * @VirtualProperty(name="role") 
     * @Expose
     */
    public function getRole()
    {
        if(count($this->roles) > 0 ){
            return $this->roles[0]->getName();
        } else {
            return null;
        }
        
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    public function getOauthClient(): ?Client
    {
        return $this->oauthClient;
    }

    public function setOauthClient(?Client $oauthClient): self
    {
        $this->oauthClient = $oauthClient;

        return $this;
    }
}
