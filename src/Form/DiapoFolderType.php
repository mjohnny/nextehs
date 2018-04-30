<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 07/01/2018
 * Time: 17:35
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class DiapoFolderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $folder = $this->getFolders();
        $builder
            ->add('diapofolder', ChoiceType::class, array(
                'choices'=>$folder,
                'label'=>'article.diapo.label',
                'required'=>false,
                'multiple'=>false,
                'placeholder'=>'article.diapo.placeholder'
            ))
        ;
    }

    private function getFolders() {
        $path = './thumbs/gallerie/';
        // recherche des sous dossiers
        $dirs = [];
        if ($dh = opendir($path)) {
            // boucler tant qu'un dossier est trouve
            while (($dir = readdir($dh)) !== false) {
                if ($dir != '.' && $dir != '..' && is_dir($path . $dir)) {
                    $dirs[$dir] = $dir;
                }
            }
            // on ferme la connection
            closedir($dh);
            return $dirs;
        }
    }
}