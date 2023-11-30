<?php

namespace Model\Statement;

class Delete extends CommonStatement
{   

    protected function __toString()
    {
        $query = 'DELETE FROM ' . $this->tableName;
        $query = $this->appendJoinToQuery($query);
        $query = $this->appendWhereClausesToQuery($query);
        return $query;
    }


}
