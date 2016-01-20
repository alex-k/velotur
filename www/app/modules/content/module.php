<?php
class module_content extends gs_base_module implements gs_module
{
    static function get_handlers()
    {
        $data = array(
            'get_post' => array(
                '' => 'handler_content.content',
            )
        );

        return self::add_subdir($data, dirname(__file__));
    }
}

class handler_content extends gs_base_handler
{
    function content($ret) {


        $tpl=gs_tpl::get_instance();
        $tpl->addTemplateDir(cfg('document_root').DIRECTORY_SEPARATOR.'content');
        $this->params['name']=$this->data['gspgid_v'];
        return $this->show($ret);
    }
}
