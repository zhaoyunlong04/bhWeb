<?php
/**
 * CLinkPager class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace frontend\components;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\LinkPager;

/**
 * CLinkPager displays a list of hyperlinks that lead to different pages of target.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.widgets.pagers
 * @since 1.0
 */
class CLinkPagerExt extends LinkPager
{
    const CSS_FIRST_PAGE = 'jumpBtn';
    const CSS_LAST_PAGE = 'jumpBtn';
    const CSS_PREVIOUS_PAGE = 'jumpBtn prev';
    const CSS_NEXT_PAGE = 'jumpBtn next';
    const CSS_INTERNAL_PAGE = '';
    const CSS_HIDDEN_PAGE = '';
    const CSS_SELECTED_PAGE = 'current';

    /**
     * @var string the CSS class for the first page button. Defaults to 'first'.
     * @since 1.1.11
     */
    public $firstPageCssClass = self::CSS_FIRST_PAGE;
    /**
     * @var string the CSS class for the last page button. Defaults to 'last'.
     * @since 1.1.11
     */
    public $lastPageCssClass = self::CSS_LAST_PAGE;
    /**
     * @var string the CSS class for the previous page button. Defaults to 'previous'.
     * @since 1.1.11
     */
    public $previousPageCssClass = self::CSS_PREVIOUS_PAGE;
    /**
     * @var string the CSS class for the next page button. Defaults to 'next'.
     * @since 1.1.11
     */
    public $nextPageCssClass = self::CSS_NEXT_PAGE;
    /**
     * @var string the CSS class for the internal page buttons. Defaults to 'page'.
     * @since 1.1.11
     */
    public $internalPageCssClass = self::CSS_INTERNAL_PAGE;
    /**
     * @var string the CSS class for the hidden page buttons. Defaults to 'hidden'.
     * @since 1.1.11
     */
    public $hiddenPageCssClass = self::CSS_HIDDEN_PAGE;
    /**
     * @var string the CSS class for the selected page buttons. Defaults to 'selected'.
     * @since 1.1.11
     */
    public $selectedPageCssClass = self::CSS_SELECTED_PAGE;
    /**
     * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
     */
    public $maxButtonCount = 10;
    /**
     * @var string the text label for the next page button. Defaults to 'Next &gt;'.
     */
    public $nextPageLabel;
    /**
     * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
     */
    public $prevPageLabel;
    /**
     * @var string the text label for the first page button. Defaults to '&lt;&lt; First'.
     */
    public $firstPageLabel;
    /**
     * @var string the text label for the last page button. Defaults to 'Last &gt;&gt;'.
     */
    public $lastPageLabel;
    /**
     * @var string the text shown before page buttons. Defaults to 'Go to page: '.
     */
    public $header;
    /**
     * @var string the text shown after page buttons.
     */
    public $footer = '';
    /**
     * @var mixed the CSS file used for the widget. Defaults to null, meaning
     * using the default CSS file included together with the widget.
     * If false, no CSS file will be used. Otherwise, the specified CSS file
     * will be included when using this widget.
     */
    public $cssFile = false;
    /**
     * @var array HTML attributes for the pager container tag.
     */
    public $htmlOptions = array();

    /**
     * Initializes the pager by setting some default property values.
     */

    /**
     * @var  设置 变成异步;;
     */
    public $updateId;

    /**
     * 是替换还是添加
     * @var bool
     */
    public $isReplaceWith = false;

    /**
     *  保留数
     * @var int
     */
    public $adjacent = 1;
    public $preadjacent = 1;
    public $lastadjacent = 1;

    public $showjump = false;


