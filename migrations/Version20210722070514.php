<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210722070514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie ADD poster_url VARCHAR(512) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_has_people DROP FOREIGN KEY fk_Movie_has_People_Movie1');
        $this->addSql('ALTER TABLE movie_has_people DROP FOREIGN KEY fk_Movie_has_People_People1');
        $this->addSql('ALTER TABLE movie_has_people CHANGE significance significance ENUM(\'principal\', \'secondaire\') DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_has_people ADD CONSTRAINT FK_EDC40D818F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_has_people ADD CONSTRAINT FK_EDC40D813147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_has_type DROP FOREIGN KEY fk_Movie_has_Type_Movie1');
        $this->addSql('ALTER TABLE movie_has_type DROP FOREIGN KEY fk_Movie_has_Type_Type1');
        $this->addSql('ALTER TABLE movie_has_type ADD CONSTRAINT FK_D7417FB8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_has_type ADD CONSTRAINT FK_D7417FBC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE people CHANGE date_of_birth date_of_birth DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE movie DROP poster_url');
        $this->addSql('ALTER TABLE movie_has_people DROP FOREIGN KEY FK_EDC40D818F93B6FC');
        $this->addSql('ALTER TABLE movie_has_people DROP FOREIGN KEY FK_EDC40D813147C936');
        $this->addSql('ALTER TABLE movie_has_people CHANGE significance significance VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`');
        $this->addSql('ALTER TABLE movie_has_people ADD CONSTRAINT fk_Movie_has_People_Movie1 FOREIGN KEY (Movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_has_people ADD CONSTRAINT fk_Movie_has_People_People1 FOREIGN KEY (People_id) REFERENCES people (id)');
        $this->addSql('ALTER TABLE movie_has_type DROP FOREIGN KEY FK_D7417FB8F93B6FC');
        $this->addSql('ALTER TABLE movie_has_type DROP FOREIGN KEY FK_D7417FBC54C8C93');
        $this->addSql('ALTER TABLE movie_has_type ADD CONSTRAINT fk_Movie_has_Type_Movie1 FOREIGN KEY (Movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_has_type ADD CONSTRAINT fk_Movie_has_Type_Type1 FOREIGN KEY (Type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE people CHANGE date_of_birth date_of_birth DATE NOT NULL');
    }
}
