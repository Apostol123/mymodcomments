<?php
class mymodcomments extends Module
{

    public function __construct()
    {
        $this->name = "mymodcomments";
        $this->displayName = "My Module of product comments";

        $this->tab = "fron_office_features";
        $this->version = "0.1";
        $this->author = "Alex Apostol";
        $this->description = "With this module, your customers will be able
        to grade and comments your products";

        $this->bootstrap = true;
        parent::__construct();
    }


    //function that enables configuration to the module
    //in the back office
    // all the retrun items will be displayed in the configuration window
    public function getContent()
    {
        $this->processConfiguration();
        $this->assignConfiguration();
        return $this->display(__FILE__, "getContent.tpl");
    }

    public function processConfiguration()
    {
        if (Tools::isSubmit("mymod_pc_form")) {
            $enable_grades = Tools::getValue('enable_grades');
            $enable_comments = Tools::getValue('enable_comments');
            Configuration::updateValue('MYMOD_GRADES', $enable_grades);
            Configuration::updateValue('MYMOD_COMMENTS', $enable_comments);
            $this->context->smarty->assign("confirmation", "ok");
        }
    }

    public function assignConfiguration()
    {
        $enable_grades = Configuration::get('MYMOD_GRADES');
        $enable_comments = Configuration::get('MYMOD_COMMENTS');
        $this->context->smarty->assign('enable_grades', $enable_grades);
        $this->context->smarty->assign(
            'enable_comments',
            $enable_comments
        );
    }

    //Allows us to add the mod to a certain point(hook) in the font page
    // in this case in the display product tab
    public function install()
    {
        parent::install();
        $this->registerHook("displayProductTabContent");
        return true;
    }

    // dispalys the html into the hook
    public function hookDisplayProductTabContent($params)
    {
        $this->processProductTabContent();
        $this->assignProductTabContent();
        return $this->display(__FILE__, "displayProductTabContent.tpl");
    }

    // method that retirves the data from the form in the hook
    //and saves the data in the DB in our module table
    public function processProductTabContent()
    {
        if (Tools::isSubmit("mymod_pc_submit_comment")) {
            $id_product = Tools::getValue('id_product');
            $grade = Tools::getValue('grade');
            $comment = Tools::getValue('comment');

            $insert = array(
                "id_product" => (int)$id_product,
                "grade" => (int)$grade,
                "comment" => pSQL($comment),
                "date_add" => date("Y-m-d H:i:s")
            );

            Db::getInstance()->insert("mymod_comment", $insert);
        }
    }

    public function assignProductTabContent()
    {
        $enable_grades = Configuration::get('MYMOD_GRADES');
        $enable_comments = Configuration::get('MYMOD_COMMENTS');
        $id_product = Tools::getValue('id_product');
        $comments = Db::getInstance()->executeS('SELECT * FROM
        ' . _DB_PREFIX_ . 'mymod_comment WHERE id_product =
        ' . (int)$id_product);
        $this->context->smarty->assign('enable_grades', $enable_grades);
        $this->context->smarty->assign(
            'enable_comments',
            $enable_comments
        );
        $this->context->smarty->assign('comments', $comments);
    }
}
