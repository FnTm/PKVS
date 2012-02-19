<?php
class Model_LibraryAcl extends Zend_Acl
{
    public function __construct ()
    {
        $this->addRole(new Zend_Acl_Role('guest')); // not authenicated
        $this->addRole(new Zend_Acl_Role('user'), 'guest'); // not authenicated
        $this->addRole(new Zend_Acl_Role('viewer'), 'user'); // authenticated as member inherit guest privilages
        $this->addRole(new Zend_Acl_Role('boss'), 'viewer'); // authenticated as member inherit guest privilages

        $this->addRole(new Zend_Acl_Role('mini-dvd'), 'boss'); // authenticated as admin inherit member privilages
        $this->addRole(new Zend_Acl_Role('dvd'),
        array('mini-dvd', 'boss')); // authenticated as admin inherit member privilages
        $this->addRole(new Zend_Acl_Role('admin'), 'dvd'); // authenticated as admin inherit member privilages
        // define Resources

        $this->add(new Zend_Acl_Resource('helpdesk'))->add(
        new Zend_Acl_Resource('helpdesk:index'), 'helpdesk');
        $this->add(new Zend_Acl_Resource('registry'))->add(
        new Zend_Acl_Resource('registry:mistake'), 'helpdesk');
        $this->add(new Zend_Acl_Resource('admin'))
            ->add(new Zend_Acl_Resource('admin:cms'), 'admin')
            ->add(new Zend_Acl_Resource('admin:index'), 'admin')
            ->add(new Zend_Acl_Resource('admin:error'), 'admin')
            ->add(new Zend_Acl_Resource('admin:idea'), 'admin')
            ->add(new Zend_Acl_Resource('admin:instr'), 'admin')
            ->add(new Zend_Acl_Resource('admin:links'), 'admin')
            ->add(new Zend_Acl_Resource('admin:lux'), 'admin')
            ->add(new Zend_Acl_Resource('admin:menu'), 'admin')
            ->add(new Zend_Acl_Resource('admin:registry'), 'admin')
            ->add(new Zend_Acl_Resource('admin:users'), 'admin');
        $this->add(new Zend_Acl_Resource('default'))
            ->add(new Zend_Acl_Resource('default:authentication'), 'default')
            ->add(new Zend_Acl_Resource('default:index'), 'default')
            ->add(new Zend_Acl_Resource('default:error'), 'default')
            ->add(new Zend_Acl_Resource('default:js'), 'default')
            ->add(new Zend_Acl_Resource('default:css'), 'default');

        $this->allow('guest', 'default:index', 'index');
        $this->allow('guest', 'default:js', 'index');
        $this->allow('guest', 'default:css', 'index');
        $this->allow('guest', 'default:index', 'visits');
        $this->allow('guest', 'default:authentication', array('login', 'logout'));
        $this->allow('guest', 'default:error', 'error');
        $this->allow('user', 'registry:mistake', array('add', 'index','success'));
        $this->allow('viewer', 'registry:mistake', array('all','view','mistakes'));
        $this->allow('boss', 'registry:mistake', array('approve'));
        $this->allow('dvd', 'registry:mistake');
        $this->allow('viewer','admin:registry',array('search'));
        $this->allow('mini-dvd','admin:registry',array('mistakes','search','view','approve'));
        $this->allow('dvd','admin:registry');

        $this->allow('admin', 'registry');
        $this->allow('admin', 'admin');

        $this->allow('admin');
        /*
        $this->allow('user', 'default:index', 'index');
        $this->allow('user', 'default:authentication', 'logout');
        $this->allow('user', 'library:books', array('index', 'list'));
        $this->allow('user', 'helpdesk:books', array('index', 'list'));
        $this->allow('admin', 'admin:book',
        array('index', 'add', 'edit', 'delete'));
        */
    }
}
