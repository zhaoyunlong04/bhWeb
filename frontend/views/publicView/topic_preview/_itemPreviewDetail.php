<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 10:49
 */
use common\models\sanhai\ShTestquestion;
use common\models\TestQuestion;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\StringHelper;
use common\components\WebDataCache;

/* @var $this \yii\web\View */
/** @var common\models\search\Es_testQuestion $item */


?>
<?php
$showType = $item->getQuestionShowType();
$isMaster = $item->getQuestionChildCache();

?>
<div class="pd25">


    <div class="Q_title clearfix">
        <p><?php echo StringHelper::htmlPurifier($item->processContent()) ?></p>
        <?= TestQuestion::mediaSource($item->mediaId,$item->mediaType,$showType)?>
    </div>

    <?php if ($showType == ShTestquestion::QUESTION_DAN_XUAN_TI || $showType == ShTestquestion::QUESTION_DUO_XUAN_TI) { ?>
        <div class="Q_cont">
            <?php
            if ($item->jsonAnswer != '' && $item->jsonAnswer != null) {
                echo $item->homeworkQuestionContent();
            }
            ?>
        </div>
    <?php } ?>


    <?php if ($showType == ShTestquestion::QUESTION_PAN_DUAN_TI) { ?>
        <div class="Q_cont">
            <?php
                echo $item->getJudgeQuestionContent();
            ?>
        </div>
    <?php } ?>


    <?php if ($showType == ShTestquestion::QUESTION_KE_PAN_LIAN_XIAN_TI) { ?>
        <div class="Q_cont">
            <?php
                echo $item->getConnectionQuestionContent();
            ?>
        </div>
    <?php } ?>

    <?php if ($showType == ShTestquestion::QUESTION_KE_PAN_YING_YONG_TI ||$showType == ShTestquestion::QUESTION_BU_KE_PAN_YING_YONG_TI) {
            if (!empty($isMaster)) {
                echo $this->render('//publicView/topic_preview/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id]);
            }
        }
    ?>

    <?php if($showType != ShTestquestion::QUESTION_BU_KE_PAN_LANG_DU_TI && $showType != ShTestquestion::QUESTION_KE_PAN_KOU_YU_TI){?>
        <div class="sUI_pannel btnArea">
            <button type="button" class="bg_white icoBtn_open show_aswerBtn">查看答案解析 <i></i></button>
        </div>
        <div class="A_cont">
            <p><em>答案：</em><?php echo  $item->getNewAnswerContent(); ?></p>

            <p><em>解析：</em><?php if (empty($item->analytical)) {
                    echo '略';
                } else {
                    echo $item->analytical;
                } ?>
            </p>
        </div>
    <?php }?>
</div>


