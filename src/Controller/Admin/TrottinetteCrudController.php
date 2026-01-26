<?php

namespace App\Controller\Admin;

use App\Entity\Trottinette;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Actions, Action};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    TextEditorField,
    BooleanField,
    ImageField,
    CollectionField,
    NumberField,
    AssociationField,
    IntegerField,
    DateTimeField,
    MoneyField
};

class TrottinetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trottinette::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Trottinette')
            ->setEntityLabelInPlural('Trottinettes')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            // ======================
            // IDENTITÉ
            // ======================

            CollectionField::new('illustrations', 'Images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            IdField::new('id')->hideOnForm(),

            ImageField::new('firstIllustration', 'Image')
                ->onlyOnIndex()
                ->setBasePath(''),

            TextField::new('name', 'Nom'),
            TextField::new('nameShort', 'Nom court')->hideOnIndex(),
            TextField::new('slug')->setFormTypeOption('disabled', true)->hideOnIndex(),
            TextField::new('nameShort')->hideOnIndex(),

            // ======================
            // CONTENU
            // ======================
            TextEditorField::new('description', 'Description'),
            /* TextEditorField::new('description', 'Description')->onlyOnForms(), */
            TextEditorField::new('descriptionShort', 'Courte desc.')/* ->onlyOnForms() */,

            // ======================
            // PRIX / STOCK
            // ======================
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setHelp('Entrez le prix en euros.'),

            AssociationField::new('tva', 'TVA')
                ->formatValue(function ($value, $entity) {
                    if (!$value) return '';
                    return $value->getName() . ' - ' . $value->getValue() . ' %';
                }),


            NumberField::new('weight', 'Poids (kg)')
                ->setHelp('Entrez le poids exact du produit')
                ->formatValue(function ($value, $entity) {
                    // On vérifie si c'est un entier
                    if (floor($value) == $value) {
                        return $value . 'kg';
                    }
                    // Sinon on affiche avec 2 décimales
                    return number_format($value, 2, ',', '') . 'kg';
                }),


            NumberField::new('stock', 'Stock'),
            BooleanField::new('isBest', 'Accueil'),

            // ======================
            // INDEX — SYNTHÈSE
            // ======================
            AssociationField::new('descriptionSections', 'Sections')
                ->onlyOnIndex()
                ->formatValue(fn ($v, $entity) =>
                    count($entity->getDescriptionSections()) . ' section(s)'
                ),

            AssociationField::new('trottinetteCaracteristiques', 'Caractéristiques')
                ->onlyOnIndex()
                ->formatValue(fn ($v, $entity) =>
                    count($entity->getTrottinetteCaracteristiques()) . ' caractéristiques'
                ),

            AssociationField::new('trottinetteAccessories', 'Accessoires')
                ->onlyOnIndex()
                ->formatValue(fn ($v, $entity) =>
                    count($entity->getTrottinetteAccessories()) . ' accessoires'
                ),

            // ======================
            // DETAIL — LISTES COMPLÈTES
            // ======================
            AssociationField::new('descriptionSections')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {
                    $html = '<ul>';
                    foreach ($entity->getDescriptionSections() as $section) {
                        $html .= '<li><strong>' . $section->getTitle() . '</strong></li>';
                    }
                    return $html . '</ul>';
                })
                ->renderAsHtml(),

            AssociationField::new('trottinetteCaracteristiques')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {
                    $html = '<ul>';
                    foreach ($entity->getTrottinetteCaracteristiques() as $tc) {
                        $label = $tc->getCaracteristique()?->getName() ?? '—';
                        $html .= '<li>' . $label . ' : ' . $tc->getValue() . '</li>';
                    }
                    return $html . '</ul>';
                })
                ->renderAsHtml(),


            AssociationField::new('trottinetteAccessories')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {
                    $html = '<ul>';
                    foreach ($entity->getTrottinetteAccessories() as $ta) {
                        $html .= '<li>' . $ta->getAccessory()?->getName() . '</li>';
                    }
                    return $html . '</ul>';
                })
                ->renderAsHtml(),

            // ======================
            // FORM — ÉDITION
            // ======================
            CollectionField::new('illustrations')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\IllustrationType::class)
                ->setFormTypeOption('by_reference', false),

            CollectionField::new('descriptionSections')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteDescriptionSectionType::class)
                ->setFormTypeOption('by_reference', false),

            CollectionField::new('trottinetteCaracteristiques')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteCaracteristiqueType::class)
                ->setFormTypeOption('by_reference', false),

            CollectionField::new('trottinetteAccessories')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteAccessoryType::class)
                ->setFormTypeOption('by_reference', false),

            // ======================
            // DATE (facultatif)
            // ======================
            /* DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(), */
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Trottinette) {
            parent::persistEntity($entityManager, $entityInstance);
            return;
        }

        $this->handleIllustrationsUpload($entityInstance);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Trottinette) {
            parent::updateEntity($entityManager, $entityInstance);
            return;
        }

        $this->handleIllustrationsUpload($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);
    }
    
    private function handleIllustrationsUpload(Trottinette $trottinette): void
    {
        $request = $this->getContext()->getRequest();

        if (!$request) {
            return;
        }

        $files = $request->files->all();

        // Nom du formulaire EasyAdmin (important)
        if (!isset($files['Trottinette']['illustrations'])) {
            return;
        }

        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadDir = $projectDir . '/public/uploads/trottinettes/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($trottinette->getIllustrations() as $index => $illustration) {

            if (
                !isset($files['Trottinette']['illustrations'][$index]['image']) ||
                !$files['Trottinette']['illustrations'][$index]['image'] instanceof UploadedFile
            ) {
                continue;
            }

            /** @var UploadedFile $file */
            $file = $files['Trottinette']['illustrations'][$index]['image'];

            // Génération d’un nom propre
            $filename = uniqid('trott_', true) . '.' . $file->guessExtension();

            // Déplacement physique du fichier
            $file->move($uploadDir, $filename);

            // 🔥 LE POINT CLÉ : on remplit AVANT le flush
            $illustration->setImage($filename);
            $illustration->setProduct($trottinette);
        }
    }

}
