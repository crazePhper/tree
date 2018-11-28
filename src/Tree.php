<?php
// +----------------------------------------------------------------------
// | Tree 树形结构数据处理
// +----------------------------------------------------------------------
// | Copyright (c) 2019 http://www.shuipf.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 水平凡 <admin@abc3210.com>
// +----------------------------------------------------------------------

namespace shuipf\tree;

class Tree
{

    /**
     * 原始数据
     * [
     *      1 => ['id'=>'1','parentid'=>0,'name'=>'一级栏目一'],
     *      2 => ['id'=>'2','parentid'=>0,'name'=>'一级栏目二'],
     *      3 => ['id'=>'3','parentid'=>1,'name'=>'二级栏目一'],
     *      4 => ['id'=>'4','parentid'=>1,'name'=>'二级栏目二'],
     *      5 => ['id'=>'5','parentid'=>2,'name'=>'二级栏目三'],
     *      6 => ['id'=>'6','parentid'=>3,'name'=>'三级栏目一'],
     *      7 => ['id'=>'7','parentid'=>3,'name'=>'三级栏目二']
     * ]
     * @var array
     */
    protected $data = [];

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    protected $icon = ['│', '├', '└'];

    protected $nbsp = "&nbsp;";

    /**
     * 处理后数据
     * @var array
     */
    protected $ret = [];


    /**
     * 构造函数
     * Tree constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data($data);
    }

    /**
     * 设置原始数据
     * @param array $data
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
        $this->ret = [];
        return $this;
    }

    /**
     * 设置修饰符
     * @param array $icons
     * @return $this
     */
    public function icon($icons)
    {
        $this->icon = array_merge($this->icon, $icons);
        return $this;
    }

    /**
     *
     * @param string $nbsp
     * @return $this
     */
    public function nbsp($nbsp)
    {
        $this->nbsp = $nbsp;
        return $this;
    }

    /**
     * 得到树型结构（单条）
     * @param int $myid 指定层级ID，默认从0开始
     * @param string $adds
     * @return array
     */
    public function getTreeOne($myid = 0, $adds = '')
    {
        //初始循环次数
        $number = 1;
        //获取指定层级下的数据
        $child = $this->getChild($myid);
        if (empty($child)) {
            return $this->ret;
        }
        //获取总数量
        $total = count($child);
        //遍历数据
        foreach ($child as $id => $data) {
            $j = $k = '';
            //当前循环次数=总数时代表最后一个
            if ($number == $total) {
                $j .= $this->icon[2];
            } else {
                $j .= $this->icon[1];
                //不想等是附加标识符
                $k = $adds ? $this->icon[0] : '';
            }
            //修饰符
            $spacer = $adds ? ($adds . $j) : '';

            $data['spacer_name'] = $spacer . $data['name'];
            $this->ret[] = $data;

            $nbsp = $this->nbsp;
            $this->getTreeOne($id, $adds . $k . $nbsp);
            $number++;
        }
        return $this->ret;
    }

    /**
     * 得到树型结构数组
     * @param int $myid
     * @return array
     */
    public function getTreeArray($myid)
    {
        $ref = [];
        //获取指定层级下的数据
        $child = $this->getChild($myid);
        if (empty($child)) {
            return [];
        }
        foreach ($child as $id => $data) {
            $ref[$id] = $data;
            //继续下级
            $ref[$id]['child'] = $this->getTreeArray($id);
        }
        return $ref;
    }

    /**
     * 获取某个层级的数组列表
     * @param int $myid 层级
     * @return array
     */
    protected function getChild($myid = 0)
    {
        $ref = [];
        if (!is_array($this->data)) {
            return [];
        }
        foreach ($this->data as $id => $a) {
            if ($a['parentid'] == $myid) {
                $ref[$id] = $a;
            }
        }
        return $ref;
    }

}