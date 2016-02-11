<?php
gs_dict::append(array(
    'LOAD_RECORDS' => 'картинки',
    'SUBMIT_FORM' => 'Сохранить',
));

class module_articles extends gs_base_module implements gs_module
{
    function __construct()
    {
    }

    function install()
    {
        foreach (array('tw_articles') as $r) {
            $this->$r = new $r;
            $this->$r->install();
        }
    }

    function get_menu()
    {
        return '<a href="/admin/articles/">Статьи</a>';
    }

    static function get_handlers()
    {
        $data = array(
            'get_post' => array(
                '' => 'gs_base_handler.show:{name:articles.html}',
                'show' => array(
                    'gs_base_handler.validate_gl:{name:show:return:true^e404}',
                    'gs_base_handler.show:{name:article_show.html}',
                    'end',
                    'e404' => 'gs_base_handler.show404:return:true',
                ),
                '/admin/articles' => 'gs_base_handler.show:{name:adm_articles.html:classname:tw_articles}',
                '/admin/articles/delete' => 'admin_handler.deleteform:{classname:tw_articles}',
                '/admin/articles/iframe_gallery' => 'gs_base_handler.many2one:{name:iframe_gallery.html}',
            ),
            'handler' => array(
                '' => 'gs_base_handler.show:{name:articles_show.html}',
                'list' => 'gs_base_handler.show:{name:articles_list.html}',
                'last' => 'gs_base_handler.show',
                'short_list' => 'gs_base_handler.show:{name:news_short_list.html}',
                '/admin/form/tw_articles' => array(
                    'gs_base_handler.post:{name:form.html:form_class:g_forms_table:classname:tw_articles:form_class:form_admin:return:gs_record}',
                    'gs_base_handler.redirect',
                ),
            ),
        );
        return self::add_subdir($data, dirname(__file__));
    }

    static function gl($alias, $rec, $data)
    {
        if (!is_object($rec)) {
            $obj = new tw_articles;
            var_dump($rec);
            $rec = $obj->get_by_id(intval($rec));
        }
        switch ($alias) {
            case 'show':
                return sprintf('/articles/show/%d.html', $rec->get_id());
                break;
        }
    }
}

class handler_articles extends gs_base_handler
{
}

class tw_articles extends gs_recordset_handler
{
    const superadmin = 1;

    function __construct($init_opts = false)
    {
        parent::__construct(array(
            'name' => "fString 'Название' keywords=1",
            'description' => "fText 'Содержание' widget=wysiwyg images_key=Images required=false keywords=1",
            'pid' => "lOne2One tw_articles",
            'Childs' => "lMany2One tw_articles:pid counter=false",
            'text_id' => "fString 'Идентификатор статьи' required=false",
            'Images' => "lMany2One tw_articles_images:Parent 'Картинки' widget=gallery  counter=false",
        ), $init_opts);

        $this->structure['triggers']['before_update'][] = 'keywords';
        $this->structure['triggers']['after_insert'][] = 'keywords';
        $this->structure['triggers']['before_delete'][] = 'keywords';

    }

    function keywords($rec, $type)
    {
        $url = module_articles::gl('show', $rec);
        if ($type == 'before_update' || $type == 'after_insert') {
            $keywords = metatags::get_keywords($rec);
            $description = str_replace("\n", " ", substr(strip_tags($rec->description), 0, 254));
            $title = strip_tags($rec->name);
            metatags::save($url, $title, $keywords, $description);
        } elseif ($type == 'before_delete') {
            metatags::delete($url);
        }
    }

}