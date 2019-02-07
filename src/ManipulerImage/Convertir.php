<?php 
namespace ManipulerImage;

/**
 * Convertir vous permet de redimensionner une image
 *
 * @author Alain Corbiere <alain.corbiere@univ-lemans.fr>
 */
class Convertir {
	
	private $nomFichierAConvertir ;
	private $nomFichierConverti ;
	
	/**
	 * Constructeur de la classe
	 *
	 * @param string $nomFichierAConvertir - nom du fichier de l'image à convertir
	 * @param string $nomFichierAConvertir - nom du fichier de l'image convertie
	 */
	public function __construct($nomFichierAConvertir = null, $nomFichierConverti = null) {
		if ($nomFichierAConvertir !== null)
			$this->setNomFichierAConvertir($nomFichierAConvertir) ;
		if ($nomFichierConverti !== null)
			$this->nomFichierConverti = $nomFichierConverti ;
	}

    /**
     * Set nomFichierAConvertir
     *
     * @param string $nomFichierAConvertir
     */
    public function setNomFichierAConvertir($nomFichierAConvertir)
    {
		if(file_exists($nomFichierAConvertir))
		{
			$this->nomFichierAConvertir = $nomFichierAConvertir;
		} else {
			throw new \Exception("Image ".$nomFichierAConvertir." n'existe pas, essayer une autre image.");
		}
    }

    /**
     * Get nomFichierAConvertir
     *
     * @return string
     */
    public function getNomFichierAConvertir()
    {
        return $this->nomFichierAConvertir;
    }
	
    /**
     * Set nomFichierConverti
     *
     * @param string $nomFichierConverti
     */
    public function setNomFichierConverti($nomFichierConverti)
    {
		if (isset($nomFichierConverti)) {
			$this->nomFichierConverti = $nomFichierConverti;
		} else {
			if (null === $this->getNomFichierConverti()) {
				throw new \Exception("Nom du fichier contenant l'image convertie n'est pas précisé.");
			}
		}
    }

    /**
     * Get nomFichierConverti
     *
     * @return string
     */
    public function getNomFichierConverti()
    {
        return $this->nomFichierConverti;
    }
	
    /** 
	 * Redimensionner une image en une vignette de 85 pixels de coté
	 *
	 * @param nomFichierConverti nom du fichier de l'image convertie
	 * @return nomFichierConverti nom du fichier de l'image convertie
	 *
	 */ 
    public function convertirImage85x85($nomFichierConverti = null) {
		$this->setNomFichierConverti($nomFichierConverti) ;
		$imageSource = imageCreateFromJpeg($this->getNomFichierAConvertir());
		$tailleImage = getImageSize($this->getNomFichierAConvertir());
		$largeurImageSource = $tailleImage[0];
		$hauteurImageSource = $tailleImage[1];
		if ($hauteurImageSource > $largeurImageSource) {
			$largeurImageReechantillonne = 85;
			// Contraint le rééchantillonage à une largeur fixe et aintient le ratio de l'image
			$hauteurImageReechantillonne = round(($largeurImageReechantillonne / $largeurImageSource) * $hauteurImageSource);
			$positionX = 0 ;
			$positionY = round(($hauteurImageReechantillonne-$largeurImageReechantillonne)/2) ;
		} 
		else {
			$hauteurImageReechantillonne = 85;
			// Contraint le rééchantillonage à une largeur fixe et maintient le ratio de l'image
			$largeurImageReechantillonne = round(($hauteurImageReechantillonne / $hauteurImageSource) * $largeurImageSource);
			$positionX = round(($largeurImageReechantillonne-$hauteurImageReechantillonne)/2) ;
			$positionY = 0 ;
		}
		$imageReechantillonne = imageCreateTrueColor($largeurImageReechantillonne, $hauteurImageReechantillonne );
		/* ImageCopyResampled copie et rééchantillonne l'image originale*/
		imageCopyResampled($imageReechantillonne,$imageSource,0,0,0,0,
                       $largeurImageReechantillonne, $hauteurImageReechantillonne,
                       $largeurImageSource, $hauteurImageSource);
		$largeurImageDestination = 85 ;
		$hauteurImageDestination = 85 ;
		$imageDestination = imageCreateTrueColor($largeurImageDestination,$hauteurImageDestination);
		imageCopy ( $imageDestination, $imageReechantillonne, 0, 0,
                $positionX, $positionY, $largeurImageDestination,
                $hauteurImageDestination );
		imageDestroy($imageReechantillonne);
		imageJpeg($imageDestination, $this->getNomFichierConverti()) ;
		imageDestroy($imageDestination);
	}

	
	/** 
	 * Redimensionner une image avec une hauteur maximale ou une largeur maximale de 595 pixels 
	 *
	 * @param nomFichierConverti nom du fichier de l'image convertie
	 * @return nomFichierConverti nom du fichier de l'image convertie
	 *
	 */
	public function convertirImage595($nomFichierConverti = null) {
		$this->setNomFichierConverti($nomFichierConverti) ;
		$imageSource = imageCreateFromJpeg($this->getNomFichierAConvertir());
		$tailleImage = getImageSize($this->getNomFichierAConvertir());
		$largeurImageSource = $tailleImage[0];
		$hauteurImageSource = $tailleImage[1];
		if ($hauteurImageSource > $largeurImageSource) {
			$hauteurImageReechantillonne = 595;
			// Contraint le rééchantillonage à une largeur fixe
			// Maintient le ratio de l'image
			$largeurImageReechantillonne = round(($hauteurImageReechantillonne / $hauteurImageSource) * $largeurImageSource);
		}
		else {
			$largeurImageReechantillonne = 595;
			// Contraint le rééchantillonage à une largeur fixe et maintient le ratio de l'image
			$hauteurImageReechantillonne = round(($largeurImageReechantillonne / $largeurImageSource) * $hauteurImageSource);
		}
		$imageReechantillonne = imageCreateTrueColor($largeurImageReechantillonne,$hauteurImageReechantillonne);
		/* ImageCopyResampled copie et rééchchantillonne l'image originale*/
		imageCopyResampled($imageReechantillonne,$imageSource,0,0,0,0,
                       $largeurImageReechantillonne, $hauteurImageReechantillonne,
                       $largeurImageSource, $hauteurImageSource);
		imageJpeg($imageReechantillonne, $this->getNomFichierConverti()) ;
		imageDestroy($imageReechantillonne);
	}
}
?>