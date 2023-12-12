<?php

namespace Model\Storage;


use Model\QueryBuilder;
use Model\Table\Reservation;


class ReservationStorage extends BaseStorage
{
   protected $connection;
   protected $queryBuilder;
   //protected $tableColumns;
   public function __construct()
   {
      parent::__construct();
      $this->queryBuilder = new QueryBuilder('prenotazioni');
   }
   //ci potremmo fare i controlli, abbiamo l'array delle colonne, controlliamo che le colonne inserite siano all'interno dell'array
   //il controllo lo farei più nel manager che nello storage!!!!
   //quindi l'oggetto new reservation lo faremo li, posto in cui faremo il manager di tutti gli errori !

   public function getReservation()
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('ingresso', '>=', date('Y-m-d'))
            ->where('cancellazione', '=', 0, 'AND')
            ->orderBy('ingresso');

         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
         //se nella query builder viene lanciata un'eccezione, la catturiamo, registriamo il messaggio di errore nel log degli errori
         //e restituiamo un array vuoto !
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni: ' . $e->getMessage());
         return $e->getMessage();
      }
   }

   public function getReservationById($id)
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero della prenotazione: ' . $e->getMessage());
         return $e->getMessage();
      }
   }

   //Stampa le prenotazioni passate
   public function getHistoricReservation()
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('ingresso', '<', date('Y-m-d'))
            ->where('cancellazione', '=', 0, 'AND')
            ->orderBy('ingresso');

         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni nello storico: ' . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Stampa le prenotazioni nel cestino
   public function getTrashReservation()
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('cancellazione', '=', 1)
            ->orderBy('ingresso');

         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni nel cestino: ' . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Stampa le prenotazioni cercate
   public function searchReservation($name, $enter)
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*']);
         if ($name) {
            //La clausola like consente di eseguire una ricerca di stringhe simili o paziali (senza il '%' ci dà la corrispondenza esatta)
            //Il wildcard % indica che la stinga $name può essere tovata in qualsiasi parte del campo 'nome' (pima e/o dopo)
            $this->queryBuilder->where('nome', 'LIKE', '%' . $name . '%');
         }
         if ($enter) {
            $this->queryBuilder->where('ingresso', '=', $enter, 'OR');
         }
         $this->queryBuilder
            ->where('ingresso', '>=', date('Y-m-d'), 'AND')
            ->where('cancellazione', '=', 0, 'AND')
            ->orderBy('ingresso');
         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni da cercare: ' . $e->getMessage());
         return $e->getMessage();
      }
   }

   //Aggiunge una prenotazione
   public function addReservation(array $body)
   {
      try {
         $this->queryBuilder->insert()
            ->insert_into($body);
         $query = $this->queryBuilder->getQuery();
         $ris = $this->connection->query($query);
         return $ris->rowCount();
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }

   //Sposta la prenotazione nel cestino
   public function trashReservation(int $id)
   {
      try {
         $this->queryBuilder->update()
            ->updateFunction('cancellazione', 1)
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $trash = $this->connection->query($query);
         return $trash;
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Modifica prenotazione
   public function editReservation(array $updateData, int $id)
   {  //per ogni colonna set value
      try {
         $this->queryBuilder->update();
         foreach ($updateData as $column => $value) {
            $this->queryBuilder->updateFunction($column, $value);
         }
         $this->queryBuilder->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $edit = $this->connection->query($query);
         return $edit;
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Cancella definitivamente una prenotazione
   public function deleteReservation($id)
   {
      try {
         $this->queryBuilder->delete()
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $delete = $this->connection->query($query);
         return $delete;
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
}
