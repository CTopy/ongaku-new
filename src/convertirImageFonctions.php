<?php
	/** 
	 * Redimensionner une image en une vignette de 85 pixels de coté
	 *
	 * @author Alain Corbiere <alain.corbiere@univ-lemans.fr>
	 * @param nomFichierAConvertir nom du fichier de l'image à convertir
	 * @param nomFichierConverti nom du fichier de l'image convertie
	 * @return nomFichierConverti nom du fichier de l'image convertie
	 *
	 */ 
	function convertirImage85x85 ($nomFichierAConvertir, $nomFichierConverti) {
		$imageSource = imageCreateFromJpeg($nomFichierAConvertir);
		$tailleImage = getImageSize($nomFichierAConvertir);
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
		imageJpeg($imageDestination, $nomFichierConverti) ;
		imageDestroy($imageDestination);
	}


	/** 
	 * Redimensionner une image avec une hauteur maximale ou une largeur maximale de 595 pixels 
	 *
	 * @author Alain Corbiere <alain.corbiere@univ-lemans.fr>
	 * @param nomFichierAConvertir nom du fichier de l'image à convertir
	 * @param nomFichierConverti nom du fichier de l'image convertie
	 * @return nomFichierConverti nom du fichier de l'image convertie
	 *
	 */
	function convertirImage595 ($nomFichierAConvertir, $nomFichierConverti) {
		$imageSource = imageCreateFromJpeg($nomFichierAConvertir);
		$tailleImage = getImageSize($nomFichierAConvertir);
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
		imageJpeg($imageReechantillonne, $nomFichierConverti) ;
		imageDestroy($imageReechantillonne);
	}
?>