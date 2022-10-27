<?php

class Search {

    public function ShowEvents($search) {
        $q = "select *, match(startDate, endDate, numOfAudience) against ('$search') as relevance "
                . "from Proj_Event where match(startDate, endDate, numOfAudience) against ('" . $search . "')";
        $q .= " ORDER BY match(startDate, endDate, numOfAudience) against ('" . $search . "') DESC";
        $result = $this->showResults($q);
        return  $result;
    }

    public function ShowAdvanced($search) {
        $q = "select *, match(startDate, endDate, numOfAudience) against ('$search' IN BOOLEAN MODE) as relevance"
                    . " from Proj_Event where match(startDate, endDate, numOfAudience) "
                    . "against ('" . $search . "' IN BOOLEAN MODE)";
              $q .= " ORDER BY match(MessageText, Subject) against ('".$search. "') DESC" ; 
        $result = $this->showResults($q);
        return  $result;
    }

    function showResults($q) {
        $db = Database::getInstance();
        $data = $db->multiFetch($q);
        if (!empty($data)) {
            $row_cnt = count($data);
            if ($row_cnt == 0) {
                echo '<br>';
                echo '<p>sorry no events were found that match your query</p>';
            } else {
                return $data;
            }
        } else {
            echo '<p class="error"> sorry no events were found that match your query</p>';
        }
    }

    function handleAll($text) {

        echo $text;
        $search = explode(' ', $text);
        // print_r($search);

        $returnText = '';

        foreach ($search as $term) {
            $term = '+' . $term . ' ';
            $returnText .= $term;
        }

        $returnText = trim($returnText);

        return $returnText;
    }

    function handleNone($text) {

        $search = explode(' ', $text);

        $returnText = '';

        foreach ($search as $term) {
            $term = '-' . $term . ' ';
            $returnText .= $term;
        }

        return $returnText;
    }

    function handlePart($text) {

        $search = explode(' ', $text);

        $returnText = '';

        foreach ($search as $term) {
            $term = $term . '* ';
            $returnText .= $term;
        }

        $returnText = trim($returnText);

        return $returnText;
    }

    function handleExact($text) {

        $returnText = '"' . $text . '"';

        return $returnText;
    }

    function handleFirst($text) {

        $search = explode(' ', $text);

        $returnText = '+' . $search[0] . ' -' . $search[1];
        /*
          foreach($search as $term){
          $term = '-'.$term. ' ';
          $returnText .= $term;
          } */

        return $returnText;
    }

    function handleRank($text) {
        $search = explode(' ', $text);

        $returnText = ' (';

        foreach ($search as $term) {
            $term = '+' . $term . ' ';
            $returnText .= $term;
        }

        $returnText = trim($returnText);

        $returnText .= ') (' . $text . ') ';

        foreach ($search as $term) {
            $term = $term . '* ';
            $returnText .= $term;
        }
        $returnText = trim($returnText);

        return $returnText;
    }

}

?>
