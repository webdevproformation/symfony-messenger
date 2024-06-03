<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture
{
    private Generator $faker ;
    private ObjectManager $manager ;
    private SluggerInterface $slugger ;

    public function __construct(
        SluggerInterface $slugger
    )
    {
        $this->slugger = $slugger ;
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager ;
        $this->faker = Factory::create();
        $this->generateArticle(10);
        $this->manager->flush();
    }

    private function generateArticle(int $nb):void{

        for($i = 0 ; $i < $nb ; $i++){
            $article = new Article();

            // destructuration en PHP
            [
                "dateObject" => $dateObject,
                "dateString" => $dateString
            ] = $this->generateRandomDateBetweenRange("01/01/2020" , "31/12/2020");

            $title = $this->faker->sentence();
            $slug = $this->slugger->slug(strtolower($title)) ."-".$dateString ;

            $article->setTitre($title)
                    ->setContent($this->faker->paragraph())
                    ->setSlug($slug)
                    ->setCreatedAt($dateObject)
                    ->setPublished(true);
                    $this->manager->persist($article);
        }
        $this->manager->flush();

    }
    /**
     * Générer un DateTimeImmutable aléatoire et une date en format textuel fr entre $dtDebut et $dtFin
     *
     * @param string $dtDebut string format d/m/Y
     * @param string $dtFin string format d/m/Y
     * @return array{dateObject : DateTimeImmutable , dateString : string} string format d/m/Y
     */
    private function generateRandomDateBetweenRange(string $dtDebut , string $dtFin): array{
        /* $dtDebutTimeStamp = DateTime::createFromFormat("d/m/Y", $dtDebut)->getTimestamp(); 
        $dtFinTimeStamp = DateTime::createFromFormat("d/m/Y", $dtFin)->getTimestamp(); 
        $randomTimeStamp = mt_rand($dtDebutTimeStamp , $dtFinTimeStamp);
        $dateTimeImmutable = (new DateTimeImmutable())->setTimestamp($randomTimeStamp); */
        $dateDebut = DateTime::createFromFormat("d/m/Y", $dtDebut);
        $dateFin = DateTime::createFromFormat("d/m/Y", $dtFin) ;
        if(!$dateDebut || !$dateFin){
            throw new HttpException(400 , "la date doit être au format d/m/Y");
        }
        $randomTimeStamp = mt_rand($dateDebut->getTimestamp() , $dateFin->getTimestamp());
        $dateTimeImmutable = (new DateTimeImmutable())->setTimestamp($randomTimeStamp);
        return [
            "dateObject" => $dateTimeImmutable,
            "dateString" => $dateTimeImmutable->format("Y-m-d")
        ];
    }
}
