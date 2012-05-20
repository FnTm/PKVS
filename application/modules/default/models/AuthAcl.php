<?php
/** Contains ACL model class
 * @author Janis Peisenieks
 * @package Authorization
 */
/** ACL class, to provide role-based access
 * @author Janis Peisenieks
 * @package Authorization
 */
class Model_AuthAcl extends Zend_Acl
{
    /**
     * Sets up the role, resource and access hierarchy
     */
    public function __construct ()
    {
        //Add all of our roles, to create the access graph
        $this->addRole(new Zend_Acl_Role('guest')); // not authenicated
        $this->addRole(new Zend_Acl_Role('user'), 'guest');  // authenticated as member inherit guest privilages

        $this->addRole(new Zend_Acl_Role('editor'), 'user'); // authenticated as member inherit guest privilages

        $this->addRole(new Zend_Acl_Role('admin'), 'editor'); // authenticated as admin inherit member privilages

        // define Resources
        $this->addResource(new Zend_Acl_Resource('helpdesk'))->addResource(
        new Zend_Acl_Resource('helpdesk:index'), 'helpdesk');
        $this->addResource(new Zend_Acl_Resource('registry'))->addResource(
        new Zend_Acl_Resource('registry:mistake'), 'helpdesk');
        $this->addResource(new Zend_Acl_Resource('admin'))
            ->addResource(new Zend_Acl_Resource('admin:cms'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:index'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:error'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:idea'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:instr'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:links'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:pasakumi'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:galerijas'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:bildes'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:menu'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:pasakuma-tipi'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:apmekletiba'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:apmekletiba-tips'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:jaunumu-kategorijas'), 'admin')
            ->addResource(new Zend_Acl_Resource('admin:user'), 'admin');
        $this->addResource(new Zend_Acl_Resource('default'))
            ->addResource(new Zend_Acl_Resource('default:authentication'), 'default')
         ->addResource(new Zend_Acl_Resource('default:pasakumi'), 'default')
            ->addResource(new Zend_Acl_Resource('default:lietotajs'), 'default')
            ->addResource(new Zend_Acl_Resource('default:punkti'), 'default')
            ->addResource(new Zend_Acl_Resource('default:maksajumi'), 'default')
            ->addResource(new Zend_Acl_Resource('default:dalibnieki'), 'default')
            ->addResource(new Zend_Acl_Resource('default:galerijas'), 'default')
            ->addResource(new Zend_Acl_Resource('default:index'), 'default')
            ->addResource(new Zend_Acl_Resource('default:error'), 'default')
            ->addResource(new Zend_Acl_Resource('default:js'), 'default')
            ->addResource(new Zend_Acl_Resource('default:css'), 'default');
       //We denay all to guest, and only allow specific partsz
        $this->deny('guest');
        $this->allow('guest', 'default:index', 'index');
//        $this->allow('guest', 'default:pasakumi');
//        $this->allow('guest', 'default:js', 'index');
//        $this->allow('guest', 'default:css', 'index');
//        $this->allow('guest', 'default:index', 'visits');
        $this->allow('guest', 'default:authentication', array('login', 'logout'));
//        $this->allow('guest', 'default:error', 'error');
        $this->allow('user','default:punkti');
        $this->allow('user','default:maksajumi');
        $this->allow('user','default:lietotajs',array('profils'));
        $this->allow('user','default:pasakumi',array('skatit'));
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
