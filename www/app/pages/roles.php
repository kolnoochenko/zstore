<?php

namespace App\Pages;

use \Zippy\Html\DataList\DataView;
use \App\Entity\User;
use \App\Entity\UserRole;
use \App\System;
use \App\Application as App;
use \Zippy\Html\Form\CheckBox;
use \Zippy\Html\Form\DropDownChoice;
use \Zippy\Html\Form\Form;
use \Zippy\Html\Form\Button;
use \Zippy\Html\Form\TextArea;
use \Zippy\Html\Form\TextInput;
use \Zippy\Html\Label;
use \Zippy\Html\Link\ClickLink;
use \Zippy\Html\Panel;
use \Zippy\Binding\PropertyBinding as Bind;

class Roles extends \App\Pages\Base
{

    public $role = null;

    public function __construct() {
        parent::__construct();

        if (System::getUser()->userlogin != 'admin') {
            $this->setError('onlyadminaccess');
            App::RedirectHome();
            return false;
        }


        $this->add(new Panel("listpan"));
        $this->listpan->add(new ClickLink('addnew', $this, "onAdd"));
        $this->listpan->add(new DataView("rolerow", new RoleDataSource(), $this, 'OnRow'))->Reload();


        $this->add(new Panel("editpanname"))->setVisible(false);
        $this->editpanname->add(new Form('editformname'))->onSubmit($this, 'savenameOnClick');
        $this->editpanname->editformname->add(new TextInput('editname'));
        $this->editpanname->editformname->add(new Button('cancelname'))->onClick($this, 'cancelOnClick');


        $this->add(new Panel("editpan"))->setVisible(false);
        $this->editpan->add(new Form('editform'))->onSubmit($this, 'saveaclOnClick');


        //виджеты
        $this->editpan->editform->add(new CheckBox('editwplanned'));
        $this->editpan->editform->add(new CheckBox('editwdebitors'));
        $this->editpan->editform->add(new CheckBox('editwnoliq'));
        $this->editpan->editform->add(new CheckBox('editwminqty'));
        $this->editpan->editform->add(new CheckBox('editwsdate'));
        $this->editpan->editform->add(new CheckBox('editwrdoc'));
        $this->editpan->editform->add(new CheckBox('editwopendoc'));
        $this->editpan->editform->add(new CheckBox('editwwaited'));
        $this->editpan->editform->add(new CheckBox('editwreserved'));
        //модули
        $this->editpan->editform->add(new CheckBox('editocstore'));
        $this->editpan->editform->add(new CheckBox('editshop'));
        $this->editpan->editform->add(new CheckBox('editnote'));
        $this->editpan->editform->add(new CheckBox('editissue'));


        $this->editpan->editform->add(new Button('cancel'))->onClick($this, 'cancelOnClick');

        $this->editpan->editform->add(new Panel('metaaccess'));
        $this->editpan->editform->metaaccess->add(new DataView('metarow', new \ZCL\DB\EntityDataSource("\\App\\Entity\\MetaData", "", "meta_type,description"), $this, 'metarowOnRow'));
        $this->editpan->editform->metaaccess->metarow->Reload();

        $this->add(new Panel("editpanmenu"))->setVisible(false);
        $this->editpanmenu->add(new Form('editformmenu'))->onSubmit($this, 'savemenuOnClick');

        $this->editpanmenu->editformmenu->add(new Button('cancelmenu'))->onClick($this, 'cancelOnClick');
        $this->editpanmenu->editformmenu->add(new DataView('mlist', new \ZCL\DB\EntityDataSource("\\App\\Entity\\MetaData", "disabled<>1  ", "meta_type,description"), $this, 'menurowOnRow'));


    }

    public function onAdd($sender) {


        $this->listpan->setVisible(false);
        $this->editpanname->setVisible(true);
        // Очищаем  форму
        $this->editpanname->editformname->clean();


        $this->role = new UserRole();
    }

    public function onEdit($sender) {
        $this->listpan->setVisible(false);
        $this->editpanname->setVisible(true);
        $this->role = $sender->getOwner()->getDataItem();
        $this->editpanname->editformname->editname->setText($this->role->rolename);

    }

    public function OnMenu($sender) {
        $this->listpan->setVisible(false);
        $this->editpanmenu->setVisible(true);
        $this->role = $sender->getOwner()->getDataItem();

        $w = "";

        if (strlen($this->role->aclview) > 0) {
            $w = " and meta_id in ({$this->role->aclview})";
        } else {
            $w = " and meta_id in (0)";
        }
        if ($this->role->rolename == 'admins') {
            $w = "";
        }
        $this->editpanmenu->editformmenu->mlist->getDataSource()->setWhere("disabled<>1  {$w}");
        $this->editpanmenu->editformmenu->mlist->Reload();

    }

