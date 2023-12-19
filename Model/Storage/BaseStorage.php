<?php

namespace Model\Storage;

use Model\Connection;
use Model\QueryBuilder;

class BaseStorage
{
    protected $connection;
    protected $queryBuilder;

    public function __construct($tableName)
    {
        $v = new Connection();
        $this->connection = $v->getConnection();
        $this-> queryBuilder = new QueryBuilder($tableName);
    }


    public function getAll(){
            $this->queryBuilder->selectColumns(['*']);
            $query = $this->queryBuilder->getQuery();
            $items = [];
            foreach ($this->connection->query($query) as $row) {
               $items[] = $row;
            }
            $count = count($items);
            return [
               'items' => $items,
               'totalPages' => 1,
               'count' => $count,
            ];
         }

    public function getPages($page = null, $itemsForPage = null, $parameters = [])
         {          
            $this->queryBuilder->selectColumns(['id']);
            $query =  $this->queryBuilder->getQuery();
            $ids = [];
            foreach ($this->connection->query($query) as $row) {
               $ids[] = $row['id'];
            }
            $count = count($ids);
            //numero di prenotazioni che vogliamo per pagina (opzionale, poichè di default è 10)
            $resultForPage = (isset($itemsForPage) && is_numeric($itemsForPage)) ? $itemsForPage : 10;
            //numero di pagina totali che abbiamo in base al numero di prenotazioni da stampare(arrotondiamo per eccesso)
            $totPages = ceil($count / $resultForPage);
            //pagina corrente che vogliamo stampare, se non la specifichiamo di default è la prima
            //il controllo is numeric da fare al service [is_numeric($page)] 
            $currentPage = (isset($page)) ? $page : 1;
            //primo parametro del limit, ci definisce da quale prenotazione parte la pagina che stiamo stampando
            $start = ($currentPage - 1) * $resultForPage;
            //count ci definisce il numero di items totali
            //lo start ci serve pe ril limit
            //result for pages è il secondo parametro del limit
            //totalPages è il numero di pagine totali
            return [
                'id' => $ids,
                'count' => $count,
                'totalPages' => $totPages,
                'start' => $start,
                'resultForPage' => $resultForPage,
            ];
         }
      
    }








