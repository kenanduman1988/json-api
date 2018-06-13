<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180613191035 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
CREATE TABLE `products` (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `created_at` date NOT NULL,
  `sku` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';
        ");

        $this->addSql("
CREATE TABLE `prices` (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_id` bigint NOT NULL,
  `iso3` varchar(8) NOT NULL,
  `value` float NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_general_ci'        
        ");
        $this->addSql("
CREATE TABLE `exchange_rates` (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` date NOT NULL,
  `currency` varchar(8) NOT NULL,
  `value` float NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_general_ci'
        ");
        $this->addSql("
ALTER TABLE `prices`
  ADD FOREIGN KEY (`product_id`) 
  REFERENCES `products` (`id`) 
  ON DELETE NO ACTION 
  ON UPDATE NO ACTION
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