    public function OnAcl($sender) {


        $this->listpan->setVisible(false);
        $this->editpan->setVisible(true);


        $this->role = $sender->getOwner()->getDataItem();


        $this->editpan->editform->metaaccess->metarow->Reload();


        if (strpos($this->role->widgets, 'wplanned') !== false) {
            $this->editpan->editform->editwplanned->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wdebitors') !== false) {
            $this->editpan->editform->editwdebitors->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wnoliq') !== false) {
            $this->editpan->editform->editwnoliq->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wminqty') !== false) {
            $this->editpan->editform->editwminqty->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wsdate') !== false) {
            $this->editpan->editform->editwsdate->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wrdoc') !== false) {
            $this->editpan->editform->editwrdoc->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wopendoc') !== false) {
            $this->editpan->editform->editwopendoc->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wwaited') !== false) {
            $this->editpan->editform->editwwaited->setChecked(true);
        }
        if (strpos($this->role->widgets, 'wreserved') !== false) {
            $this->editpan->editform->editwreserved->setChecked(true);
        }

        if (strpos($this->role->modules, 'ocstore') !== false) {
            $this->editpan->editform->editocstore->setChecked(true);
        }
        if (strpos($this->role->modules, 'shop') !== false) {
            $this->editpan->editform->editshop->setChecked(true);
        }
        if (strpos($this->role->modules, 'note') !== false) {
            $this->editpan->editform->editnote->setChecked(true);
        }
        if (strpos($this->role->modules, 'issue') !== false) {
            $this->editpan->editform->editissue->setChecked(true);
        }
    }

    public function savenameOnClick($sender) {
        $this->role->rolename = $this->editpanname->editformname->editname->getText();

        $role = UserRole::getFirst('rolename=' . UserRole::qstr($this->role->rolename));
        if ($user instanceof UserRole) {
            if ($role->role_id != $this->role->role_id) {
                $this->setError('Неуникальное имя');
                return;
            }
        }

        $this->role->save();
        $this->listpan->rolerow->Reload();
        $this->listpan->setVisible(true);
        $this->editpanname->setVisible(false);

    }

    public function savemenuOnClick($sender) {
        $smartmenu = array();

        foreach ($sender->mlist->getDataRows() as $row) {
            $item = $row->getDataItem();
            if ($item->mview == true) {
                $smartmenu[] = $item->meta_id;
            }
        }
        $this->role->smartmenu = implode(',', $smartmenu);


        $this->role->save();
        $this->listpan->rolerow->Reload();
        $this->listpan->setVisible(true);
        $this->editpanmenu->setVisible(false);

    }


    public function saveaclOnClick($sender) {


        $varr = array();
        $earr = array();
        $xarr = array();
        $carr = array();

        foreach ($this->editpan->editform->metaaccess->metarow->getDataRows() as $row) {
            $item = $row->getDataItem();
            if ($item->viewacc == true) {
                $varr[] = $item->meta_id;
            }
            if ($item->editacc == true) {
                $earr[] = $item->meta_id;
            }
            if ($item->exeacc == true) {
                $xarr[] = $item->meta_id;
            }
            if ($item->cancelacc == true) {
                $carr[] = $item->meta_id;
            }
        }
        $this->role->aclview = implode(',', $varr);
        $this->role->acledit = implode(',', $earr);
        $this->role->aclexe = implode(',', $xarr);
        $this->role->aclcancel = implode(',', $carr);


        $widgets = "";

        if ($this->editpan->editform->editwplanned->isChecked()) {
            $widgets = $widgets . ',wplanned';
        }
        if ($this->editpan->editform->editwdebitors->isChecked()) {
            $widgets = $widgets . ',wdebitors';
        }
        if ($this->editpan->editform->editwnoliq->isChecked()) {
            $widgets = $widgets . ',wnoliq';
        }
        if ($this->editpan->editform->editwminqty->isChecked()) {
            $widgets = $widgets . ',wminqty';
        }
        if ($this->editpan->editform->editwsdate->isChecked()) {
            $widgets = $widgets . ',wsdate';
        }
        if ($this->editpan->editform->editwrdoc->isChecked()) {
            $widgets = $widgets . ',wrdoc';
        }
        if ($this->editpan->editform->editwopendoc->isChecked()) {
            $widgets = $widgets . ',wopendoc';
        }
        if ($this->editpan->editform->editwwaited->isChecked()) {
            $widgets = $widgets . ',wwaited';
        }
        if ($this->editpan->editform->editwreserved->isChecked()) {
            $widgets = $widgets . ',wreserved';
        }


        $this->role->widgets = trim($widgets, ',');

        $modules = "";
        if ($this->editpan->editform->editshop->isChecked()) {
            $modules = $modules . ',shop';
        }
        if ($this->editpan->editform->editnote->isChecked()) {
            $modules = $modules . ',note';
        }
        if ($this->editpan->editform->editocstore->isChecked()) {
            $modules = $modules . ',ocstore';
        }
        if ($this->editpan->editform->editissue->isChecked()) {
            $modules = $modules . ',issue';
        }

        $this->role->modules = trim($modules, ',');

        $this->role->save();
        $this->listpan->rolerow->Reload();
        $this->listpan->setVisible(true);
        $this->editpan->setVisible(false);

    }

