<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429131230 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE archive (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, create_date DATETIME NOT NULL, modification_date DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_D5FC5D9CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tag_id INT NOT NULL, event_id INT DEFAULT NULL, create_date DATE NOT NULL, title LONGTEXT NOT NULL, content LONGTEXT NOT NULL, publication_date DATE NOT NULL, archived TINYINT(1) NOT NULL, diapofolder LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_23A0E66A76ED395 (user_id), INDEX IDX_23A0E66BAD26311 (tag_id), UNIQUE INDEX UNIQ_23A0E6671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, subject VARCHAR(120) NOT NULL, message LONGTEXT NOT NULL, message_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, program_id INT NOT NULL, title LONGTEXT NOT NULL, presentation LONGTEXT DEFAULT NULL, start_date DATE NOT NULL, place_number INT DEFAULT NULL, archived TINYINT(1) NOT NULL, INDEX IDX_3BAE0AA7BAD26311 (tag_id), INDEX IDX_3BAE0AA73EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_inscription (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, phone VARCHAR(20) NOT NULL, email VARCHAR(100) NOT NULL, mobility TINYINT(1) NOT NULL, validated TINYINT(1) NOT NULL, add_info LONGTEXT DEFAULT NULL, INDEX IDX_F519255671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_receiver (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_E0BA4AFCE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, program LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) DEFAULT NULL, address VARCHAR(255) NOT NULL, zip_code INT NOT NULL, city VARCHAR(100) NOT NULL, phone VARCHAR(15) NOT NULL, birth DATE NOT NULL, newsletter TINYINT(1) NOT NULL, accepted TINYINT(1) NOT NULL, uptodate DATE DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE archive ADD CONSTRAINT FK_D5FC5D9CA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA73EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('ALTER TABLE event_inscription ADD CONSTRAINT FK_F519255671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6671F7E88B');
        $this->addSql('ALTER TABLE event_inscription DROP FOREIGN KEY FK_F519255671F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA73EB8070A');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BAD26311');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7BAD26311');
        $this->addSql('ALTER TABLE archive DROP FOREIGN KEY FK_D5FC5D9CA76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('DROP TABLE archive');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_inscription');
        $this->addSql('DROP TABLE newsletter_receiver');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE fos_user');
    }
}
