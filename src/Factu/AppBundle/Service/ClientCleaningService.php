<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace Factu\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Factu\AppBundle\Entity\Client;
use Factu\AppBundle\Form\ClientType;

class ClientCleaningService
{

  private $em;

  public function __construct(EntityManager $entityManager)
  {
      $this->em = $entityManager;
  }

  public function cleanClient($client)
  {
    $this->cleanCivilite($client);

    $this->cleanRue($client);

    $this->cleanVille($client);

    $this->cleanTel($client);

    $this->cleanPortable($client);

    $this->cleanFax($client);

    $this->cleanEmail($client);

    $this->cleanPays($client);

    /*$france = $this->em
      ->getRepository('FactuAppBundle:Country')
      ->findOneByCode('FR');*/


    return $client;
  }

  private function cleanCivilite($client) {
    if ($client->getCivilite() == null) {

      $codeToFind = "";
      if (preg_match("/^M (.*)$/", $client->getNom(), $matches)) {
        $codeToFind = "Mr";
        $client->setNom($matches[1]);
      } else if (preg_match("/^MME (.*)$/", $client->getNom(), $matches)) {
        $codeToFind = "Mme";
        $client->setNom($matches[1]);
      } else if (preg_match("/^MLLE (.*)$/", $client->getNom(), $matches)) {
        $codeToFind = "Mlle";
        $client->setNom($matches[1]);
      }

      if ($codeToFind !== "") {
        $civilite = $this->em
          ->getRepository('FactuAppBundle:Type')
          ->findWithCodeAndGrp($codeToFind, "civilite");
          if ($civilite != null) {
            $client->setCivilite($civilite);
          }
      }
    }
  }

  private function cleanRue($client) {
    if ($client->getRue() != "") {
      $client->setRue(ucwords(strtolower($client->getRue())));
    }
  }

  private function cleanVille($client) {
    $strVille = $client->getVille();
    if ($strVille != null) {
      preg_match("/^([0-9]*)\ *(.*)$/", $strVille, $matches);
      if ($matches != null && $matches[1] != null) {
        $client->setCodePostal($matches[1]);
      }
      if ($matches != null && $matches[2] != null) {
        $client->setVille(strtoupper($matches[2]));
      }

      // client.codepostal = CleaningVille.findCodePostal(client.ville, client.codepostal) 
    }
  }

  private function cleanEmail($client) {
    $str = $client->getEmail();
    if ($str != null) {
      $str = str_replace(" ", "", $str);
      $str = str_replace(",", ".", $str);
      $client->setEmail($str);
    }
  }

  private function cleanTel($client) {
    $client->setTel($this->cleanPhoneNumber($client->getTel()));
  }

  private function cleanPortable($client) {
    $client->setPortable($this->cleanPhoneNumber($client->getPortable()));
  }

  private function cleanFax($client) {
    $client->setFax($this->cleanPhoneNumber($client->getFax()));
  }

  private function cleanPhoneNumber($str) {
    if ($str != null) {
      $str = str_replace(" ", "", $str);
      if ($str != "") {
        while (strlen($str) < 10) {
          $str = "0" . $str;
        }
        $str = preg_replace("/(\d{2})/", "$1 ", $str);
      }
    }
    return $str;
  }

  private function cleanPays($client) {
    if ($client->getPays() == null || $client->getPays() == "") {
      $defaultCountry = $this->em
        ->getRepository('FactuAppBundle:Country')
        ->getDefaultCountry();
      $client->setPays($defaultCountry);
    }
  }

}