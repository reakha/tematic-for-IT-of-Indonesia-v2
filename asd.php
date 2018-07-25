<?php

$a1=array(
        array(
            "a"=>1,
            "b"=>2,
            "c"=>"gre",
            "d"=>"yellow"
        ),
        array(
            "a"=>"red",
            "b"=>"green",
            "c"=>"blue",
            "d"=>"yellow"
        ),
        array(
            "a"=>"red",
            "b"=>5,
            "c"=>"blue",
            "d"=>1
        ),
        array(
            "a"=>"red",
            "b"=>5,
            "c"=>"blue",
            "d"=>1
        ),
        array(
            "a"=>"red",
            "b"=>5,
            "c"=>"blue",
            "d"=>1
        )
    );

$a2=array(
        array(
            "e"=>1,
            "f"=>2,
            "g"=>3
        ),
        array(
            "e"=>1,
            "f"=>5,
            "g"=>6
        ),
        array(
            "e"=>1,
            "f"=>5,
            "g"=>6
        )
      
        //[0]=>4
    );

    $a = array();
    foreach ($a2 as $key => $value) 
    {
        for ($i=0; $i < count($a1) ; $i++) 
        { 
           $a[$key][] = array_intersect($value, $a1[$i]);
           // $a[$key][] = array_intersect($value, $a1[0]);
           // $a[$key][] = array_intersect($value, $a1[1]);
           // $a[$key][] = array_intersect($value, $a1[2]);
           // $a[$key][] = array_intersect($value, $a1[3]);
        //     echo "<pre>";
        // print_r($a1[$i]);
        // echo "<pre>";
        }
    }

             foreach ($a as $key => $value) 
                {
                    foreach ($value as $key1 => $value1) 
                    {
                        if (count($value1)==0) 
                        {
                            $csv[$key][] = count($value1)/1;
                        }
                        else 
                        {
                           $csv[$key][] = count($value1)/count($a2[$key]);
                        }
                        //$csv[$key][] = array_sum($value1)/count($value1);/count($a2[$key])
                        
                    }
                        echo "<pre>";
                        // print_r(array_sum($value1));
                        //print_r(count($a2[$key]));
                        // print_r($plus_all_indekS[$key]);
                        echo "<pre>";
                }
   
        echo "1111111111111111111111111111111";
        echo "<pre>";
        print_r($a1);
        print_r($a2);
        echo "<pre>";

        echo "resulttttttttttttttttttttttttttttttttttt :";
        echo "<pre>";
        print_r($a);
        echo "<pre>";
         echo "sameeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee :";
        echo "<pre>";
        print_r($csv);
        echo "<pre>";


?>