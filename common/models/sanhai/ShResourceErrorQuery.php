<?php

namespace common\models\sanhai;

/**
 * This is the ActiveQuery class for [[ShResourceError]].
 *
 * @see SeSchoolGrade
 */
class ShResourceErrorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ShResourceError[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ShResourceError|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}