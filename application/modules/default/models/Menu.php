<?php
/**
 * The Model file for the Menu controller
 * @author Janis
 * @package Menu
 * @subpackage Admin
 *
 */
/**
 * The Model class for the Menu controller
 * @author Janis
 * @package Menu
 * @subpackage Admin
 *
 */
class Baklans_Model_Menu extends Zend_Db_Table_Abstract
{
    protected $_name = 'hd_main_menu';
    public function createMenuItem ($title, $link, $parent)
    {
        $row = $this->createRow();
        // set the row data
        $row->title = $title;
        $row->link = $link;
        $row->parent = $parent;
        $row->order = $this->getLastOrder($parent);
        // save the new row
        $row->save();
        // now fetch the id of the row you just created and return it
        $id = $this->_db->lastInsertId();
        return $id;
    }
    public function getNews ($id)
    {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (! $row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }
    public function updateMenu ($id, $value, $field)
    {
        $data = array("" . $field . "" => $value);
        $this->update($data, 'id = ' . (int) $id);
        return $result = "yes";
    }
    public function editOrders ($id, $order)
    {
        $data = array('order' => $order);
        $this->update($data, 'id = ' . (int) $id);
        return $result = "yes";
    }
    public function deleteMenu ($id)
    {
        $this->delete('id =' . (int) $id);
        return "1";
    }
    public function getParents ($adm = "qwerty", $toArray = false)
    {
        $select = $this->select();
        $select->where("parent='0'");
        if ($adm == "admin") {
            $select->where("id!='0'");
        }
        $select->order('order asc');
        $return = $this->fetchAll($select);
        if ($toArray === true) {
            $return = $return->toArray();
        }
        return $return;
    }
    public function getChildren ($id)
    {
        $select = $this->select();
        $select->where("parent='" . $id . "'");
        if ($id == 0) {
            $select->where("parent!='" . $id . "'");
        }
        $select->order('order asc');
        return $this->fetchAll($select);
    }
    public function getChildrenCount ($id)
    {
        $select = $this->select();
        $select->where("parent='" . $id . "'");
        $select->order('order desc');
        $results = $this->fetchAll($select)->toArray();
        if (count($results) != 0 && $id != 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getChildrenActive ($id)
    {
        $select = $this->select();
        $select->where("parent='" . $id . "'");
        $select->where("id!='0'");
        $select->where("link='" . $this->curPageURL() . "'");
        $select->order('order desc');
        $results = $this->fetchAll($select)->toArray();
        if (count($results) != 0 || $id == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getIfActive ($link)
    {
        if ($link == $this->curPageURL()) {
            return true;
        } else {
            return false;
        }
    }
    public function getLastOrder ($parent)
    {
        $select = $this->select();
        $select->where("parent='" . $parent . "'");
        $select->limit(1);
        $select->order('order desc');
        $results = $this->fetchAll($select)->toArray();
        return (int) $results[0]['order'] + 1;
    }
    public function curPageURL ()
    {
        $pageURL = "http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] .
             $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    public function getSortables ()
    {
        $select = $this->select();
        $select->where("parent='0'");
        $select->where("id!='0'");
        $select->order('id asc');
        $results = $this->fetchAll($select)->toArray();
        $return = "";
        foreach ($results as $row) {
            $index = $row['id'];
            $return .= "$(\"#list_" . $index . "\").sortable({

	      handle : '.handle',

	      update : function () {

			  var order = $('#list_" . $index . "').sortable('serialize');

			   $(\"#saveList_" . $index . "\").click(function(event){


		  		$(\"#info\").load(\"/admin/menu/reorder/id/\"+order);

			   });

	      }

	    });



	$('#list #reorder_" . $index . "').click(function() {

		$(this).next().toggle('slow');

		return false;

	}).next().hide();
";
        }
        return $return;
    }
    function getMainMenu ()
    {
        $saturs = "";
        $saturs .= "<div id=\"main-handle\"><ul class=\"sf-menu sf-navbar\"><li class=\"home\"><a href=\"" .
         APPLICATION_DOMAIN . "/\"><img src=\"" . RESOURCE_PATH .
         "/home.gif\"></a></li>";
        $parentsa = $this->getParents("admin", true);
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $role = Zend_Registry::get('role');
            //var_dump($role);
            if (in_array($role, array('admin', 'dvd', 'mini-dvd','boss','viewer'))) {
                $array = array('link' => APP_DOMAIN . "/registry/mistake/all",
                'title' => 'Reģistrētās kļūdas');
                $array['id'] = 0;
                $parentsa[] = $array;
            }
            $array = array('link' => APP_DOMAIN . "/authentication/logout",
            'title' => 'Iziet no sistēmas');
            $array['id'] = 0;
            $parentsa[] = $array;
            if (in_array($role, array('admin', 'dvd', 'mini-dvd'))) {
                $array = array(
                'link' => APP_DOMAIN . "/admin/registry/mistakes",
                'title' => 'Administrācijas sistēma');
                $array['id'] = 0;
                $parentsa[] = $array;
            }
        } else {
            $array = array('link' => APP_DOMAIN . "/authentication/login",
            'title' => 'Ienākt sistēmā');
            $array['id'] = 0;
            $parentsa[] = $array;
        }
        //var_dump($parentsa);
        foreach ($parentsa as $parents) {
            if ($this->getIfActive($parents['link']) ||
             $this->getChildrenActive($parents['id'])) {
                $saturs .= "<li class=\"active\">";
            } else {
                $saturs .= "<li>";
            }
            if ($this->getChildrenCount($parents['id'])) {
                $saturs .= "<a class=\"main-link\" href=\"" . $parents['link'] .
                 "\">" . $parents['title'] . "</a>";
                $saturs .= "<ul class=\"sub-links\">";
            } else {
                $saturs .= "<a class=\"main-link\" href=\"" . $parents['link'] .
                 "\">" . $parents['title'] . "</a>";
            }
            foreach ($this->getChildren($parents['id']) as $children) {
                if ($this->getIfActive($children['link'])) {
                    $saturs .= "<li class=\"active\">";
                } else {
                    $saturs .= "<li>";
                }
                $saturs .= "<a href=\"" . $children['link'] . "\">" .
                 $children['title'] . "</a>";
                $saturs .= "</li>";
            }
            if ($this->getChildrenCount($parents['id'])) {
                $saturs .= "</ul>";
            }
            $saturs .= "</li>";
        }
        $saturs .= "</ul></div>";
        //return $saturs;
        $data = $saturs;
        return $data;
         //echo 'This is never cached ('.time().').';
    //print_r($albums->getParents());
    //echo $this->curPageURL ();
    }
    function getEditMainMenu ()
    {
        $saturs = "";
        $saturs .= "<ul class=\"\" id=\"list\">";
        foreach ($this->getParents("admin") as $parents) {
            $saturs .= "<li id=\"listItem_" . $parents['id'] . "\"><img src=\"" .
             RESOURCE_PATH .
             "/arrow.png\" alt=\"move\" width=\"16\" height=\"16\" class=\"handle\" />";
            $saturs .= "<span class=\"edit\" id=\"title_" . $parents['id'] .
             "\">" . $parents['title'] .
             "</span></strong>(<span class=\"edit\" id=\"link_" . $parents['id'] .
             "\">" . $parents['link'] . "</span>) ";
            if ($this->getChildrenCount($parents['id'])) {
                $saturs .= "<span id=\"reorder_" . $parents['id'] .
                 "\" class=\"reorder\"><img src=\"" . RESOURCE_PATH .
                 "/icons/asterisk_orange.png\" alt=\"move\" width=\"16\" height=\"16\"  /></span><div><ul id=\"list_" .
                 $parents['id'] . "\">";
            } else {
                $saturs .= "<A href=\"/admin/menu/delete/id/" . $parents['id'] .
                 "\" onClick=\"return confirmation()\"><img src=\"" .
                 RESOURCE_PATH .
                 "/icons/delete.png\" alt=\"move\" width=\"16\" height=\"16\"  /></a>";
            }
            foreach ($this->getChildren($parents['id']) as $children) {
                $saturs .= "<li id=\"listItem_" . $children['id'] .
                 "\"><img src=\"" . RESOURCE_PATH .
                 "/arrow.png\" alt=\"move\" width=\"16\" height=\"16\" class=\"handle\" />";
                $saturs .= "<span class=\"edit\" id=\"title_" . $children['id'] .
                 "\">" . $children['title'] .
                 "</span></strong>(<span class=\"edit\" id=\"link_" .
                 $children['id'] . "\">" . $children['link'] .
                 "</span>) <A href=\"/admin/menu/delete/id/" . $children['id'] .
                 "\" onClick=\"return confirmation()\"><img src=\"" .
                 RESOURCE_PATH .
                 "/icons/delete.png\" alt=\"move\" width=\"16\" height=\"16\"  /></a>";
                $saturs .= "</li>";
            }
            if ($this->getChildrenCount($parents['id'])) {
                $saturs .= "</ul><input type=\"button\" id=\"saveList_" .
                 $parents['id'] . "\" value=\"Saglabāt secību!\"></div>";
            }
            $saturs .= "</li>";
        }
        $saturs .= "</ul><input type=\"button\" id=\"saveList\" value=\"Saglabāt secību!\">";
        //return $saturs;
        $data = $saturs;
        return $data;
         //echo 'This is never cached ('.time().').';
    //print_r($albums->getParents());
    //echo $this->curPageURL ();
    }
}
?>