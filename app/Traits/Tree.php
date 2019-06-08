<?php

namespace App\Traits;


trait Tree
{

    /**
     * 取所有父级
     * @return mixed
     */
    public function getParents()
    {
        return $this->whereIn('id', explode('.', $this->code))->get();
    }

    /**
     * 取直接父级
     * @return mixed
     */
    public function getParent()
    {
        return $this->find($this->pid);
    }

    /**
     * 取所有子节点
     * @param bool $includeSelf
     * @return array
     */
    public function getDescendants($includeSelf = false)
    {
        if ($this->isLeaf()) {
            return [];
        }

        //是否包括自己
        $code = $includeSelf ? $this->code : $this->code . '.';

        return $this->where('code', 'like', $code . '%')->orderBy('code', 'ASC')->get();
    }

    /**
     * 获取直接子节点
     * @param bool $asArray
     * @return array
     */
    public function getDescendant()
    {
        if ($this->isLeaf()) {
            return [];
        }
        return $this->where('pid', $this->id)->orderBy('code', 'ASC')->get();
    }

    /**
     * 取所有子叶子节点
     * @return array
     */
    public function getSonLeafs()
    {
        if ($this->isLeaf()) {
            return [];
        }

        return $this->where(['is_leaf' => 1])
            ->where('code', 'like', $this->code . '.%')
            ->orderBy('code', 'ASC')
            ->get();
    }

    /**
     * 取同级节点
     * @param bool $self
     * @return mixed
     */
    public function getSibling($self = false)
    {
        return $self ?
            $this->where('pid', $this->pid)
                ->orderBy('sort', 'ASC')
                ->get() :
            $this->where('pid', $this->pid)
                ->where('id', '<>', $this->id)
                ->orderBy('sort', 'ASC')
                ->get();

    }


    /**
     * 判断是否叶子节点
     * @return mixed
     */
    public function isLeaf()
    {
        return $this->is_leaf;
    }

    /**
     * 判断是否是根
     * @return bool
     */
    public function isRoot()
    {
        return $this->pid == 0;
    }

    /**
     * 取层级
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * 是否有有子元素
     * @return bool
     */
    public function hasChild()
    {
        return (bool)$this->where(['pid' => $this->id])->first();
    }


    /**
     * 形成树
     * @param $records
     * @return array
     */
    static public function makeTree($records)
    {
        $tree = [];
        $records = array_index($records, 'id');
        foreach ($records as &$record) {
            if ($record['pid'] != 0 && isset($records[$record['pid']])) {
                $records[$record['pid']]['sons'][] = &$records[$record['id']];
            } else {
                $tree[] = &$records[$record['id']];
            }
        }
        unset($record);

        return $tree;
    }

    /**
     * @name 生成树
     */
    public function genTree()
    {
        return self::makeTree($this->getDescendants(1)->toArray());
    }


    /**
     * 供select 下拉选择
     * @param array $condition
     * @param int $id
     * @param string $html
     * @return array
     */
    public function selTree($condition = [], $id = 0, $html = '--')
    {
        if ($id) {
            $items = $this->find($id)->getDescendants(true);
        } else {
            $items = $this->where($condition)->orderBy('code', 'asc')->get();
        }

        $arr = [];
        foreach ($items as &$item) {
            $arr[$item->id] = str_repeat($html, $item->level - 1) . ' ' . $item->name;
        }
        unset($item);

        return $arr;
    }



//    public function beforeSave($insert)
//    {
//        if (!parent::beforeSave($insert)) {
//            return false;
//        }
//
//        if (!$this->pid) {
//            $this->pid = 0;
//            $this->level = 1;
//        }
//
//        return true;
//    }


//    public function afterSave($insert, $changedAttributes)
//    {
//        $retval = parent::afterSave($insert, $changedAttributes);
//
//        if ( $this->pid == 0) {
//            $this->code = $this->id;
//            $level = 1;
//        } else {
//            $parent = static::findOne($this->pid);
//            $parent->is_leaf = 0;
//
//            $this->code = $parent->code . '.' . $this->id;
//            $this->level = $parent->level + 1;
//            self::updateAll(['is_leaf'=>$parent->is_leaf], 'id=' . $this->pid);
//        }
//
//        self::updateAll(['code'=>$this->code, 'level'=>$this->level], 'id=' . $this->id);
//
//        return $retval;
//    }
//
//    /**
//     * @name 为树排序，前台表格树使用
//     */
//    public static function sortTree($condition=[], $order=null, $size='')
//    {
//        $sort = static::find()
//            ->filterWhere($condition)
//            ->orderBy('code asc' . ',' . $order)
//            ->asArray()
//            ->all();
//
//        foreach ($sort as $k => &$v) {
////            if (!isset($v['thumb']) || !is_null($v['thumb'])) break;
////            if (!$v['thumb']) {
////                $v['cover'] = '';
////                continue;
////            }
//
//            if (!isset($v['thumb'])) break;
//
//            $v['cover'] = Attachment::getById($v['thumb'], $size);
//        }unset($v);
//
//        return $sort;
//    }

}