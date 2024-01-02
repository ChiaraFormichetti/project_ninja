<?php

namespace Model\Storage;


use Model\QueryBuilder;

class CodesStorage extends BaseStorage
{
    protected $connection;
    protected $queryBuilder;
    protected $result = [
        'data' => [],
        'errors' => []
    ];

    public function __construct()
    {
        parent::__construct('codes');
    }

    public function getCheck(string $code): array
    {
        try {
            $this->queryBuilder->select()
                ->selectColumns(['*'])
                ->where('code', '=', $code);
            $query = $this->queryBuilder->getQuery();
            $id = [];
            foreach ($this->connection->query($query) as $row) {
                $id[] = $row;
            }
            if (count($id) === 1) {
                $this->result['data'] = $id;
            }
        } catch (\Exception $e) {
            $this->result['errors'] = $e->getMessage();
        }
        return $this->result;
    }
}