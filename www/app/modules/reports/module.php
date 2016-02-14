<?php

class module_reports extends gs_base_module implements gs_module
{
    function __construct()
    {
    }

    function install()
    {
        foreach (array('reports', 'reports_images', 'reports_images_files') as $r) {
            $this->$r = new $r;
            $this->$r->install();
        }
    }

    function get_menu()
    {
        $ret = array();
        $item = array();
        $item[] = '<a href="/admin/reports/">Отчеты</a>';
        $item[] = '<a href="/admin/reports/reports">reports</a>';
        $ret[] = $item;
        return $ret;
    }

    static function get_handlers()
    {
        $data = array(
            'handler' => array(
                '/admin/form/reports_images' => array(
                    'gs_base_handler.post:{name:admin_form.html:classname:reports_images:form_class:g_forms_table:return:gs_record}',
                    'gs_base_handler.redirect',
                ),
                '/admin/form/reports' => array(
                    'gs_base_handler.post:{name:form_admin_reports.html:classname:reports:form_class:g_forms_table}',
                    'gs_base_handler.redirect:href:/admin/reports/reports',
                ),
                '/admin/inline_form/reports' => array(
                    'gs_base_handler.redirect_if:gl:save_cancel:return:true',
                    'gs_base_handler.post:{name:inline_form.html:classname:reports}',
                    'gs_base_handler.redirect_if:gl:save_continue:return:true',
                    'gs_base_handler.redirect_if:gl:save_return:return:true',
                ),
                'form/reports' => array(
                    'gs_base_handler.post:{name:form_reports_reports.html:classname:reports:form_class:g_forms_table}',
                    'gs_base_handler.redirect:href:/reports/my',
                ),
                '/admin/form/guide/reports' => array(
                    'gs_base_handler.post:{name:form_admin_reports.html:classname:reports:form_class:g_forms_table}',
                    'gs_base_handler.redirect:href:/admin/guide',
                ),
            ),
            'get' => array(
                '/admin/reports/reports' => array(
                    'gs_base_handler.show:name:adm_reports.html',
                ),
                '/admin/reports/reports/delete' => array(
                    'gs_base_handler.delete:{classname:reports}',
                    'gs_base_handler.redirect',
                ),
                '/admin/reports/reports/copy' => array(
                    'gs_base_handler.copy:{classname:reports}',
                    'gs_base_handler.redirect',
                ),
                'my/*' => array(
                    'gs_base_handler.show:name:user_report.html:gl:gspgid',
                ),
                'my' => array(
                    'gs_base_handler.show:name:user_reports.html',
                ),
                'reports' => array(
                    'rec_show' => 'gs_base_handler.rec_by_id:classname:reports',
                    'gs_base_handler.show:name:report_show.html:gl:gspgid',
                ),
                '' => array(
                    'gs_base_handler.show:name:reports.html',
                ),
            ),
        );
        return self::add_subdir($data, dirname(__file__));
    }

    static function gl($alias, $rec, $data)
    {
        $fname = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'gl.php';
        if (file_exists($fname)) {
            $x = include($fname);
            return $x;
        }
        return parent::gl($alias, $rec, $data);
    }

    /*
    static function gl($alias,$rec) {
        if(!is_object($rec)) {
            $obj=new tw_reports;
            $rec=$obj->get_by_id(intval($rec));
        }
        switch ($alias) {
            case '___show____':
                return sprintf('/reports/show/%s/%d.html',
                        date('Y/m',strtotime($rec->date)),
                        $rec->get_id());
            break;
        }
    }
    */
}

/*
class handler_reports extends gs_base_handler {
}
*/


class reports extends gs_recordset_short
{
    public $no_urlkey = true;
    public $orderby = "id";

    function __construct($init_opts = false)
    {
        parent::__construct(array(
            'title' => 'fString verbose_name="Заголовок"     required=true        ',
            'text' => 'fText verbose_name="Текст"  widget="plaintext"    required=false        ',
            'authorName' => 'fString verbose_name="Автор"     required=true        ',
            'Images' => 'lMany2One reports_images:Parent verbose_name="Фотографии"   widget="MultiPowUpload"  required=false    ',
            'User' => 'lOne2One tw_users    required=true  foreign_field_name=userID  ',
            'Tour' => 'lOne2One tw_tours    required=false  foreign_field_name=tourID  ',
            'Trip' => 'lOne2One tw_trip verbose_name="Маршрут"   widget="parent_list"  required=false  foreign_field_name=tripID index_field_name=tripID counter=false  ',
            'Guide' => 'lOne2One tw_guide    required=false    ',

        ), $init_opts);

        $this->structure['fkeys'] = array();
        $this->structure['triggers']['after_insert'][] = 'trigger_1';
        $this->structure['triggers']['before_delete'][] = 'trigger_2';
    }

    function trigger_1($rec, $type, $options = array())
    {
        $t = $rec->Trip->first();
        $t->_Reports_count += 1;
        $t->commit();
    }

    function trigger_2($rec, $type, $options = array())
    {
        $t = $rec->Trip->first();
        $t->_Reports_count -= 1;
        $t->commit();
    }


}


class reports_images extends tw_images
{
    public $no_urlkey = true;
    public $orderby = "id";

    function __construct($init_opts = false)
    {
        parent::__construct(array(
            'file_uid' => 'fString    options="64"  required=false  index=true      ',
            'group_key' => 'fString    options="32"  required=false  index=true      ',
            'Parent' => 'lOne2One reports    required=false  mode=link  ',
            'File' => 'lOne2One reports_images_files verbose_name="File"   widget="include_form"  required=false  hidden=false  ',
        ), $init_opts);

        $this->structure['fkeys'] = array(
            array('link' => 'reports.Images', 'on_delete' => 'CASCADE', 'on_update' => 'CASCADE'),
        );
    }
}


class reports_images_files extends tw_file_images
{
    public $no_urlkey = true;
    public $orderby = "id";

    function __construct($init_opts = false)
    {
        parent::__construct(array(
            'Parent' => 'lOne2One reports_images    required=false    ',
        ), $init_opts);

        $this->structure['fkeys'] = array(
            array('link' => 'reports_images.File', 'on_delete' => 'CASCADE', 'on_update' => 'CASCADE'),
        );


    }


    function config_previews()
    {
        parent::config_previews();
        $this->config = array_merge($this->config, array(
            'box800' => array('width' => 800, 'height' => 800, 'method' => 'use_box', 'bgcolor' => array(0, 0, 0)),
            'crop80' => array('width' => 80, 'height' => 80, 'method' => 'use_crop', 'bgcolor' => array(0, 0, 0)),
        ));
    }
}
