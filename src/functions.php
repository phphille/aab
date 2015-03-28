<?php
/**
 * Bootstrapping functions, essential and needed for Anax to work together with some common helpers.
 *
 */



/**
 * Utility for debugging.
 *
 * @param mixed $array values to print out
 *
 * @return void
 */
function dump($array)
{
    echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}



/**
 * Sort array but maintain index when compared items are equal.
 * http://www.php.net/manual/en/function.usort.php#38827
 *
 * @param array    &$array       input array
 * @param callable $cmp_function custom function to compare values
 *
 * @return void
 *
 */
function mergesort(&$array, $cmp_function)
{
    // Arrays of size < 2 require no action.
    if (count($array) < 2) return;
    // Split the array in half
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway);
    $array2 = array_slice($array, $halfway);
    // Recurse to sort the two halves
    mergesort($array1, $cmp_function);
    mergesort($array2, $cmp_function);
    // If all of $array1 is <= all of $array2, just append them.
    if (call_user_func($cmp_function, end($array1), $array2[0]) < 1) {
        $array = array_merge($array1, $array2);
        return;
    }
    // Merge the two sorted arrays into a single sorted array
    $array = array();
    $ptr1 = $ptr2 = 0;
    while ($ptr1 < count($array1) && $ptr2 < count($array2)) {
        if (call_user_func($cmp_function, $array1[$ptr1], $array2[$ptr2]) < 1) {
            $array[] = $array1[$ptr1++];
        } else {
            $array[] = $array2[$ptr2++];
        }
    }
    // Merge the remainder
    while ($ptr1 < count($array1)) $array[] = $array1[$ptr1++];
    while ($ptr2 < count($array2)) $array[] = $array2[$ptr2++];
    return;
}




function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}





/*
function is_val_exists($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && is_val_exists($needle, $element))
               return true;
     }
   return false;
}
*/


function tags($data){

    $tmp = array();
    foreach ($data as $array) {
        foreach ($array as $row) {
            $tmp[] = $row;
        }
    }

    //dump($data);
    $data = array_map(function ($v) {
        return (array) $v ; // convert to array
    }, $tmp);
    //dump($data);

    $new = array();
    $i = 0;
    foreach($data as $value) {
        $new = array_merge_recursive($new, $value);
    }
    //dump($new);

    $values = array_unique(array_diff_assoc($new['idTags'], array_unique($new['idTags'])));
    //dump($values);
    if($values){
        foreach($values as $val){
            $keys[] = array_keys($new['idTags'], $val);
        }
        //dump($keys);
        foreach($keys as $array){
            foreach($array as $val){
                $newIdTag[] = $new['idTags'][$val];
                $newName[] = $new['name'][$val];
                break;
            }
        }
        foreach($keys as $array){
            $score = 0;
            foreach($array as $val){
                $score = $score + $new['total'][$val];
                unset($new['total'][$val]);
                unset($new['idTags'][$val]);
                unset($new['name'][$val]);
            }
            $newScore[] = $score;
        }
        /*
        dump($new);
        dump($newIdTag);
        dump($newName);
        dump($newScore);
        */
        for($i=0; $i<count($newIdTag); $i++){
            array_push($new['total'], $newScore[$i]);
            array_push($new['idTags'], $newIdTag[$i]);
            array_push($new['name'], $newName[$i]);
        }
    }

    $arr1 = array_values($new['name']);
    $arr2 = array_values($new['idTags']);
    $arr3 = array_values($new['total']);


    $correctArray = array();
    for($i=0; $i<count($new['idTags']); $i++){
        $correctArray[] = array('id' => $arr2[$i], 'name' => $arr1[$i], 'total'=> $arr3[$i]);
    }
    array_multisort($arr3, SORT_DESC, $correctArray);

    return $correctArray;
}


/*
function get_keys_for_duplicate_values($my_arr, $clean = false) {
    if ($clean) {
        return array_unique($my_arr);
    }

    $dups = $new_arr = array();
    foreach ($my_arr as $key => $val) {
      if (!isset($new_arr[$val])) {
         $new_arr[$val] = $key;
      } else {
        if (isset($dups[$val])) {
           $dups[$val][] = $key;
        } else {
           $dups[$val] = array($key);
        }
      }
    }
    return $dups;
}
*/



function get_first_sentence($string) {
    $array = preg_split('/(^.*\w+.*[\.\?!][\s])/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
    // You might want to count() but I chose not to, just add
    if(isset($array[1])){
        return trim($array[0] . $array[1]);
    }
    else{
        return trim($array[0]);
    }
}



