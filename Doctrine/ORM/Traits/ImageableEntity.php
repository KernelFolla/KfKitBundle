<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ImageEntity
 *
 * need to copy commented code
 *
// * @Vich\UploaderBundle\Mapping\Annotation\Uploadable
 */
trait ImageableEntity {
//    /**
//     * @Symfony\Component\Validator\Constraints\File(
//     * maxSize="6M",
//     * mimeTypes={"image/gif", "image/png", "image/jpeg", "image/pjpeg"}
//     * )
//     * @Symfony\Component\Validator\Constraints\Image(
//     *     minWidth = 300,
//     *     minHeight = 300,
//     *     maxWidth = 300,
//     *     maxHeight = 300
//     * )
//     * @Vich\UploaderBundle\Mapping\Annotation\UploadableField(mapping="image", fileNameProperty="imagePath")
//     */
//    protected $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imagePath", type="string", length=255, nullable=true)
     */
    private $imagePath;

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Sets file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setImageFile($file)
    {
        $this->imageFile = $file;
    }

    /**
     * Get file.
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
} 
