<?php
///This file has 8 valuable functions used for several situations


    
    function strhas(string $character, string $string){
        /***check for character in a string***/
        for($i = 0;$i < strlen($string);$i++){
            if($character == $string[$i]){
                return true;
            }
        }
        return false;
    }

    //split string
    function strsplit(string $string,string $character){
        $arr = array();
        $k = 0;
        for($i = 0;$i < strlen($string);$i++){
            if($string[$i] == $character){
                $temp = '';
                for($j = 0;$j < $i;$j++){
                    $temp = $temp.$string[$j];
                }
                $arr[$k] = $temp;
                $temp1 = '';
                for($j = $i+1;$j<strlen($string);$j++){
                    $temp1 = $temp1.$string[$j];
                }
                $string = $temp1;
                $k++;
                $i = 0;
                if(!strhas($character, $string)){
                    $arr[$k] = $string;
                }
            }
        }
        if(sizeof($arr) == 0){
            $arr[0] = $string;
        }
        return $arr;
    }

    //replace a character in a string
    function replaceChar(string $string, string $previous_char, string $new_char, $nb_to_remove = NULL){
        if($nb_to_remove <0){
            return false;
        }
        if($nb_to_remove == 1){
            for($i = 0;$i < strlen($string);$i++){
                if($string[$i] == $previous_char){
                    $string[$i] = $new_char;
                    return $string;
                }
            }
            echo $string." doesn't contain ".$previous_char;
            return false;
        }elseif($nb_to_remove == NULL){
            for($i = 0;$i < strlen($string);$i++){
                if($string[$i] == $previous_char){
                    $string[$i] = $new_char;
                }
            }
            return $string;
        }else{
            $i = 0;
            while($i <$nb_to_remove){
                    for($j = 0;$j < strlen($string);$j++){
                        if($string[$j] == $previous_char){
                            $string[$j] = $new_char;
                            $i += 1;
                        }
                    }
            }
            return $string;
        }
    }

    //remove character from a string
    function removeChar(string $string, string $character = NULL, int $nb_to_remove = NULL,int $index = -1){
        if($index != -1){
            $temp = "";
            for($i = 0;$i < strlen($string);$i++){
                if($i == $index){
                    continue;
                }
                $temp[$i] = $string[$i];
            }
            return $temp;
        }
        if($nb_to_remove < 0){
            return false;
        }elseif($nb_to_remove == NULL){
            $result = '';
            for($i = 0;$i < strlen($string);$i++){
                if($string[$i] == $character){
                    continue;
                }
                $result .= $string[$i];
            }
            return $result;
        }else{
            $result = '';
            $j = 0;
            for($i = 0;$i < strlen($string);$i++){
                if($string[$i] == $character){
                    continue;
                }
                $result .= $string[$i];
            }
            return $result;
        }
    }

    //check if a directory is empty
    function containsFiles(string $directory){
        $result = scandir($directory);
        if(is_bool($result)){
            return "The provided string is not a directory";
        }elseif(sizeof(scandir($directory)) == 2){
            return false;
        }else{
            return true;
        }
    }

    //empty a directory
    function emptyDir(string $directory){
        while(containsFiles($directory)){
            $arr = scandir($directory);
            foreach($arr as $ar){
                if(is_file($directory."/".$ar)){
                    unlink($directory."/".$ar);
                }elseif($ar == "." || $ar == ".."){
                    continue;
                }else{
                    emptyDir($directory."/".$ar);
                }
            }
        }
        return rmdir($directory);
    }

    //count the existence of a character
    function countChar(string $string, string $character){
        $count = 0;
        for($i = 0;$i < strlen($string);$i++){
            if($string[$i] == $character){
                $count += 1;
            }
        }
        return $count;
    }

    //remove a value from array
    function arrayRemove(array $array, int $index){
        $arr = array();
        if(!is_array($array)){
            return "The given value is not an array";
        }
        if(sizeof($array) >= $index){
            return "The index given is greater than the size of the array";
        }
        for($i = 0;$i < sizeof($array);$i++){
            if($i == $index){
                continue;
            }
            $arr[$i] = $array[$i];
        }
        return $arr;
    }
    function GetDirectorySize($path){
        $bytestotal = 0;
        $path = realpath($path);
        if($path!==false && $path!='' && file_exists($path)){
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }
    function strPartition(string $string,int $start,int $end){
        $temp = '';
        for($i = 0;$i+$start < $end;$i++){
            $temp .= $string[$i+$start];
        }
        return $temp;
    }
    function strhasstr(string $string,string $needle){
        $i = 0;
        $size = strlen($needle);
        if(strlen($string) < $size){
            return false;
        }
        for($i;$i < strlen($string);$i++){
            if($i+$size > strlen($string)){
                return false;
            }
            if(strcmp(strPartition($string,$i,$size+$i),$needle) == 0){
                return $i;
            }
        }
        return false;
    }
?>