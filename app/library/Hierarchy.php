<?php
/**
 * Created by PhpStorm.
 * User: Anik
 * Date: 12-Dec-14
 * Time: 12:23 AM
 */

class Hierarchy {
    //$parent_id_array
    public static function getTree($parent_node_array, $root){
        $sql = "parent_id in (".implode(",", $parent_node_array).")";
        $results = Relationship::whereRaw($sql)->get();
        $previous = null;
        if(count($results) > 0){
            $next_nodes = array();
            // collect the levels
            foreach($results as $result){
                if($result['left_hand'] != null)
                    $next_nodes[] = $result['left_hand'];
                if($result['right_hand'] != null)
                    $next_nodes[] = $result['right_hand'];

                if($result['left_hand'] == null){
                    return array('node' => $result['parent_id'], 'position' => 'left_hand');
                } elseif($result['right_hand'] == null){
                    if($root == true){
                        // root node
                        return array('node' => $result['parent_id'], 'position' => 'right_hand');
                    } else{
                        //return static::getTree($next_nodes, false);
                        foreach(array_slice($results->toArray(), 1) as $a){
                            if($a['left_hand'] == null){
                                return array('node' => $a['parent_id'], 'position' => 'left_hand');
                            }
                        }
                        return array('node' => $result['parent_id'], 'position' => 'right_hand');
                    }
                }
            }
            return static::getTree($next_nodes, false);
        }
    }
    public static function genealogy($parent_array, $level, &$tree){
        if($level == 1) $tree[] = $parent_array[0];
        if($level == 4) return $tree;
        $returnNow = true;
        foreach($parent_array as $parent){
            if($parent != null){
                $returnNow = false;
                break;
            }
        }
        if($returnNow)
            return $tree;

        $sql = " parent_id IN ( ".implode(",", $parent_array).")";
        $x = Relationship::whereRaw($sql)->get();
        $currentNodes = array();
        for($i = 0; $i < count($x); ++$i){
            $tree[] = $x[$i]["left_hand"];
            if($x[$i]["left_hand"] != null){
                $currentNodes[] = $x[$i]["left_hand"];
            }
            $tree[] = $x[$i]["right_hand"];
            if($x[$i]["right_hand"] != null){
                $currentNodes[] = $x[$i]["right_hand"];
            }
        }
        if(count($x) == 0)
            return $tree;
        return static::genealogy($currentNodes, ++$level, $tree);
    }
} 