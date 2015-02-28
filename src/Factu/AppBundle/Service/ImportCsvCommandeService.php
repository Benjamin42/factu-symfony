<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace Factu\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Factu\AppBundle\Entity\Commande;
use Factu\AppBundle\Entity\CommandeProduct;
use Factu\AppBundle\Entity\CommandeService;

class ImportCsvCommandeService
{

  private $em;

  public function __construct(EntityManager $entityManager)
  {
      $this->em = $entityManager;
  }

  public function import($fileName)
  {
    $listProduct = $this->em
      ->getRepository('FactuAppBundle:Product')
      ->findAll();

    // Ligne pas ligne  
    $file = fopen($fileName, "r");
    while(!feof($file))
    {
      $row = fgetcsv($file, 0, ";");
      $this->createCommande($row, $listProduct);
    }

    // On flush tout d'un coup

    fclose($file);
    
    //var_dump($file);
  }

  private function createCommande($row, $listProduct) {
    $commande = new Commande();

    $client = $this->em
      ->getRepository('FactuAppBundle:Client')
      ->findOneByNumClient($row[3]);
/*
    $row[1]; // bon de livraison

    $row[21]; // nb etiquette
    $row[22]; // etiquette TTC
    $row[23]; // transport TTC
    $row[22]; // 
    $row[22]; // 
    $row[22]; // 
    $row[22]; // 
*/
    if ($client != null) {
      $commande->setClient($client);
      $commande->setNumFactu($row[0]);

      $dateFactu = \DateTime::createFromFormat('d/m/y', $row[4]);

      $commande->setDateFactu($dateFactu);

      // A livrer ?
      $commande->setToDelivered(True); // TODO

      $commande->setDateDelivered($dateFactu);
      $commande->setIsDelivered(True);

      // Payé ? (on ne prend pas en compte $row[2]. On considère que tout est payé)
      $commande->setDatePayed($dateFactu);
      $commande->setIsPayed(True);


      foreach ($listProduct as $product) {
        $idCol = $product->getIdColCsv();
        if ($idCol != null && ($row[$idCol] != "" || $row[$idCol + 1] != "")) {
          $commandeProduct = new CommandeProduct();
          $commandeProduct->setProduct($product);

          if ($row[$idCol] != "") {
            $commandeProduct->setQty($row[$idCol]);
          }
          if ($row[$idCol + 1] != "") {
            $commandeProduct->setQtyGift($row[$idCol + 1]);
          }

          $commandeProduct->setCommande($commande);
          $commande->addCommandeProduct($commandeProduct);
        }
      }


      $this->em->persist($commande);
          $this->em->flush();
    }


  }

}

