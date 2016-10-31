<?php

// src/AndroidDev/UserBundle/Entity/Contact.php

namespace AppBundle\Entity;

/**
 * Contact
 *
 */
class Contact
{

    /**
     * @var string
     */
    protected $nom;
    
    /**
     * @var string
     */
    protected $prenom;
    
    /**
     * @var string
     */
    protected $mail;
    
    /**
     * @var string
     */
    protected $objet;
    
    /**
     * @var string
     */
    protected $contenu;

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function getObjet()
    {
        return $this->objet;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function setObjet($objet)
    {
        $this->objet = $objet;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

}
