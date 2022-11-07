<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

use App\Entity\Specialite;

class SpecialiteFixtures
{
    public array $list_specialites = array();

    public function charger(ObjectManager $manager): void
    {
        $list = [
            ['I',    1, 'Droit privé et sciences criminelles'],
            ['I',    2, 'Droit public'],
            ['I',    3, 'Histoire du droit et des institutions'],
            ['I',    4, 'Science politique'],
            ['II',   5, 'Sciences économiques'],
            ['II',   6, 'Sciences de gestion'],
            ['III',  7, 'Sciences de language : linguistique et phonétique générales'],
            ['III',  8, 'Langue et littératures anciennes'],
            ['III',  9, 'Langue et littérature françaises'],
            ['III', 10, 'Littératures comparées'],
            ['III', 11, 'Langues et littératires anglaises et anglo-saxonnes'],
            ['III', 12, 'Langues et littératures germanique et scandinaves'],
            ['III', 13, 'Langues et littérature slaves'],
            ['III', 14, 'Langues et littératures romanes : espagnol, italien, portugais, autres langues romanes'],
            ['III', 15, 'Langues et littératures arabes, chinoises, japonaises, hébraïques, d\'autres domaines linguistiques'],
            ['IV',  16, 'Psychologie, psychologie clinique, psychologie sociale'],
            ['IV',  17, 'Philosophie'],
            ['IV',  18, 'Architecture (ses théories et ses pratiques), art appliqués, arts plastiques, arts du spectacle, épistémologie des enseignements artistiques, esthétique, musicologie, musique, science de l\'art'],
            ['IV',  19, 'Sociologie, démographie'],
            ['IV',  20, 'Anthropologie biologique, ethnologie, préhistoire'],
            ['IV',  21, 'Histoire, civilisation, archéologie et art des mondes anciens et médiévaux'],
            ['IV',  22, 'Histoire et civilisations : histoire des mondes modernes, histoire des mondes modernes, histoire du monde contemporain, de l\'art, de la musique'],
            ['IV',  23, 'Géographie physique, humaine, économique et régionale'],
            ['IV',  24, 'Aménagement de l\'espace, urbanisme'],
            ['V',   25, 'Mathématiques'],
            ['V',   26, 'Mathématiques appliqués et applications des mathématiques'],
            ['V',   27, 'Informatique'],
            ['VI',  28, 'Millieux denses et matériaux'],
            ['VI',  29, 'Constituants élémentaires'],
            ['VI',  30, 'Millieurx dilués et optique'],
            ['VII', 31, 'Chimie théorique, physique, analytique'],
            ['VII', 32, 'Chimie organique, minérale, industrielle'],
            ['VII', 33, 'Chimie des matériaux'],
            ['VIII',34, 'Astronomie, astrophysique'],
            ['VIII',35, 'Structure et évolution de la Terre et des autres planètes'],
            ['VIII',36, 'Terre solide : géodynamique des enveloppes supérieures, paléo-blosphère'],
            ['VIII',37, 'Méteorologie, océanographie physique et physique de l\'environnement'],
            ['IX',  60, 'Mécanique, génie mécanique, génie civil'],
            ['IX',  61, 'Génie informatique, automatique et traitement du signal'],
            ['IX',  62, 'Energétique, génie des procédés'],
            ['IX',  63, 'Génie électrique, électronique, photonique et systèmes'],
            ['X',   64, 'Biochimie et biologie moléculaire'],
            ['X',   65, 'Biologie cellulaire'],
            ['X',   66, 'Physiologie'],
            ['X',   67, 'Biologie des populations et écologie'],
            ['X',   68, 'Biologie des organismes'],
            ['X',   69, 'Neurosciences'],
            ['XII', 70, 'Sciences de l\'éducation'],
            ['XII', 71, 'Sciences de l\'information et de la communication'],
            ['XII', 72, 'Épistémologie, histoire des sciences et des techniques'],
            ['XII', 73, 'Cultures et langues régionales'],
            ['XII', 74, 'Sciences et technique des activités physiques et sportives'],
            ['théologie', 76, 'théologie catholique'],
            ['théologie', 77, 'théologie protestante'],
            ['pharmacie', 85, 'Sciences physico-chimiques et technologies pharmaceutiques'],
            ['pharmacie', 86, 'Sciences du médicament'],
            ['pharmacie', 87, 'Sciences biologiques pharmaceutiques']
        ];

        for($i = 0; $i < count($list); $i++)
        {
            $specialite = new Specialite();
            $specialite->setGroupe($list[$i][0]);
            $specialite->setSection($list[$i][1]);
            $specialite->setNom($list[$i][2]);
            $manager->persist($specialite);
            $this->list_specialites[] = $specialite;
        }


        $manager->flush();
    }
}
