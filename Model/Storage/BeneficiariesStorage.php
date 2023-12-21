<?php

namespace Model\Storage;


use Model\QueryBuilder;

class BeneficiariesStorage extends BaseStorage
{
    protected $connection;
    protected $queryBuilder;
    protected $result = [
        'success' => false,
        'errors' => []
    ];

    public function __construct()
    {
        parent::__construct('beneficiaries');
    }

    public function getCheck(string $code): array
    {
        try {
            $this->queryBuilder->select()
                ->selectColumns(['id'])
                ->where('code', '=', $code);
            $query = $this->queryBuilder->getQuery();
            $id = [];
            foreach ($this->connection->query($query) as $row) {
                $id[] = $row;
            }
            if (count($id) === 1) {
                $this->result['success'] = true;
            }
        } catch (\Exception $e) {
            $this->result['errors'] = $e->getMessage();
        }
        return $this->result;
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