    public function init()
    {
        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
        if ($this->nextPageLabel === null)
            $this->nextPageLabel = '下一页';
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = '上一页';
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = '首页';
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = '末页';
        if ($this->header === null)
            $this->header = '';

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'page';

    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons)) {
            return;
        }

        echo $this->header;
        if ($this->showjump == true) {
            $curPage = \Yii::$app->request->getQueryParam("page", 1);
            if ($this->updateId) {
               $pageCount= $this->pagination->pageCount;
                $js = "if(isNaN($(this).parent('form').find('input').val())){
                alert('必须是数字');
                 $(this).parent('form').find('input').val('".$curPage."');
                }
                else if($(this).parent('form').find('input').val()>".$pageCount."){
                        alert('最大页为".$pageCount."');
                        $(this).parent('form').find('input').val('".$pageCount."')
                }else{
                        $.ajax({'url':$(this).parent('form').attr('action'),'data':$(this).parent('form').serialize(), 'cache':false,'success':function(html){\$('" . $this->updateId . "')." . ($this->isReplaceWith ? "replaceWith" : "html")."(html)}});
                }
                return false;";
                $buttons[] = '共'.$pageCount.'页'.'<span><form method="get" action="' . $this->pagination->createUrl(0) . '">到第 <input name="page" class="jumpInp" onclick="return false;" value="' . $curPage . '"> 页 <button class="btn30 w60 bg_blue"   onclick="' . $js . '" >确定</button></form></span>';
            } else {
                $pageCount= $this->pagination->pageCount;
                $buttons[] = '共'.$pageCount.'页'.'<span><form method="get" action="' . $this->pagination->createUrl(0) . '">到第 <input name="page" class="jumpInp" value="' . $curPage . '"> 页 <button class="btn30 w60 bg_blue"  onclick="" >确定</button></form></span>';
            }
        }
        echo Html::tag('div', implode("\n", $buttons), $this->htmlOptions);

        echo $this->footer;
    }

    /**
     * Registers the needed client scripts (mainly CSS file).
     */
    public function registerClientScript()
    {
        if ($this->cssFile !== false)
            self::registerCssFile($this->cssFile);
    }

    /**
     * Registers the needed CSS file.
     * @param string $url the CSS URL. If null, a default CSS URL will be used.
     */
    public static function registerCssFile($url = null)
    {
        if ($url === null)
            $url = Html::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager') . '.css');
        Yii::$app->getClientScript()->registerCssFile($url);
    }

    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons()
    {
        $this->maxButtonCount = $this->maxButtonCount - $this->adjacent;


        if (($pageCount = $this->pagination->getPageCount()) <= 1)
            return array();
        $currentPage = $this->pagination->getPage();


        if ($currentPage > $this->adjacent + 2 && $currentPage <= $pageCount - $this->adjacent) {

            $this->maxButtonCount = $this->maxButtonCount - $this->adjacent;
        }


        list($beginPage, $endPage) = $this->getPageRange();

        //  currentPage is calculated in getPageRange()
        $buttons = array();


        // prev page
        if (($page = $currentPage - 1) < 0)
            $page = 0;
        $buttons[] = $this->createPageButton($this->prevPageLabel, $page, $this->previousPageCssClass, $currentPage <= 0, false);

        for ($ii = 0; $ii < $this->adjacent; ++$ii) {
            if ($ii < $beginPage)
                $buttons[] = $this->createPageButton($ii + 1, $ii, $this->internalPageCssClass, false, $ii == $currentPage);

        }

        if ($ii > 0 && $ii < $beginPage) {
            $buttons[] = Html::tag('span', '......', []);
        }


        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $i == $currentPage);
        }

        if ($i < $pageCount - $this->adjacent) {
            $buttons[] = Html::tag('span', '......', []);
        }


        for ($ii = $pageCount - $this->adjacent; $ii < $pageCount; ++$ii) {
            if ($ii > $endPage) {
                $buttons[] = $this->createPageButton($ii + 1, $ii, $this->internalPageCssClass, false, $ii == $currentPage);
            }
        }


        // next page
        if (($page = $currentPage + 1) >= $pageCount - 1)
            $page = $pageCount - 1;
        $buttons[] = $this->createPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);


        return $buttons;
    }

//    /**
//     * @return array the begin and end pages that need to be displayed.
//     */
//    protected function getPageRange()
//    {
//
//        $currentPage = $this->pagination->getPage();
//        $pageCount = $this->pagination->getPageCount();
//
//        $beginPage = max(0, $currentPage - (int)($this->maxButtonCount / 2));
//        if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
//            $endPage = $pageCount - 1;
//            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
//        }
//        return array($beginPage, $endPage);
//    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if ($hidden || $selected)
            $class .= ' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);

        if ($this->updateId) {
            return Html::a($label, $this->pagination->createUrl($page), ["encode" => false, "class" => $class, "onclick" => new JsExpression("$.ajax({'url':this.href,'cache':false,'success':function(html){\$('" . $this->updateId . "')." . ($this->isReplaceWith ? "replaceWith" : "html") . "(html)}});return false;")]);
        } else {
            return Html::a($label, $this->pagination->createUrl($page), ["class" => $class]);
        }

    }
}