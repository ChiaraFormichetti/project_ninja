<?php

namespace Model\Storage;


use Model\QueryBuilder;

class GiftsStorage extends BaseStorage
{
    protected $connection;
    protected $queryBuilder;
    protected $result = [
        'data' => [],
        'errors' => [],
        ];

    public function __construct()
    {
        parent::__construct('gifts');
    }

    public function getType(string $type): array
    {
        try {
            $this->queryBuilder->select()
                ->selectColumns(['*'])
                ->where('type', '=', $type)
                ->where('available', '=', 1);
            $query = $this->queryBuilder->getQuery();
            $id = [];
            foreach ($this->connection->query($query) as $row) {
                $id[] = $row;
            }
            if (count($id)) {
                $this->result['data'] = $id;
            }
        } catch (\Exception $e) {
            $this->result['errors'] = $e->getMessage();
        }
        return $this->result;
    }
}