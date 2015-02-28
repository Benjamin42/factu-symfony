<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace Factu\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Factu\AppBundle\Entity\Client;

class ImportCsvClientService
{

  private $em;

  public function __construct(EntityManager $entityManager)
  {
      $this->em = $entityManager;
  }

  public function import($fileName)
  {
    $france = $this->em
      ->getRepository('FactuAppBundle:Country')
      ->findOneByCode('FR');

    // Ligne pas ligne  
    $file = fopen($fileName, "r");
    while(!feof($file))
    {
      $row = fgetcsv($file, 0, ";");
      $this->createClient($row, $france);
    }

    // On flush tout d'un coup
    $this->em->flush();
    fclose($file);
    
    //var_dump($file);
  }

  private function createClient($row, $france) {
    $client = new Client();

    $client->setNumClient($row[0]);
    $client->setNumTva($row[1]);
    $client->setNom($row[2]);
    $client->setNomInfo($row[3]);
    $client->setBat($row[4]);
    $client->setRue($row[5]);
    $client->setBp($row[6]);
    //$client->setCodePostal($row[]);
    $client->setVille($row[7]);

    $pays = null;
    if ($row[8] !== "") {
      $pays = $this->em
        ->getRepository('FactuAppBundle:Country')
        ->findOneByName($row[8]);
    }
    $client->setPays(($pays == null ? $france : $pays));
    
    $client->setTel($row[9]);
    $client->setFax($row[10]);
    $client->setPortable($row[11]);
    $client->setEmail($row[12]);
    $client->setCommentaire($row[13]);

    $this->em->persist($client);


    /*
      :num_client => self.clean($row[0]),
      :num_tva => self.clean($row[1]),
      :nom => self.clean($row[2]),
      :nom_info => self.clean($row[3]),
      :bat => self.clean($row[4]),
      :num_voie => self.clean($row[5]),
      :bp => self.clean($row[6]),
      #:codepostal => self.clean(row[]),
      :ville => self.clean($row[7]),
      :pays => CleaningPay.findByNom(self.clean($row[8])),
      :tel => self.clean($row[9]),
      :portable => self.clean($row[11]),
      :fax => self.clean($row[10]),
      :email => self.clean($row[12]),
      :commentaire => self.clean($row[13])
    */

  }
}