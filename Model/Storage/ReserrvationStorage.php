<?php

namespace Model\Storage;


use Model\QueryBuilder;
use Model\Table\Reservation;

class ReservationStorage extends BaseStorage
{
   protected $connection;
   protected $queryBuilder;
   protected $reservation;
   protected $tableColumns;
   public function __construct(QueryBuilder $queryBuilder, Reservation $reservation)
   {
      parent::__construct();
      $this->queryBuilder = $queryBuilder;
      $this->reservation = $reservation;
   }
   /*public function translateReservation()
   {
      $this->tableColumns = $this->rv->getTableColumns();
      $this->qb->select($this->tableColumns);
   }
   */
   //Stampa tutte le prenotazioni valide
   public function getReservation()
   {
     $this->select->selectColumns(['*'])
                           ->where('ingresso', '>=', 'CURRENT_DATE()')
                           ->where('cancellazione', '=', 0, 'AND')
                           ->orderBy('ingresso');

      $query = $this->queryBuilder->__toString();
      $reservation = [];
      foreach ($this->connection->query('"' . $query . '"') as $row) {
         $reservation[] = $row;
      }
      return $reservation;
   }
   //Stampa le prenotazioni passate
   public function getHistoricReservation()
   {
      $this->qb->where('ingresso', '<', 'CURRENT_DATE()')
         ->where('cancellazione', '=', 0, 'AND')
         ->orderBy('ingresso');

      $query = $this->qb->toSql();
      $reservation = [];
      foreach ($this->connection->query('"' . $query . '"') as $row) {
         $reservation[] = $row;
      }
      return $reservation;
   }
   //Stampa le prenotazioni nel cestino
   public function getTrashReservation()
   {
      $this->qb->where('cancellazione', '=', 1)
         ->orderBy('ingresso');

      $query = $this->qb->toSql();
      $reservation = [];
      foreach ($this->connection->query('"' . $query . '"') as $row) {
         $reservation[] = $row;
      }
      return $reservation;
   }
   //Stampa le prenotazioni cercate
   public function searchReservation($nome, $ingresso)
   {
      $this->qb->where('nome', '=', $nome)
         ->where('ingresso', '=', $ingresso, 'OR')
         ->orderBy('ingresso');
      $query = $this->qb->toSql();
      $reservation = [];
      foreach ($this->connection->query('"' . $query . '"') as $row) {
         $reservation[] = $row;
      }
      return $reservation;
   }
   //Aggiunge una prenotazione
   public function addReservation(array $body)
   {
      $query = $this->qb->insert_into($body);
      $ris = $this->connection->query('"' . $query . '"');
      return $ris->rowCount();
   }
   //Sposta la prenotazione nel cestino
   public function trashReservation(int $id)
   {
      $this->qb->update('cancellazione', 1)
         ->where('id', '=', $id);
      $query = $this->qb->updateQuery();
      $trash = $this->connection->query('"' . $query . '"');
      return $trash;
   }
   //Modifica prenotazione
   public function editReservation($nome, $posti, $ingresso, $uscita, $id)
   {
      $this->qb->update('nome', $nome)
         ->update('posti', $posti)
         ->update('ingresso', $ingresso)
         ->update('uscita', $uscita)
         ->where('id', '=', $id);
      $query = $this->qb->updateQuery();
      $edit = $this->connection->query('"' . $query . '"');
      return $edit;
   }
   //Cancella definitivamente una prenotazione
   public function deleteReservation($id)
   {
      $this->qb->where('id', '=', $id);
      $query = $this->qb->delete($id);
      $delete = $this->connection->query('"' . $query . '"');
      return $delete;
   }
}
