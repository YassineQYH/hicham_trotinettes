<?php

namespace App\Controller\Admin;

use App\Entity\HomeVideo;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeVideoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeVideo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre interne'),

            // Champ vidéo uploadée dans le formulaire
            Field::new('videoFile', 'Vidéo uploadée')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false, // pas lié directement à l'entité
                    'required' => false,
                ])
                ->onlyOnForms(), // affiché uniquement dans le formulaire

            TextField::new('videoFile', 'Nom du fichier vidéo')
                ->onlyOnIndex(), // pour voir le nom du fichier dans la liste

            UrlField::new('videoUrl', 'URL externe')
                ->setHelp('YouTube ou Vimeo (si aucune vidéo uploadée)'),

            TextField::new('headline', 'Titre affiché sur la vidéo'),
            TextareaField::new('subtitle', 'Sous-titre'),

            BooleanField::new('isActive', 'Active')
                ->setHelp('Une seule vidéo peut être active à la fois'),

            IntegerField::new('position', 'Position'),

            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }

    /**
     * Override pour gérer la sauvegarde d'une nouvelle entité
     */
    public function persistEntity(EntityManagerInterface $entityManager, $homeVideo): void
    {
        $this->handleVideoUpload($homeVideo, $entityManager);
        parent::persistEntity($entityManager, $homeVideo);
    }

    /**
     * Override pour gérer la mise à jour d'une entité existante
     */
    public function updateEntity(EntityManagerInterface $entityManager, $homeVideo): void
    {
        $this->handleVideoUpload($homeVideo, $entityManager);
        parent::updateEntity($entityManager, $homeVideo);
    }

    /**
     * Gestion du fichier uploadé et activation unique
     */
    private function handleVideoUpload(HomeVideo $homeVideo, EntityManagerInterface $entityManager): void
    {
        $request = $this->getContext()->getRequest();

        // --- Upload vidéo ---
        /** @var UploadedFile|null $videoFile */
        $videoFile = $request->files->get('HomeVideo')['videoFile'] ?? null;

        if ($videoFile instanceof UploadedFile) {
            $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = (new \Symfony\Component\String\Slugger\AsciiSlugger())->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $videoFile->guessExtension();

            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/home_videos';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $videoFile->move($uploadDir, $newFilename);

            $homeVideo->setVideoFile($newFilename);
        }

        // --- Activation unique, seulement si submit classique (non AJAX) ---
        if ($request->isMethod('POST')) {
            if ($homeVideo->getIsActive()) {
                $entityManager->createQuery(
                    'UPDATE App\Entity\HomeVideo hv SET hv.isActive = false WHERE hv.isActive = true AND hv.id != :id'
                )->setParameter('id', $homeVideo->getId())
                ->execute();
            }
        }

        $homeVideo->setUpdatedAt(new \DateTimeImmutable());
    }


}
