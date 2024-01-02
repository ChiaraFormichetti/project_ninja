<?php

namespace Model\Storage;


use Model\QueryBuilder;

class BeneficiariesStorage extends BaseStorage
{
    protected $connection;
    protected $queryBuilder;
    protected $result = [
        'data' => [],
        'success' => false,
        'errors' => []
    ];

    public function __construct()
    {
        parent::__construct('beneficiaries');
    }

    public function postAdd(array $body): array{
       
        try{
            $this->queryBuilder->insert()
            ->insert_into($body);
            $query = $this->queryBuilder->getQuery();
            $ris = $this->connection->query($query);
            if($ris->rowCount() === 1){
                $this->result["success"] = true;
            }
        } catch(\Exception $e){
            $this->result['errors'] = $e->getMessage();
        }
        return $this->result;
    }
}
