<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService 
{
    public function __construct(
        private ParameterBagInterface $params
    )
    {}

    public function square(UploadedFile $picture, ?string $folder = '', ?int $width = 250): string
    {
        // On donne un nouveau nom à l'image
        $files = md5(uniqid(rand(), true)) . '.webp';

        // On récupère les infos de l'image

        $pictureInfo = getimagesize($picture);
        if($pictureInfo === false){
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le type mime

        // switch($pictureInfo['mime']){
        //     case 'image/png':
        //         $pictureSource = imagecreatefrompng($picture);
        //         break;
        //     case 'image/jpeg':
        //         $pictureSource = imagecreatefromjpeg($picture);
        //         break;
        //      case 'image/webp':
        //              $pictureSource = imagecreatefromwebp($picture);
        //              break;
        //     default:
        //         throw new Exception('Format d\'image incorrect');
        // }

        // On recadre L'image

        $imagewidth = $pictureInfo['1'];
        $imageheight = $pictureInfo['0'];

        switch($imagewidth <=> $imageheight){
            case -1: //portrait
                $sqaresize = $imagewidth;
                $srcx = 0;
                $srcy = ($imageheight - $imagewidth) / 2;
                break;
            case 0: //carré
                $sqaresize = $imagewidth;
                $srcx = 0;
                $srcy = 0;
                break;
            case 1: //paysage
                $sqaresize = $imageheight;
                $srcx = ($imagewidth - $imageheight) / 2;
                $srcy = 0;
                break;
        }

        // On crée une nouvelle image vierge

        // $resizepicture = imagecreatetruecolor($width, $width);

        // On génère le contenu de l'image

        // imagecopyresampled($resizepicture, $pictureSource, 0, 0, $srcx, $srcy, $width, $width, $sqaresize,
        // $sqaresize);

        // On crée le chemin de stockage

        $path = $this->params->get('uploads_directory') . $folder;

        // On crée le dossier s'il n'existe pas     

        if(!file_exists($path . '/mini/')){
            mkdir($path . '/mini/', 0755, true);
        }

        // On stock l'image réduite

        // imagewebp($resizepicture, $path . '/mini/' . $width . 'x' . $width . '.' . $files);

        // On stock l'image originale

        $picture->move($path . '/', $files);

        return $files ;
    }
}