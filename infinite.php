<?php
    $mysqli = new mysqli('localhost' , 'root' , 'password' , 'test');
    $mysqli->query('set names utf8');
    $res=$mysqli->query('select * from test');
    $rows=array();
    while($row = $res->fetch_assoc())
    {
        $rows[]=$row;
    }
    $res->free();
  
  echo "<pre>";
  // var_dump(getTree($rows));
   print_r(getTree4($rows));
   echo "/<pre>";
  
  /**
  * 版本1.0
  * 将标准二维数组换成树
  * @param  array  $list   待转换的数据集
  * @param  string  $pk 唯一标识字段
  * @param  string  $pid    父级标识字段
  * @param  string  $child  子集标识字段
  * return  array 
  */
  function getTree1($list, $pk='id', $pid='pid', $child='child', $root=-1)
  {
        $tree = array();
        $packData = array();
        
        //将数组转换为索引数组
        foreach($list as $item)
        {
            $packData[$item[$pk]]=$item;
        }
        foreach($packData as $key => $value)
        {
            if($value[$pid] == $root)
            {
                //根节点放入
                $tree[]=&$packData[$key];
            }
            else
            {
                //子节点放入
                $packData[$value[$pid]][$child][]=&$packData[$key];
            }
        }
       return $tree;
  }
  
  /**
  * 版本2.0
  * 将标准二维数组换成树与v1.0类似
  * @param  array  $list   待转换的数据集
  * @param  string  $pk 唯一标识字段
  * @param  string  $pid    父级标识字段
  * @param  string  $child  子集标识字段
  * return  array 
  */
   function getTree2($list, $pk='id', $pid='pid', $child='child', $root=-1)
   {
        // 创建Tree
        $tree = array();
        if(is_array($list)) 
        {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) 
            {
                $refer[$data[$pk]] =& $list[$key];
            }

            foreach ($list as $key => $data) 
            {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId)
                {
                    $tree[] =& $list[$key];
                }
                else
                {
                    if (isset($refer[$parentId])) 
                    {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
   }
   
   /**
  * 版本3.0
  * 将标准二维数组换成树，利用递归方式实现
  * @param  array  $list   待转换的数据集
  * @param  string  $pk 唯一标识字段
  * @param  string  $pid    父级标识字段
  * @param  string  $child  子集标识字段
  * return  array 
  */
   function getTree3($list, $pk='id', $pid='pid', $child='child', $root=-1)
   {
       $tree=array();
        foreach($list as $key=> $val){

            if($val[$pid]==$root){
                //获取当前$pid所有子类 
                    unset($list[$key]);
                    if(! empty($list)){
                        $child=getTree3($list,$pk,$pid,$child,$val[$pk]);
                        if(!empty($child)){
                            $val['_child']=$child;
                        }                   
                    }              
                    $tree[]=$val; 
            }
        }   
        return $tree;
   }
   
   
   /**
  * 版本4.0
  * 将标准二维数组换成数组，利用递归方式实现
  * @param  array  $list   待转换的数据集
  * @param  string  $pk 唯一标识字段
  * @param  string  $pid    父级标识字段
  * @param  string  $child  子集标识字段
  * return  array 
  */
  function getTree4($list, $pid=-1, $level=1)
  {
        static $newlist = array();
       foreach($list as $key => $value)
       {
            if($value['pid']==$pid)
            {
                $value['level'] = $level;
                $newlist[] = $value;
                unset($list[$key]);
                getTree4($list, $value['id'], $level+1);
            }
       }
       return $newlist;
  }
  