    public function cancelOnClick($sender) {
        $this->listpan->setVisible(true);
        $this->editpan->setVisible(false);
        $this->editpanname->setVisible(false);
        $this->editpanmenu->setVisible(false);
    }


    //удаление  роли

    public function OnRemove($sender) {

        $role = $sender->getOwner()->getDataItem();

        $del = UserRole::delete($role->role_id);
        if (strlen($del) > 0) {
            $this->setError($del);
            return;
        }

        $this->listpan->rolerow->Reload();
    }

    public function OnRow($datarow) {
        $item = $datarow->getDataItem();
        $datarow->add(new \Zippy\Html\Label("rolename", $item->rolename));
        $datarow->add(new \Zippy\Html\Label("cnt", $item->cnt));

        $datarow->add(new \Zippy\Html\Link\ClickLink("smenu", $this, "OnMenu"));
        $datarow->add(new \Zippy\Html\Link\ClickLink("acl", $this, "OnAcl"))->setVisible($item->rolename != 'admins');
        $datarow->add(new \Zippy\Html\Link\ClickLink("edit", $this, "OnEdit"))->setVisible($item->rolename != 'admins');
        $datarow->add(new \Zippy\Html\Link\ClickLink("remove", $this, "OnRemove"))->setVisible($item->rolename != 'admins');
        if ($item->cnt > 0) {
            $datarow->remove->setVisible(false);
        }
        if ($item->cnt == 0) {
            $datarow->cnt->setVisible(false);
        }
    }


    public function metarowOnRow($row) {
        $item = $row->getDataItem();
        switch ($item->meta_type) {
            case 1:
                $title = "Документ";
                break;
            case 2:
                $title = "Отчет";
                break;
            case 3:
                $title = "Журнал";
                break;
            case 4:
                $title = "Справочник";
                break;
            case 5:
                $title = "Сервис";
                break;
        }
        $item->editacc = false;
        $item->viewacc = false;
        $item->exeacc = false;
        $item->cancelacc = false;
        $earr = @explode(',', $this->role->acledit);
        if (is_array($earr)) {
            $item->editacc = in_array($item->meta_id, $earr);
        }
        $varr = @explode(',', $this->role->aclview);
        if (is_array($varr)) {
            $item->viewacc = in_array($item->meta_id, $varr);
        }
        $xarr = @explode(',', $this->role->aclexe);
        if (is_array($xarr)) {
            $item->exeacc = in_array($item->meta_id, $xarr);
        }
        $carr = @explode(',', $this->role->aclcancel);
        if (is_array($carr)) {
            $item->cancelacc = in_array($item->meta_id, $carr);
        }

        $row->add(new Label('description', $item->description));
        $row->add(new Label('meta_name', $title));

        $row->add(new CheckBox('viewacc', new Bind($item, 'viewacc')));
        $row->add(new CheckBox('editacc', new Bind($item, 'editacc')))->setVisible($item->meta_type == 1 || $item->meta_type == 4);
        $row->add(new CheckBox('exeacc', new Bind($item, 'exeacc')))->setVisible($item->meta_type == 1);
        $row->add(new CheckBox('cancelacc', new Bind($item, 'cancelacc')))->setVisible($item->meta_type == 1);
    }


    public function menurowOnRow($row) {
        $item = $row->getDataItem();
        switch ($item->meta_type) {
            case 1:
                $title = "Документ";
                break;
            case 2:
                $title = "Отчет";
                break;
            case 3:
                $title = "Журнал";
                break;
            case 4:
                $title = "Справочник";
                break;
            case 5:
                $title = "Сервис ";
                break;
        }
        $smartmenu = @explode(',', $this->role->smartmenu);
        if (is_array($smartmenu)) {
            $item->mview = in_array($item->meta_id, $smartmenu);
        }


        $row->add(new Label('meta_desc', $item->description));
        $row->add(new Label('meta_name', $title));

        $row->add(new CheckBox('mshow', new Bind($item, 'mview')));
    }


}

class RoleDataSource implements \Zippy\Interfaces\DataSource
{

    //private $model, $db;

    public function getItemCount() {
        return UserRole::findCnt();
    }

    public function getItems($start, $count, $orderbyfield = null, $desc = true) {
        return UserRole::find('', $orderbyfield, $count, $start);
    }

    public function getItem($id) {
        return UserRole::load($id);
    }

}
