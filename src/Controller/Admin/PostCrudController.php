<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
                        ->setDateFormat('long')
                        ->setTimeFormat('long')
                        ->setDateTimeFormat('long', 'long');
        ;
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('text'),
            DateTimeField::new('happenedDate'),
            TextField::new('tags')
        ];
    }

    public function new(AdminContext $context) {
        return $this->redirect($this->get('router')->generate('create'));
    }

}
