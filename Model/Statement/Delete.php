<?php

namespace Model\Statement;

class Delete extends CommonStatement
{   

    public function __toString():string
    {
        $query = 'DELETE FROM ' . $this->tableName;
        $query = $this->appendJoinToQuery($query);
        $query = $this->appendWhereClausesToQuery($query);
        return $query;
    }


}
