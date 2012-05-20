<?php
/** Contains picture adding form
 * @author Janis Peisenieks
 * @package Bildes
 * @subpackage Admin
 */
/**
 * A form for picture adding and editing
 * @author Janis Peisenieks
 * @package Bildes
 * @subpackage Admin
 */
class Admin_Form_Bildes_Pievienot extends JP_Form
{

    /**
     * Main initialization function
     * @param null $option Contains gallery list
     */
    public function __construct($option = null)
    {
        //Copying arrays, because of some problems with array sending to the parent
        $galleries = $option;
        $option = null;

        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        //Set the name of the form
        $this->setName("BildesPievienotForm");
        //Create and add all of the necessary fields to the form
        $this->addElement($this->createElement('text', 'pictureTitle')->setLabel("Bildes nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea', 'pictureDescription')->setLabel('Bildes apraksts'));
        $this->addElement($gallery = $this->createElement('select', 'galleryId')->setLabel('Bildes galerija'));
        $this->addElement($this->createElement('file', 'picture')->setLabel('Bilde'));

        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
        //Iterate throught the gallery list and add them to the select-box
        if (!is_null($galleries)) {
            foreach ($galleries as $gal) {
                $gallery->addMultiOption($gal['galleryId'], $gal['galleryTitle']);
            }
        }

    }
}