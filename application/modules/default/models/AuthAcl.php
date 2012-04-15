<?php
class Model_AuthAcl extends Zend_Acl
{
    public function __construct ()
    {
        $this->addRole(new Zend_Acl_Role('guest')); // not authenicated
        $this->addRole(new Zend_Acl_Role('user'), 'guest'); // not authenicated
        $this->addRole(new Zend_Acl_Role('editor'), 'user'); // authenticated as member inherit guest privilages

        $this->addRole(new Zend_Acl_Role('admin'), 'editor'); // authenticated as admin inherit member privilages
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
            ->add(new Zend_Acl_Resource('admin:koncerti'), 'admin')
            ->add(new Zend_Acl_Resource('admin:menu'), 'admin')
            ->add(new Zend_Acl_Resource('admin:registry'), 'admin')
            ->add(new Zend_Acl_Resource('admin:user'), 'admin');
        $this->add(new Zend_Acl_Resource('default'))
            ->add(new Zend_Acl_Resource('default:authentication'), 'default')
         ->add(new Zend_Acl_Resource('default:pasakumi'), 'default')
        ->add(new Zend_Acl_Resource('default:dalibnieki'), 'default')
            ->add(new Zend_Acl_Resource('default:index'), 'default')
            ->add(new Zend_Acl_Resource('default:error'), 'default')
            ->add(new Zend_Acl_Resource('default:js'), 'default')
            ->add(new Zend_Acl_Resource('default:css'), 'default');
        $this->deny('guest');
        $this->allow('guest', 'default:index', 'index');
//        $this->allow('guest', 'default:pasakumi');
//        $this->allow('guest', 'default:js', 'index');
//        $this->allow('guest', 'default:css', 'index');
//        $this->allow('guest', 'default:index', 'visits');
        $this->allow('guest', 'default:authentication', array('login', 'logout'));
//        $this->allow('guest', 'default:error', 'error');
        $this->allow('editor', 'admin');

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
