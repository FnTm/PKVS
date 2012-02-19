<?php
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';

class Sozfo_Tool_Doctrine_Context_FixturesDirectory extends Zend_Tool_Project_Context_Filesystem_Directory
{

    /**
     * @var string
     */
    protected $_filesystemName = 'fixtures';

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'FixturesDirectory';
    }
}
