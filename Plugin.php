<?php

/**
 * Poptrox,图片弹出灯箱插件
 *
 * @package Poptrox
 * @author 玖玖kyuu
 * @version 0.01
 * @link https://www.moyu.win
 * @date 2017.7.10
 */

class Poptrox_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 启用插件方法,如果启用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        //在博客页脚输出
        Typecho_Plugin::factory('Widget_Archive')->footer = array('Poptrox_Plugin', 'footer');
        return "插件已开启，请进行配置";
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        return "已移除";
    }

    /**
     * 获取插件配置面板
     *
     * @static
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        //是否使用插件jQuery，Poptrox必备
        $jQuerySwitch = new Typecho_Widget_Helper_Form_Element_Checkbox('jQuerySwitch', array('open' => '是否使用插件的jQuery'),'open','选项', '如果主题自带jQuery可关闭，但是必须保证jQuery在Poptrox之前加载并且只加载一个jQuery');
        $form->addInput($jQuerySwitch);
        //jQuery地址
        $jQueryUrl = new Typecho_Widget_Helper_Form_Element_Text('jQueryUrl', NULL, '//cdn.bootcss.com/jquery/2.1.1/jquery.min.js', _t('jQuery地址：'), '默认无需修改');
        $form->addInput($jQueryUrl);
        //Poptrox地址
        $poptroxUrl = new Typecho_Widget_Helper_Form_Element_Text('poptroxUrl', NULL, '//cdn.bootcss.com/jquery.poptrox/2.5.1/jquery.poptrox.min.js', _t('Poptrox地址：'), '默认无需修改');
        $form->addInput($poptroxUrl);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {

    }

    /*
     * 添加JS到博客页脚
     */
    public static function footer(){
        //取参数
        $jQuerySwitch = Helper::options()->plugin("Poptrox")->jQuerySwitch;
        $jQueryUrl = Helper::options()->plugin("Poptrox")->jQueryUrl;
        $poptroxUrl = Helper::options()->plugin("Poptrox")->poptroxUrl;
        //判断是否启动插件的jQuery
        if(strcmp("open",$jQuerySwitch[0])==0){
        echo <<<EOT
<script src="{$jQueryUrl}"></script>\n
EOT;
        }
        //输出Poptrox信息及设置
        echo <<<EOT
<script src="{$poptroxUrl}"></script>
<script>
//Poptrox设置信息
$(document).ready(function() {
	$(".post-content img").each(function() {
		var a = "<a href='" + this.src + "' class='image'></a>";
		$(this).wrapAll(a)
	})
}), $(function() {
	var a = $(".post-content");
	a.poptrox({
		usePopupCaption: !1,
		selector: "a.image",
		usePopupCloser: false,
		windowMargin: 0,
		overlayColor: "#FFF",
		overlayOpacity: 0.5,
		popupSpeed: 2
	})
});
</script>\n
EOT;
    }
}