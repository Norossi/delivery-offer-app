<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216040159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delivery_offer (id SERIAL NOT NULL, offer_id VARCHAR(255) NOT NULL, customer_id VARCHAR(255) NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_email VARCHAR(255) NOT NULL, delivery_zipcode VARCHAR(255) NOT NULL, pickup_zipcode VARCHAR(255) NOT NULL, delivery_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) DEFAULT \'pending\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_39DD61AC53C674EE ON delivery_offer (offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE delivery_offer');
    }
}
