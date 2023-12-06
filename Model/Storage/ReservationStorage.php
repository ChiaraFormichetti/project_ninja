<?php

namespace Model\Storage;


use Model\QueryBuilder;
use Model\Table\Reservation;

class ReservationStorage extends BaseStorage
{
   protected $connection;
   protected $queryBuilder;  
   protected $reservationTable;
   //protected $tableColumns;
   public function __construct()
   {
      parent::__construct();
      $this->queryBuilder = new QueryBuilder('prenotazioni');
      $this->reservationTable = new Reservation();
   }

   public function translateReservation()
   {
      $tableColumns = $this->reservationTable->getTableColumns();
      return $tableColumns;
   }

   public function getReservation()
   {
     $this->queryBuilder->select()
                           ->selectColumns(['*'])
                           ->where('ingresso', '>=', date('Y-m-d'))
                           ->where('cancellazione', '=', 0, 'AND')
                           ->orderBy('ingresso');

      $query = $this->queryBuilder->getQuery();
      $reservations = [];
      foreach ($this->connection->query( $query ) as $row) {
         $reservations[] = $row;
      }
      return $reservations;
   }

   //Stampa le prenotazioni passate
   public function getHistoricReservation()
   {
      $this->queryBuilder->select()
         ->selectColumns(['*'])
         ->where('ingresso', '<', date('Y-m-d'))
         ->where('cancellazione', '=', 0, 'AND')
         ->orderBy('ingresso');

      $query = $this->queryBuilder->getQuery();
      $reservations = [];
      foreach ($this->connection->query( $query ) as $row) {
         $reservations[] = $row;
      }
      return $reservations;
   }
   //Stampa le prenotazioni nel cestino
   public function getTrashReservation()
   {
      $this->queryBuilder->select()
         ->selectColumns(['*'])
         ->where('cancellazione', '=', 1)
         ->orderBy('ingresso');

      $query = $this->queryBuilder->getQuery();
      $reservations = [];
      foreach ($this->connection->query($query ) as $row) {
         $reservations[] = $row;
      }
      return $reservations;
   }
   //Stampa le prenotazioni cercate
   public function searchReservation($name, $enter)
   {
      $this->queryBuilder->select()
         ->selectColumns(['*']);
         if($name){
            //La clausola like consente di eseguire una ricerca di stringhe simili o paziali (senza il '%' ci dà la corrispondenza esatta)
            //Il wildcard % indica che la stinga $name può essere tovata in qualsiasi parte del campo 'nome' (pima e/o dopo)
            $this->queryBuilder->where('nome','LIKE','%'.$name.'%');
         }
         if($enter){
            $this->queryBuilder->where('ingresso', '=' , $enter, 'OR');
         }
         $this->queryBuilder
         ->where('ingresso', '>=', date('Y-m-d'),'AND')
         ->where('cancellazione', '=', 0, 'AND')
         ->orderBy('ingresso');
      $query = $this->queryBuilder->getQuery();
      $reservations = [];
      foreach ($this->connection->query($query) as $row) {
         $reservations[] = $row;
      }
      return $reservations;
   }
   
   //Aggiunge una prenotazione
   public function addReservation(array $body)
   {
      $this->queryBuilder->insert()
         ->insert_into($body);
         $query = $this->queryBuilder->getQuery();
      $ris = $this->connection->query( $query );
      return $ris->rowCount();
   }
   //Sposta la prenotazione nel cestino
   public function trashReservation(int $id)
   {
      $this->queryBuilder->update()
         ->updateFunction('cancellazione', 1)
         ->where('id', '=', $id);
      $query = $this->queryBuilder->getQuery();
      $trash = $this->connection->query( $query );
      return $trash;
   }
   //Modifica prenotazione
   public function editReservation(array $updateData,int $id)
   {  //per ogni colonna set value
      $this->queryBuilder->update();
         foreach($updateData as $column => $value){
            $this->queryBuilder->updateFunction($column,$value);
         }
         $this->queryBuilder->where('id','=',$id);
      $query = $this->queryBuilder->getQuery();
      $edit = $this->connection->query($query );
      return $edit;
   }
   //Cancella definitivamente una prenotazione
   public function deleteReservation($id)
   {
      $this->queryBuilder->delete()
         ->where('id', '=', $id);
      $query = $this->queryBuilder->getQuery();
      $delete = $this->connection->query( $query );
      return $delete;
   }
}
